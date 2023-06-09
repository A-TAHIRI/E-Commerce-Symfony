<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProductRepository;
use App\Form\SearchProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index( ProductRepository $productRepository,    CategorieRepository $categorieRepository, Request  $request): Response
    {
      $categories=$categorieRepository->findBy([],['categorieOrder'=>'asc']) ;
      $products=$productRepository->findBy([],['created_at'=>'desc']) ;
    

      $form = $this->createForm(SearchProductFormType::class);
        
      $search = $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
          // On recherche les annonces correspondant aux mots clés
          $products = $productRepository->search(
            $search->get('mots')->getData(),
              // $search->get('categories')->getData(),
           
          );
      }



        return $this->render('main/index.html.twig',[
          'categories'=>$categories ,
          'products'=>$products ,
          'form'=>$form->createView()
        ]

    );
    }
}