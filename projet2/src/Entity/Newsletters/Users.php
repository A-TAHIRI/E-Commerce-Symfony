<?php

namespace App\Entity\Newsletters;

use App\Repository\Newsletters\UsersRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


 #[ORM\Entity(repositoryClass:UsersRepository::class)]
 
class Users
{
    
    #[ORM\Id] 
    #[ORM\GeneratedValue] 
     #[ORM\Column]                    
    
    private ?int $id=null;


    #[ORM\Column( length:255, nullable:true)]
     
    private ?string $email;

    
    #[ORM\Column] 
     
    private ?DateTime $created_at;

   
    
     #[ORM\Column( length:255, nullable:true)]
    
    private ?string $validation_token;

    
     #[ORM\Column]
    
    private ?bool $is_valid = false;
       
    
     
    #[ORM\ManyToMany(targetEntity:Categories::class, inversedBy:"users")]
    
    private $categories;

    public function __construct()
    {
        $this->created_at = new DateTime();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    

    public function getValidationToken(): ?string
    {
        return $this->validation_token;
    }

    public function setValidationToken(string $validation_token): self
    {
        $this->validation_token = $validation_token;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->is_valid;
    }

    public function setIsValid(bool $is_valid): self
    {
        $this->is_valid = $is_valid;

        return $this;
    }

  
    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            // $category->addUser($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        $this->categories->removeElement($category);
        return $this;
    }
    
}