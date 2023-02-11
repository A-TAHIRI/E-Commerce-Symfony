<?php

namespace App\Controller;


use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\SearchProductFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ProductRepository $productRepository , Request $request): Response

  
    {

      $products=$productRepository->findBy([],['created_at'=>'desc']) ;
       
      $form = $this->createForm(SearchProductFormType::class);
        
      $search = $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
          // On recherche les annonces correspondant aux mots clés
          $products = $productRepository->search(
              $search->get('mots')->getData(),
           
          );
      }


        return $this->render('main/index.html.twig',[
          'products' => $products,
          'form' => $form->createView()
          
        ]

    );
    }
    
}