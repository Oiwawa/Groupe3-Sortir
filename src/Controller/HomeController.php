<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EntityManagerInterface $entityManager){
        if(!is_null($this->getUser())){

        return $this->render('home/index.html.twig');
        }
        return $this->redirectToRoute('app_login');
    }

}