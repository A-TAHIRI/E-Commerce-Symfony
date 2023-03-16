<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use PhpParser\Node\Stmt\Switch_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $productRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
           
            $total += $product->getPrice()* $quantite/100;
        }

        return $this->render('cart/index.html.twig', compact("dataPanier", "total"));
       
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();
      

        if(!empty($panier[$id])){
            $panier[$id]++;
          
       
        }else{
            $panier[$id] =1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);
     
        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("app_main");
    }


    
    #[Route('/checkout', name: 'checkout')]
    public function checkout(SessionInterface $session ,$stripeSK ): Response

    {
        
           
        Stripe::setApiKey($stripeSK);
      
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => [
                            'name' =>"{{ element.produit.name }}",
                        ],
                        'unit_amount'  => '2000'
                    ],
                    'quantity'   => '1',
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('cart_success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('cart_cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    

        return $this->redirect($session->url, 303);
    }



    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('cart/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('cart/cancel.html.twig', []);
    }

}