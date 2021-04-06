<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserProfilType;
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
    public function profil(Request $request, EntityManagerInterface $entityManager)
    {
        //Récupère le paramètre indiqué dans l'URL
        $user = new User();
        $form = $this->createForm(UserProfilType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('user/profil.html.twig', ['userProfilForm'=>$form->createView()]);
    }

}