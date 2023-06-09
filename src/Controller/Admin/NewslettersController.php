<?php

namespace App\Controller\Admin;

use App\Entity\Newsletters\Newsletters;
use App\Entity\Newsletters\Users;
use App\Form\NewslettersFormType;
use App\Form\NewslettersUsersFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\Newsletters\NewslettersRepository;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/newsletters', name: 'admin_newsletters_')]
class NewslettersController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(NewslettersRepository $newsletters): Response
    {
        return $this->render('admin/newsletters/list.html.twig', [
            'newsletters' => $newsletters->findAll()
        ]);
    }



    /**
     * @Route("/confirm/{id}/{token}", name="confirm")
     */
    // public function confirm(Users $user,EntityManagerInterface $em, $token): Response
    // {
    //     if($user->getValidationToken() != $token){
    //         throw $this->createNotFoundException('Page non trouvée');
    //     }

    //     $user->setIsValid(true);

        
    //     $em->persist($user);
    //     $em->flush();

        
    //     $this->addFlash('success', 'Compte activé');

    //     return $this->redirectToRoute('app_main');
    // }

    /**
     * @Route("/prepare", name="prepare")
     */
    public function prepare(Request $request ,EntityManagerInterface $em): Response
    {
        $newsletter = new Newsletters();
        $form = $this->createForm(NewslettersFormType::class, $newsletter);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           
            $em->persist($newsletter);
            $em->flush();

            return $this->redirectToRoute('admin_newsletters_list');
        }

        return $this->render('admin/newsletters/prepare.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list", name="list")
     */
  
    /**
     * @Route("/send/{id}", name="send")
     */
    public function send(Newsletters $newsletter, MailerInterface $mailer ): Response
    {
        $users = $newsletter->getCategories()->getUsers();
        foreach($users as $user){
            if($user->getIsValid()){
                $email =(New  TemplatedEmail())
                ->from('newsletters@site.fr')
                ->to ($user->getEmail())
                ->subject($newsletter->getName())
                ->htmlTemplate('emails/newsletter.html.twig')
                ->context(compact('newsletter' ,'user'));
                $mailer->send($email);
            }
        }

        // $newsletter->setIsSent(true);

        // $em = $this->getDoctrine()->getManager();
        // $em->persist($newsletter);
        // $em->flush();

        return $this->redirectToRoute('admin_newsletters_list');
    }

    /**
     * @Route("/unsubscribe/{id}/{newsletter}/{token}", name="unsubscribe")
     */
    // public function unsubscribe(Users $user, Newsletters $newsletter,EntityManagerInterface $em, $token): Response
    // {
    //     if($user->getValidationToken() != $token){
    //         throw $this->createNotFoundException('Page non trouvée');
    //     }

        

    //     if(count($user->getCategories()) > 1){
    //         $user->removeCategory($newsletter->getCategories());
    //         $em->persist($user);
    //     }else{
    //         $em->remove($user);
    //     }
    //     $em->flush();

    //     $this->addFlash('message', 'Newsletter supprimée');

    //     return $this->redirectToRoute('app_main');
    // }
}