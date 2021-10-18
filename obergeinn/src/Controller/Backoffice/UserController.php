<?php

namespace App\Controller\Backoffice;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* @Route("/backoffice/utilisateur", name="backoffice_user_", requirements={"id": "\d+"})
* @IsGranted("ROLE_ADMIN")
*/
class UserController extends AbstractController
{
    /**
     * 
     * URL: /backoffice/utilisateur/
     * Route : backoffice_user_index
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/", name="index")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backoffice/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * Page which enable to see the detailed information of an existing user.
     * 
     * URL : /backoffice/utilisateur/editer/{id}
     * Route : backoffice_user_edit
     * 
     * @Route("/editer/{id}", name="edit")
     *
     * @return Response
     */
    // Use of param converter in order to display an error 404 if the user id doesn't exist
    public function edit(User $user, Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        // We check that the datas are valid before saving them.
        if ($form->isSubmitted() && $form->isValid()) {
            // A la création d'un utilisateur
            // on va hasher le mot de passe saisi en clair
            // dans le formulaire
            if ($form->has('plainPassword')) {
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backoffice_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('backoffice/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

     /**
     * Page which allow the removal of an account.
     * 
     * URL : /backoffice/utilisateur/supprimer/{id}
     * Route : backoffice_user_delete
     * 
     * @Route("/supprimer/{id}", name="delete")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backoffice_user_index', [], Response::HTTP_SEE_OTHER);
    }
}

