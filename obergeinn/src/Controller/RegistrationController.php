<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Intervention\Image\ImageManager;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        // Instanciate an empty object
        $user = new User();
        // Instanciate the formtype and linked it to the empty object.
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        // Recovery of form datas and insert it to the user object.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
                        ->fit(50,50)
                        ->save('uploads/'.$newFilename);
                    
                    $user->setImage($newFilename);
                } catch (FileException $e) {
                    // TODO Here wondering if we must send an email to admin? 
                }
                
            }

                // encode the plain password
                $user->setPassword(
                    $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
                );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                    ->from(new Address('obergeinn.officiel@gmail.com', 'obergeinn'))
                    ->to($user->getEmail())
                    ->subject('Confirmation d\'adresse mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $this->addFlash('success', 'Un email avec un lien d\'activation de votre compte vient de vous être envoyé.');

                return $this->redirectToRoute('app_login');
        }


            return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_login');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        // $this->addFlash('success', 'Votre compte a bien été créé. Vous pouvez à présent vous connecter.');

        return $this->redirectToRoute('app_login');
    }
}
