<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Intervention\Image\ImageManager;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/profil", name="user_")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/editer/{id}", name="edit")
     */
        public function update(int $id): Response
        {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->find($id);
    
            if (!$user) {
                throw $this->createNotFoundException(
                    'No user found for id '.$id
                );
            }
    
            $user->setName('New user name!');
            $entityManager->flush();
            //dashboard or edit profile for the redirection
            return $this->redirectToRoute('dashboard', [
                'id' => $user->getId()
            ]);
        }

    /**
     * @Route("/supprimer/{id}", name="delete")
     */
   public function delete(int $id, HttpFoundationRequest $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

       //

       $this->container->get('security.token_storage')->setToken(null);


        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', "Compte supprimé avec succès, à bientot chez O'Berg'Inn");


        //$this->redirectToRoute('app_logout');

        //$request->getSession()->invalidate();

        //$this->tokenStorage->setToken(); // TokenStorageInterface 
        
        return $this->redirectToRoute('home');
    }
    
     
     /**
     * @Route("/", name="index")
     */
    public function index() 
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            ]);
    }

        /**
     * @Route("/modifier", name="update")
     */
    public function edit(HttpFoundationRequest $request, SluggerInterface $slugger)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {


             // Recovering of the binary file
            /** @var UploadedFile $imgFile */
            $imgFile = $form->get('image')->getData();

            // Checking il the file is informed or not in the form
            if ($imgFile) {
                // Recovering of the file name
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);

                // For security reasons (sql injections...) we will clean the file name with the sluggerInterface service
                $safeFilename = $slugger->slug($originalFilename);

                // To avoid conflicts betwenn two file with the same name we will renamed our files with a suffix (uniqid)
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile->guessExtension();
                
                try {
                    // We use ImageManager service (Intervention image) to resize and crop the image to use it as a thumbnail and save the image into uploads directory
                    $manager = new ImageManager();
                    $manager->make($imgFile)
                        ->fit(50, 50)
                        ->save('uploads/'.$newFilename);
                    
                    $user->setImage($newFilename);
                } catch (FileException $e) {
                    // TODO Here wondering if we must send an email to admin?
                }
            }
            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
                
            $this->addFlash('success', 'Profil mis à jour');
            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/edit-profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
      /**
     * @Route("/modifier/mot-de-passe", name="password")
     */
    public function editPass(HttpFoundationRequest $request, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        if ($request->isMethod('POST')){

            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            //we verify if the 2 password are identical :
                if ($request->request->get('pass') == $request->request->get('pass2')) {
                    $user->setPassword($userPasswordHasherInterface->hashPassword($user,$request->request->get('pass') ));
                    $em->flush();
                    $this->addFlash('success', 'Mot de passe mis à jour avec succès');
                    return $this->redirectToRoute('user_index');
                }
                else {
                    $this->addFlash('warning', 'Les deux mots de passes ne sont pas identiques');
                }
        }

        return $this->render('user/edit-profil-password.html.twig');
    }

}
// we created the update and deleted function based on this link "updating or deleting an object" : https://symfony.com/doc/current/doctrine.html
// In order to check if the update and delete method function, we need a Back Office page or an edit profile page.