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
     * @Route(path="campus", name="campus")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function campus(EntityManagerInterface $entityManager , Request $request): Response
    {

        $campus = new campus();
        //recupere list des campus
        $campusList = $entityManager->getRepository(Campus::class)->findAll();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();
        }
        return $this->render('admin/campus.html.twig',['campusForm' => $form->createView(),'campusList'=> $campusList]);
    }


    // modifier le nom d un campus

    /**
     * @Route(Path="campusmodifier/{id}" , name="campusmodifier")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function modifierCampus(EntityManagerInterface $entityManager , Request $request )
    {
        $campusList = $entityManager->getRepository(Campus::class)->findAll();
      $campus = $entityManager->getRepository(Campus::class)->find($request->get('id'));
      $campusForm = $this->createForm(CampusType::class, $campus);
      $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'le campus a bien été modifié !');

            return $this->redirectToRoute('admin_campus',[
            'campusForm' => $campusForm->createView()
        ,'campusList'=>$campusList]);

        }
        return $this->render("admin/modifiercampus.html.twig",[
            'campusForm' => $campusForm->createView()]
        );

    }


        // supprime un campus
    /**
     * @Route(Path="campusDelete/{id}" , name="campusDelete")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
        public
        function deleteCampus(EntityManagerInterface $em, Request $request, int $id): RedirectResponse
        {

            // $em=$this->getDoctrine()->getManager();
            //$CampusRepository= $this->getDoctrine()->getRepository(CampusType::class);
            //$campus = $CampusRepository->find($id);
            // $campus = new Campus();
            // $campus = $em->getRepository(Campus::class)->find($id);

            //if($campus = null){
            // throw $this->createNotFoundException('Site inconnu ou deja supprimé');
            //}

            $em->remove($campus = $em->getRepository(Campus::class)->find($id));
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
        $villeliste = $entityManager->getRepository(Ville::class)->findAll();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
        }
        return $this->render('admin/city.html.twig');
    }

    /**
     * @Route(Path="modifierville", name="modifierville")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function modifierVille(EntityManagerInterface $em , Request $request, $id): RedirectResponse
    {

        $em->remove($ville= $em->getRepository(ville::class)->find($id));

        if ($ville->isSubmitted() && $ville->isValid()) {
            $em->flush();
            $this->addFlash('success', 'la ville a bien été modifié !');

            return $this->redirectToRoute('admin_villes');
        }

    }


     //Supprimer une ville
    /**
     * @Route(Path="deletecity" , name="deletecity")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteCity(EntityManagerInterface $em , request $request, $id): RedirectResponse
    {

        $em ->remove($ville = $em->getRepository(ville::class)->find($id));
        $em->flush();
        $this->addFlash('success', 'le campus a bien été supprimé !');

        return $this->redirectToRoute('admin_villes');
}

     //Ajouter un utilisateur
    /**
     * @Route(path="userRegister", name="userRegister" )
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
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
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'utilisateur ajouté!');
        }
        return $this->render('admin/userRegister.html.twig', ['userRegisterForm'=>$form->createView()]);
    }

}



