<?php


namespace App\Controller;


use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\UserProfilType;
use App\Form\UserRegisterType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function campus(EntityManagerInterface $entityManager , Request $request){
        $campus = new campus();

        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();

        }

        return $this->render('admin/campus.html.twig',[]);
    }

    /**
     * @Route(path="villes", name="villes", methods={"GET"})
     */

    public function city(EntityManagerInterface $entityManager, Request $request){
        $ville = new ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

        }

        return $this->render('admin/city.html.twig');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route(path="userRegister", name="userRegister" )
     */
    public function userRegister(EntityManagerInterface $entityManager, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
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



