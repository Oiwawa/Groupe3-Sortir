<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserProfilType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * Class UserController
 * @package App\Controller
 * @Route(path="user/", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="{username}", name="profil")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AppAuthenticator $authenticator
     * @return Response
     */
    public function profil(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder,
    GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator): Response
    {
        //Récupère les données de l'user en fonction de l'url
        $user = $entityManager->getRepository('App:User')->findOneBy(['username' => $request->get('username')]);
        //Si utilisateur connecté souhaite accéder à son profil
        if ($request->get('username') === $this->getUser()->getUsername()) {

            $form = $this->createForm(UserProfilType::class, $this->getUser());
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword( $passwordEncoder->encodePassword( $user, $form->get('password')->getData()));
                $entityManager->refresh($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a été modifié avec succès!');

            }
            return $this->render('user/profilConnectedUser.html.twig', ['userProfilForm' => $form->createView()]);
        }
        //Si l'user souhaite accéder au profil d'un autre utilisateur:
        $user = $entityManager->getRepository('App:User')->findOneBy(['username' => $request->get('username')]);
        return $this->render('user/profil.html.twig', ['user' => $user]);
    }

}