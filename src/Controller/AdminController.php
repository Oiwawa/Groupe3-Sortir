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
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    //créer un campus
    /**
     * @Route(path="campus", name="campus", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function campus(EntityManagerInterface $entityManager , Request $request): Response
    {

        $campus = new campus();
        //recupere list des campus
        $campuslist = $entityManager->getRepository(Campus::class)->findAll();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();
        }

        return $this->render('admin/campus.html.twig',['campusForm' => $form->createView(),'campuslist'=> $campuslist]);
    }



    // supprime un campus
    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @Route(Path="campusDelete/{id}" , name="campusDelete")
     */
    public function deletecampus(EntityManagerInterface $em ,Request $request, $id): RedirectResponse
    {

       // $em=$this->getDoctrine()->getManager();
        //$CampusRepository= $this->getDoctrine()->getRepository(CampusType::class);
       //$campus = $CampusRepository->find($id);
       // $campus = new Campus();
       // $campus = $em->getRepository(Campus::class)->find($id);

        //if($campus = null){
           // throw $this->createNotFoundException('Site inconnu ou deja supprimé');
        //}

        $em ->remove($campus = $em->getRepository(Campus::class)->find($id));
        $em->flush();
        $this->addFlash('success', 'le campus a bien été supprimé !');

        return $this->redirectToRoute('admin_campus');
    }

    // Ajouter une ville

    /**
     * @Route(path="villes", name="villes", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function city(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ville = new ville();

        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
        }
        return $this->render('admin/city.html.twig');
    }

     //Ajouter un utilisateur
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
        return $this->render('admin/userRegister.html.twig', ['userRegisterForm'=>$form->createView()]);
    }




}



