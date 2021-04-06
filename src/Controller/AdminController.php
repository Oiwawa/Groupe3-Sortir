<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @Route(path="admin/", name="admin_")
 */
class AdminController extends AbstractController

{
    /**
     * @Route(path="campus", name="campus", methods={"GET"})
     */
    public function campus(EntityManagerInterface $em , Request $request){




        return $this->render('admin/campus.html.twig');
    }

    /**
     * @Route(path="villes", name="villes", methods={"GET"})
     */

    public function city(){


        return $this->render('admin/city.html.twig');
    }


}