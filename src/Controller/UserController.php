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
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="{username}", name="profil")
     */
    public function profil(Request $request, EntityManagerInterface $entityManager)
    {
        //Si utilisateur connecté souhaite accéder à son profil
        if($request->get('username') === $this->getUser()->getUsername()){
        $form = $this->createForm(UserProfilType::class, $this->getUser());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été modifié avec succès!');
        }
        return $this->render('user/profilConnectedUser.html.twig', ['userProfilForm'=>$form->createView()]);
        }
        //Si l'user souhaite accéder au profil d'un autre utilisateur:

        $userName = $request->get('username');
        $user = new User();
        $user = $entityManager->getRepository('App:User')->findOneBy(['username' => $request->get('username')]);

        return $this->render('user/profil.html.twig',['user'=>$user]);
    }
}