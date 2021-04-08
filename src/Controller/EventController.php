<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use App\Form\EventCreateType;
use App\Form\PlaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $formPlace = $this->createForm(PlaceType::class, $place);
        $formPlace->handleRequest($request);

        $event = new Event();
        $event->setPlace($place);
        $organizer = $entityManager->getRepository('App:User')->findOneBy(['username' => $this->getUser()->getUsername()]);
        $event->setOrganizer($organizer);

        $form = $this->createForm(EventCreateType::class, $event);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $formPlace->isSubmitted() && $formPlace->isValid() ){

            $entityManager->persist($place);
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Votre sortie a bien été crée');

            return $this->redirectToRoute('event_detail', ['name'=>$event->getName()]);
        }

        return $this->render('event/create.html.twig',
            ['eventCreateForm'=>$form->createView(),
            'placeForm'=>$formPlace->createView()]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route(path="{name}", name="detail")
     */
    public function detail(EntityManagerInterface $entityManager, Request $request): Response
    {
        $event = $entityManager->getRepository('App:Event')->findOneBy(['name' => $request->get('name')]);
        return $this->render('event/detail.html.twig', ['event'=>$event]);
    }
}