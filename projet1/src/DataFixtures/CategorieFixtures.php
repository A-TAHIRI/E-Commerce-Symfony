<?php

namespace App\DataFixtures;

use App\Entity\Categorie;

use App\Entity\Trait\SlugTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
class CategorieFixtures extends Fixture
{
     private $counter=1;
   public function __construct(private SluggerInterface $slugger){
    
   }


    public function load(ObjectManager $manager): void
    {
         $parent = $this->createCategorie('Informatique',null,$manager);
         $this->createCategorie('Ordinateurs portables',$parent,$manager);
         $this->createCategorie('Ecrans',$parent,$manager);
         $this->createCategorie('Souris',$parent,$manager);


         $parent = $this->createCategorie('Mode',null,$manager);
         $this->createCategorie('Homme',$parent,$manager);
         $this->createCategorie('Femme',$parent,$manager);
         $this->createCategorie('Enfant',$parent,$manager);


         $manager->flush();
    }


    public function createCategorie(string $name, Categorie $parent=null,ObjectManager $manager ){
     $categorie = new Categorie();
     $categorie -> setName($name);
     $categorie -> setSlug($this->slugger->slug($categorie->getName())->lower());
     $categorie-> setParent($parent);
     $manager->persist($categorie);
     $this->addReference('cat-'.$this->counter,$categorie);
     $this->counter++;
     return $categorie;





}



}