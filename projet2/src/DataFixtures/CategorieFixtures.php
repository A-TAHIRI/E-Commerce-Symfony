<?php

namespace App\DataFixtures;

use App\Entity\Categorie;

use App\Entity\Traits\SlugTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
class CategorieFixtures extends Fixture
{
     private $counter=1;
     private $i=1;
   public function __construct(private SluggerInterface $slugger){
    
   }


    public function load(ObjectManager $manager): void
    {
         $parent = $this->createCategorie('Bijoux',null,$manager);
         $this->createCategorie('Collier ',$parent,$manager);
         $this->createCategorie('B.d\'oreille',$parent,$manager);
         $this->createCategorie('Bracelet',$parent,$manager);
         $this->createCategorie('Bague',$parent,$manager);
         $this->createCategorie('B.cheville',$parent,$manager);



         $parent = $this->createCategorie('Accessoires',null,$manager);
         $this->createCategorie('Montres',$parent,$manager);
        


         $manager->flush();
    }


    public function createCategorie(string $name, Categorie $parent=null,ObjectManager $manager ){
     $categorie = new Categorie();
     $categorie -> setName($name);
     $categorie -> setSlug($this->slugger->slug($categorie->getName())->lower());
     $categorie->setcategorieOrder($this->i++);
     $categorie-> setParent($parent);
     $manager->persist($categorie);
     $this->addReference('cat-'.$this->counter,$categorie);
     $this->counter++;
     return $categorie;





}



}