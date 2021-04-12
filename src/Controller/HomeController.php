<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route(path="", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route(path="", name="index", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        //Si l'user n'est pas connecté, renvoi vers la page de connexion
        if (is_null($this->getUser())) {
        return $this->redirectToRoute('app_login');
        }

        $eventList = $entityManager->getRepository('App:Event')->findAll();


            return $this->render('home/index.html.twig', ['eventList'=>$eventList]);
    }


}