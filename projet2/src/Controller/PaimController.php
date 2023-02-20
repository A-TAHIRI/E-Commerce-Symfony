<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaimController extends AbstractController
{
    

     #[Route('/pay', name:'pay')]
    public function pay(SessionInterface $session): Response

    
    {

        $panier = $session->get("panier", []);
  

        if(isset($_POST['total']) && !empty($_POST['total'])){
            require_once('vendor/autoload.php');
            $total = (float)$_POST['total'];
             // On instancie Stripe
                \Stripe\Stripe::setApiKey('STRIPE_SECRET_KEY_TEST');

                $intent = \Stripe\PaymentIntent::create([
                    'amount' => $total*100,
                    'currency' => 'eur'
                        
        ]);
        return $this->render('pay/pay.html.twig', [$intent => 'intent'
            
        ]);
            

        }else{
            
                return $this->redirectToRoute('app_login');
            

        }
    }
}
  