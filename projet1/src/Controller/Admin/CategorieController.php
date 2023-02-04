<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieFormType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/admin/categorie', name: 'admin_categorie_')]

class CategorieController extends AbstractController
{
    #[Route('/', name: 'index')]
    
    public function index( CategorieRepository $categorieRepository): Response
    {
        return $this->render('admin/categorie/index.html.twig',[
            'categories'=>$categorieRepository->findBy([]) 
           
          ]);

     
        
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //On crée un "nouveau produit"
        $categorie = new Categorie();

        // On crée le formulaire
        $categorieForm = $this->createForm(CategorieFormType::class, $categorie);

        // On traite la requête du formulaire
        $categorieForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($categorieForm->isSubmitted() && $categorieForm->isValid()){
            // On récupère les images
        
        
            // On génère le slug
            $slug = $slugger->slug($categorie->getName());
            $categorie->setSlug($slug);

            // On arrondit le prix 
            //$prix = $product->getPrice() * 100;
           // $product->setPrice($prix);

            // On stocke
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Categorie ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_categorie_index');
        }


         return $this->render('admin/categorie/add.html.twig',[
           'categorieForm' => $categorieForm->createView()
      ]);

       // return $this->renderForm('admin/product/add.html.twig', compact('productForm'));
        // ['productForm' => $productForm]
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le Voter
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $categorie);

        // On divise le prix par 100
       // $prix = $product->getPrice() / 100;
       // $product->setPrice($prix);

        // On crée le formulaire
        $categorieForm = $this->createForm(CategorieFormType::class, $categorie);

        // On traite la requête du formulaire
        $categorieForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($categorieForm->isSubmitted() && $categorieForm->isValid()){
            // On récupère les images
          

           
            
            
            // On génère le slug
            $slug = $slugger->slug($categorie->getName());
            $categorie->setSlug($slug);

            // On arrondit le prix 
           // $prix = $product->getPrice() * 100;
           // $product->setPrice($prix);

            // On stocke
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Categorie modifié avec succès');

            // On redirige
            return $this->redirectToRoute('admin_categorie_index');
        }


        return $this->render('admin/categorie/edit.html.twig',[
            'categorietForm' => $categorieForm->createView(),
            'categorie' => $categorie
        ]);

        // return $this->renderForm('admin/products/edit.html.twig', compact('productForm'));
        // ['productForm' => $productForm]
    }

    #[Route('/suppression/{id}', name: 'delete', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Categorie $categorie): Response
    {
        
        // On vérifie si l'utilisateur peut supprimer avec le Voter
        $this->denyAccessUnlessGranted('CATEGORY_DELETE', $categorie);

        $manager->remove($categorie);
        $manager->flush();
          
        $this->addFlash('success', ' Votre categorie à été supprimé avec succès');
       
        return $this->redirectToRoute('admin_categorie_index');
    }

   
  

}