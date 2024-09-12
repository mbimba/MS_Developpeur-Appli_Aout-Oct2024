<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
//use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
// ..


class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(): Response
    {
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }

    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
        // ...
        ->addPart((new DataPart(fopen('/path/to/images/logo.png', 'r'), 'logo', 'image/png'))->asInline())
        ->addPart((new DataPart(new File('/path/to/images/signature.gif'), 'footer-signature', 'image/gif'))->asInline())
    
        // utiliser la syntaxe 'cid:' + "nom de l'image intégrée " pour référencer l'image
        ->html('<img src="cid:logo"> ... <img src="cid:footer-signature"> ...')
    
        // utiliser la même syntaxe pour les images intégrées en tant que background
        ->html('... <div background="cid:footer-signature"> ... </div> ...')
 
        ->from('hello@example.com')
//            ->to('you@example.com')
        ->to(new Address('ryan@example.com'))
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Time for Symfony Mailer!')

        // le chemin de la vue Twig à utiliser dans le mail
        ->htmlTemplate('emails/signup.html.twig')

        // un tableau de variable à passer à la vue; 
       //  on choisit le nom d'une variable pour la vue et on lui attribue une valeur (comme dans la fonction `render`) :
        ->context([
                'expiration_date' => new \DateTime('+7 days'),
                'username' => 'foo',
            ]);

        $mailer->send($email);

        // ...
    }


}
