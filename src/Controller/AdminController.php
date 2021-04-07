<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserProfilType;
use App\Form\UserRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route(path="userRegister", name="userRegister" )
     */
    public function userRegister(EntityManagerInterface $entityManager, Request $request, UserPasswordEncoderInterface $passwordEncoder){
       $user = new User();

       $form = $this->createForm(UserRegisterType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($user->getAdmin()== true){
                $user->setRoles((array)'ROLE_ADMIN');
            }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'utilisateur ajouté!');
        }
        return $this->render('admin/userregister.html.twig', ['userRegisterForm'=>$form->createView()]);
    }


}