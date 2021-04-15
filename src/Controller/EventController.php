<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventCancelFormType;
use App\Form\EventCreateType;
use DateTime;
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
        $event->setCurrentSubs(0);
        $form = $this->createForm(EventCreateType::class, $event);

        if ($event->getLimitDate() >! $event->getEventDate()) {
            $this->addFlash('warning', 'Les dates indiqués ne sont pas valides.');
        }
        $form->handleRequest($request);
        $message = '';

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('register')->isClicked()) {
                $event->setState($state = $entityManager->getRepository('App:EventState')->find(1));
                $message = 'Votre sortie a bien été enregistré! Vous pouvez toujours la modifier, puis la publier ou la supprimer sur cette page.';

            } elseif ($form->get('publish')->isClicked()) {
                $event->setState($state = $entityManager->getRepository('App:EventState')->find(2));
                $message = 'Votre sortie a bien été publié! Vous pouvez la modifier jusqu\'au début de l\'événement.';
            }
            $entityManager->persist($place);
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', $message);

            $eventDetail = $entityManager->getRepository('App:Event')->findOneBy(['name' => $request->get('name')]);

            return $this->redirectToRoute('event_detail',
                ['id' => $event->getId(),
                    'event' => $eventDetail]);
        }
        return $this->render('event/create.html.twig',
            ['eventCreateForm' => $form->createView()]);
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
        if ($this->getUser() === $event->getOrganizer() && $event->getState()->getId() != 4) {
            //Update l'event si modification
            $updateEventForm = $this->createForm(EventCreateType::class, $event);
            $updateEventForm->handleRequest($request);

            //Update le lieu si modification
            if ($updateEventForm->get('register')->isClicked()) {
                $event->setState($state = $entityManager->getRepository('App:EventState')->find(1));
                $entityManager->flush();
            } elseif ($updateEventForm->get('publish')->isClicked()) {
                $event->setState($state = $entityManager->getRepository('App:EventState')->find(2));
                $this->addFlash('success', 'Votre sortie est publiée!');
                $entityManager->flush();
                return $this->redirectToRoute('home_index');
            }


            $participants = $event->getSubscribers();
            return $this->render('event/editEvent.html.twig',
                ['updateEventForm' => $updateEventForm->createView()
                    , 'event' => $event,
                    'participants' => $participants]);
        }
        $participants = $event->getSubscribers();

        //Test si l'utilisateur est déjà inscrit ou non
        $subOrNot = false;
        foreach ($participants as $participant) {
            if ($this->getUser() == $participant) {
                $subOrNot = true;
            }
        }
        //Renvoie vers une page de détail sans modification possible
        return $this->render('event/detailEvent.html.twig', [
            'event' => $event,
            'participants' => $participants,
            'subOrNot' => $subOrNot]);
    }

    /**
     * @Route(path="subscribe/{id}", name="subscribe")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse
     */
    public function subToEvent(EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        //Récupération de l'événement
        $event = $entityManager->getRepository('App:Event')->findOneBy(['id' => $request->get('id')]);
        //Test si l'événement est ouvert
        if ($event->getState() == $state = $entityManager->getRepository('App:EventState')->find(2)) {

            //Test si la date limite n'est pas dépassé
            if ($event->getLimitDate() > (new DateTime("now"))) {

                //Test si il reste de la place dans l'événement
                if ($event->getCurrentSubs() < $event->getNbrPlace()) {

                    //Ajout de l'utilisateur + incrémentation du nombre de participants
                    $event->addSubscriber($user = $this->getUser());
                    $event->setCurrentSubs($event->getCurrentSubs() + 1);

                    //Fermeture des inscriptions si l'événement est complet
                    if ($event->getCurrentSubs() == $event->getNbrPlace()) {
                        $event->setState($closed = $entityManager->getRepository('App:EventState')->find(3));
                    }

                    $entityManager->flush();
                    $this->addFlash('success', 'Inscription confirmée.');

                } else {
                    $this->addFlash('warning', 'Cet événement est complet. Vous ne pouvez pas vous y inscrire.');
                }
            } else {
                $this->addFlash('warning', 'La date limite pour s\'inscrire à cet événement est dépassé.');
            }
        } else {
            $this->addFlash('warning', 'Les inscriptions pour cet événement ne sont pas ouvertes.');
        }
        return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
    }

    /**
     * @Route(path="unsubscribe/{id}", name="unsubscribe")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse
     */
    public function unsubToEvent(EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $event = $entityManager->getRepository('App:Event')->findOneBy(['id' => $request->get('id')]);
        $user = $this->getUser();
        $event->removeSubscriber($user);
        $event->setCurrentSubs($event->getCurrentSubs() - 1);
        if ($event->getCurrentSubs() > $event->getNbrPlace()) {
            $event->setState($opened = $entityManager->getRepository('App:EventState')->find(2));
        }
        $entityManager->flush();
        $this->addFlash('success', 'Vous n\'êtes plus inscrit à cet événement.');
        return $this->redirectToRoute('event_detail', ['id' => $event->getId()]);
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
        $cancelForm->handleRequest($request);

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
        $event = $entityManager->getRepository(Event::class)->findOneBy(['id' => $request->get('id')]);
        $event->setState($archive = $entityManager->getRepository('App:EventState')->find(4));
        $entityManager->flush();
        $this->addFlash('success', 'L\'événement a bien été annulé !');
        return $this->redirectToRoute('home_index');
    }


    /**
     * @Route(path="archive/{id}", name="archive")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     */
    public function archive(EntityManagerInterface $entityManager, Request $request)
    {
        $event = $entityManager->getRepository('App:Event')->findOneBy(['id' => $request->get('id')]);
        $event->setState($state = $entityManager->getRepository('App:EventState')->find(1));
        $entityManager->flush();

    }

    public function updateList(EntityManagerInterface $entityManager, Request $request)
    {
        $event = $entityManager->getRepository('App:Event')->findAll();


    }

}