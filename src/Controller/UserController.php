<?php


namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route(path="user/", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="{username}", name="profil")
     */
    public function profil(Request $request, EntityManagerInterface $entityManager){
        //Récupère le paramètre indiqué dans l'URL
        $usernames = $entityManager->getRepository('App:User')->find($request->get('username'));

        //TODO formulaire de modification du profil
        return $this->render('user/profil.html.twig', ['usernames', $usernames]);
    }

}