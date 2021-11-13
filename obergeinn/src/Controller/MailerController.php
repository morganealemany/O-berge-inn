<?php

namespace App\Controller;

use App\Entity\Participation;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MailerController extends AbstractController
{
    /**
     * @Route("/mailer", name="mailer")
     * @IsGranted("ROLE_USER")
     * 
     */
    public function sendEmail(MailerInterface $mailer, UserRepository $userRepository, EventRepository $eventRepository): Response
    {
        if($_POST) {
            // Retrieve of input data from the template
            $sentEventId = filter_input(INPUT_POST, 'eventId', FILTER_VALIDATE_INT);
            $sentEmailArray = filter_input(INPUT_POST, 'mail', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            
            $event = $eventRepository->find($sentEventId);
                        
            foreach ($sentEmailArray as $guestEmail) {

                $userFindByEmail = $userRepository->findByEmail($guestEmail);

                if (empty($userFindByEmail)) {
                    $mailer->send((new TemplatedEmail())
                        ->from(new Address('obergeinn.officiel@gmail.com', 'O\'Berge\'Inn team'))
                        ->to(new Address($guestEmail, 'Invité'))
                        ->subject('Vous êtes invité!')
                        ->htmlTemplate('mailer/invitation_inscription.html.twig')
                        ->context([
                            'event' => $event,
                        ]));

                        // $mailer->send($email2);
                }
                else {
                    dump($userFindByEmail);
                    // 1. Create an entrance in the Participation table
                    $participation = new Participation();
                    $participation->setUser($userFindByEmail);
                    $participation->setEvent($event);
                    $participation->setStatus(0);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($participation);
                    $em->flush();
                
                    // 2. We send an invitation mail with the link towards the event.
                    $mailer->send((new TemplatedEmail())
                        ->from(new Address('obergeinn.officiel@gmail.com', 'O\'Berge\'Inn team'))
                        ->to(new Address($guestEmail, $userFindByEmail->getFirstname()))
                        ->subject('Vous êtes invité!')
                        ->htmlTemplate('mailer/invitation.html.twig')
                        ->context([
                            'event' => $event,
                    ]));

                    // $mailer->send($email1);
                }
            }
          





            // foreach ($sentEmailArray as $guestEmail) {
            //     // If the inform adress email is the same as a current adress email present in the DB
            //     foreach ($allUserList as $user) {
            //         if ($guestEmail === $user->getEmail()) {
            //             dump($guestEmail, 'user exist');
            //             // 1. Create an entrance in the Participation table
            //             $participation = new Participation();
            //             $participation->setUser($user);
            //             $participation->setEvent($event);
            //             $participation->setStatus(0);
            //             $em = $this->getDoctrine()->getManager();
            //             $em->persist($participation);
            //             $em->flush();
                        
            //             // 2. We send an invitation mail with the link towards the event.
            //             $email1 = (new TemplatedEmail())
            //             ->from(new Address('obergeinn.officiel@gmail.com', 'O\'Berge\'Inn team'))
            //             ->to(new Address($guestEmail, $user->getFirstname()))
            //             ->subject('Vous êtes invité!')
            //             ->htmlTemplate('mailer/invitation.html.twig')
            //             ->context([
            //                 'event' => $event,
            //             ]);

            //             $mailer->send($email1);

            //         } else {
            //             dump($guestEmail, 'user doesn\'t exist');
            //             // Otherwise, it is because the email isn't suscribed on the website.
            //             // Send a mail proposing to register
            //             $email2 = (new TemplatedEmail())
            //             ->from(new Address('obergeinn.officiel@gmail.com', 'O\'Berge\'Inn team'))
            //             ->to(new Address($guestEmail, 'Invité'))
            //             ->subject('Vous êtes invité!')
            //             ->htmlTemplate('mailer/invitation_inscription.html.twig')
            //             ->context([
            //                 'event' => $event,
            //             ]);

            //             $mailer->send($email2);
            //         }
            //     }
            //     // if (isset($email1)) {
            //     //     $mailer->send($email1);
            //     // }
            //     // elseif(isset($email2)) {
            //     //     $mailer->send($email2);
            //     // };
                
            // }
            // Add a flash message to inform the user of the successing creation
            $this->addFlash('success', 'Vos invitations ont bien été envoyées.' );
            
        }    
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }
}
