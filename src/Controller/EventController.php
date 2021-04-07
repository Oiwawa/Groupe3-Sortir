<?php


namespace App\Controller;


use App\Entity\Event;
use App\Form\EventCreateType;
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

        $event = new Event();
        $form = $this->createForm(EventCreateType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($event);
            $entityManager->flush();
        }

        return $this->render('event/create.html.twig', ['eventCreateForm'=>$form->createView()]);
    }

}