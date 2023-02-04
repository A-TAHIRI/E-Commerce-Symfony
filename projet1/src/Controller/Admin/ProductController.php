<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Categorie;
use App\Repository\ProductRepository;
use App\Form\ProductFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/admin/produit', name: 'admin_product_')]

class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    
    public function index( ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig',[
            'products'=>$productRepository->findBy([]) 
           
          ]);

     
        
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //On crée un "nouveau produit"
        $product = new Product();

        // On crée le formulaire
        $productForm = $this->createForm(ProductFormType::class, $product);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($productForm->isSubmitted() && $productForm->isValid()){
            // On récupère les images
            $images = $productForm->get('images')->getData();
            
            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'products';

                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $product->addImage($img);
            }

            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On arrondit le prix 
            //$prix = $product->getPrice() * 100;
           // $product->setPrice($prix);

            // On stocke
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_product_index');
        }


         return $this->render('admin/product/add.html.twig',[
           'productForm' => $productForm->createView()
      ]);

       // return $this->renderForm('admin/product/add.html.twig', compact('productForm'));
        // ['productForm' => $productForm]
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Product $product, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le Voter
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

        // On divise le prix par 100
       // $prix = $product->getPrice() / 100;
       // $product->setPrice($prix);

        // On crée le formulaire
        $productForm = $this->createForm(ProductFormType::class, $product);

        // On traite la requête du formulaire
        $productForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($productForm->isSubmitted() && $productForm->isValid()){
            // On récupère les images
            $images = $productForm->get('images')->getData();

            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'products';

                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Image();
                $img->setName($fichier);
                $product->addImage($img);
            }
            
            
            // On génère le slug
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            // On arrondit le prix 
           // $prix = $product->getPrice() * 100;
           // $product->setPrice($prix);

            // On stocke
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit modifié avec succès');

            // On redirige
            return $this->redirectToRoute('admin_product_index');
        }


        return $this->render('admin/product/edit.html.twig',[
            'productForm' => $productForm->createView(),
            'product' => $product
        ]);

        // return $this->renderForm('admin/products/edit.html.twig', compact('productForm'));
        // ['productForm' => $productForm]
    }

    #[Route('/suppression/{id}', name: 'delete', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Product $product): Response
    {
        
        // On vérifie si l'utilisateur peut supprimer avec le Voter
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product,);

        $manager->remove($product);
        $manager->flush();
          
        $this->addFlash('success', ' Votre Produit à été supprimé avec succès');
       
        return $this->redirectToRoute('admin_product_index');
    }

    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(Image $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'products', 300, 300)){
                // On supprime l'image de la base de données
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

}