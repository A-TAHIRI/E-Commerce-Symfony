<?php

namespace App\Twig;


use App\Entity\Newsletters\Users;
use App\Form\NewslettersUsersFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\Routing\Annotation\Route;

class CatsExtension extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
  
    
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cats', [$this, 'getForm'],['is_safe'=>['html']])
        ];
    }



    public function getForm(Request $request, MailerInterface $mailer,$form)
    {
        $user = new Users();
    $form = $this->createForm(NewslettersUsersFormType::class, $user);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $token = hash('sha256', uniqid());

        $user->setValidationToken($token);

        
       $this-> em->persist($user);
        $this->em->flush();

        $email = (new TemplatedEmail())
            ->from('newsletter@site.fr')
            ->to($user->getEmail())
            ->subject('Votre inscription à la newsletter')
            ->htmlTemplate('emails/inscription.html.twig')
            ->context(compact('user', 'token'))
            ;

       $mailer->send($email);


 
    }

    
    return $this-> $form->createView();
     
    }
}