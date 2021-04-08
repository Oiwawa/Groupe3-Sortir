<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use App\Form\CancelEventFormType;
use App\Form\EventCreateType;
use App\Form\PlaceType;
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
        $placeForm = $this->createForm(PlaceType::class, $place);
        $placeForm->handleRequest($request);

        $event = new Event();
        $event->setPlace($place);
        $organizer = $entityManager->getRepository('App:User')->findOneBy(['username' => $this->getUser()->getUsername()]);
        $event->setOrganizer($organizer);

        $form = $this->createForm(EventCreateType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $placeForm->isSubmitted() && $placeForm->isValid() ){

            $entityManager->persist($place);
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a bien été crée');

            $eventDetail = $entityManager->getRepository('App:Event')->findOneBy(['name' => $request->get('name')]);

            return $this->redirectToRoute('event_detail', ['id'=>$event->getId(), 'event' => $eventDetail]);
        }

        return $this->render('event/create.html.twig',
            ['eventCreateForm'=>$form->createView(),
            'placeForm'=>$placeForm->createView()]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route(path="{id}", name="detail")
     */
    public function detail(EntityManagerInterface $entityManager, Request $request): Response
    {
        //Update l'event si modification
        $event = $entityManager->getRepository('App:Event')->findOneBy(['id' => $request->get('id')]);
        $updateEventForm = $this->createForm(EventCreateType::class, $event);
        $updateEventForm->handleRequest($request);
        //Update le lieu si modification
        $placeForm = $this->createForm(PlaceType::class, $event->getPlace());
        $placeForm->handleRequest($request);
        return $this->render('event/detail.html.twig',
            ['updateEventForm' => $updateEventForm->createView(),
            'placeForm' => $placeForm->createView()
            ,'event'=>$event]);
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

        $cancelForm = $this->createForm(CancelEventFormType::class);
        return $this->render('event/cancel.html.twig',
            ['cancelForm'=>$cancelForm->createView(),
                'event' => $event]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse
     * @Route(path="delete/{id}", name="delete")
     */
    public function delete(EntityManagerInterface $entityManager,Request $request): RedirectResponse
    {
        $entityManager ->remove($event = $entityManager->getRepository(Event::class)->findOneBy(['id' => $request->get('id')]));
        $entityManager->flush();
        $this->addFlash('success','L\'événement a bien été annulé !');
        return $this->redirectToRoute('home_index');
    }
}