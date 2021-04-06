<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route(path="", name="home_")
 */
class HomeController extends AbstractController
{

    /**
     * @Route(path="", name="index", methods={"GET"}
     */
    public function index(){

        return $this->render('home/index.html.twig');
    }
    public function login(){

        return $this->render('security/login.html.twig');
    }
}