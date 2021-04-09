<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventCancelFormType;
use App\Form\EventCreateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EventController
 * @package App\Controller
 * @Route(path="event/", name="event_")
 */
class EventController extends AbstractController
{

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route(path="create", name="create")
     */
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {

        $place = new Place();

        $event = new Event();
        $event->setPlace($place);
        $organizer = $entityManager->getRepository('App:User')->findOneBy(['username' => $this->getUser()->getUsername()]);
        $event->setOrganizer($organizer);
        $form = $this->createForm(EventCreateType::class, $event);
        $form->handleRequest($request);

        $message = '';
        if ($form->get('register')->isClicked()) {
            $event->setState($state = $entityManager->getRepository('App:EventState')->find(1));
            $message = 'Votre sortie a bien été enregistré! Vous pouvez toujours la modifier, puis la publier ou la supprimer sur cette page.';

        } elseif ($form->get('publish')->isClicked()) {
            $event->setState($state = $entityManager->getRepository('App:EventState')->find(2));
            $message =  'Votre sortie a bien été publié! Vous pouvez la modifier jusqu\'au début de l\'événement.';
        }

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->persist($place);
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', $message);

            $eventDetail = $entityManager->getRepository('App:Event')->findOneBy(['name' => $request->get('name')]);

            $particants = $entityManager->getRepository('App:Event')->allParticipant($event->getId());
            return $this->redirectToRoute('event_detail',
                ['id' => $event->getId(),
                    'event' => $eventDetail]);
        }
        return $this->render('event/create.html.twig',
            ['eventCreateForm' => $form->createView(),
                ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route(path="{id}", name="detail")
     */
    public function detail(EntityManagerInterface $entityManager, Request $request): Response
    {
        //Récupère l'id de l'event pour l'affichage
        $event = $entityManager->getRepository('App:Event')->findOneBy(['id' => $request->get('id')]);
        //Si l'utilisateur est aussi l'organisateur -> affichage du formulaire pour modification

        if ($this->getUser() === $event->getOrganizer()) {
            //Update l'event si modification
            $updateEventForm = $this->createForm(EventCreateType::class, $event);
            $updateEventForm->handleRequest($request);

            //Update le lieu si modification
            if ($updateEventForm->get('register')->isClicked()) {
                $event->setState($state = $entityManager->getRepository('App:EventState')->find(1));
            } elseif ($updateEventForm->get('publish')->isClicked()) {
                $event->setState($state = $entityManager->getRepository('App:EventState')->find(2));
            }

            $entityManager->flush();

            $particants = $entityManager->getRepository('App:Event')->allParticipant($event->getId());
            return $this->render('event/editEvent.html.twig',
                ['updateEventForm' => $updateEventForm->createView()
                    , 'event' => $event,
                    'participants'=> $particants]);
        }
            //$particants = $entityManager->getRepository('App:Event')->allParticipant($event->getId());
            $particants = $event->getSubscribers();

        //Renvoie vers une page de détail sans modification possible
        return $this->render('event/detailEvent.html.twig', [
            'event' => $event,
            'participants'=> $particants]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route(path="cancel/{id}", name="cancel")
     */
    public function cancel(EntityManagerInterface $entityManager, Request $request): Response
    {
        $event = $entityManager->getRepository('App:Event')->findOneBy(['id' => $request->get('id')]);

        $cancelForm = $this->createForm(EventCancelFormType::class);
        return $this->render('event/cancel.html.twig',
            ['cancelForm' => $cancelForm->createView(),
                'event' => $event]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse
     * @Route(path="delete/{id}", name="delete")
     */
    public function delete(EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $entityManager->remove($event = $entityManager->getRepository(Event::class)->findOneBy(['id' => $request->get('id')]));
        //TODO enlever le remove, archiver les sorties
        $entityManager->flush();
        $this->addFlash('success', 'L\'événement a bien été annulé !');
        return $this->redirectToRoute('home_index');
    }

    /**
     * @Route(path="subscribe/{id}", name="subscribe")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse
     */
    public function subToEvent(EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $event = $entityManager->getRepository('App:Event')->findOneBy(['id' => $request->get('id')]);
        $user = $this->getUser();
        $event->addSubscriber($user);
        $entityManager->flush();
        $this->addFlash('success', 'Inscription confirmée');
        return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
    }
}