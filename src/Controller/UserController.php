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

        $form = $this->createForm(UserProfilType::class, $this->getUser());


        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $entityManager->persist();
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été modifié avec succès!');
        }
        return $this->render('user/profil.html.twig', ['userProfilForm'=>$form->createView()]);
    }
}