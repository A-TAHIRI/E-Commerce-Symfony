<?php

namespace App\Entity\Newsletters;

use App\Repository\Newsletters\NewslettersRepository;
use DateTime;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


 #[ORM\Entity(repositoryClass:NewslettersRepository::class)]

class Newsletters
{
    
    #[ORM\Id]
    #[ ORM\GeneratedValue]
    #[ORM\Column] 
    
    private ?int $id = null;

    
    #[ORM\Column(length :255)] 
     
    private ?string $name;

    
    #[ORM\Column(type: Types::TEXT)] 
    
    private ?string $content;

    
     #[ORM\Column]
     
    private ?DateTime $created_at;

    
    #[ORM\Column]
    
    private ?bool $is_sent = false;

   

    public function __construct()
    {
        $this->created_at = new DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getIsSent(): ?bool
    {
        return $this->is_sent;
    }

    public function setIsSent(bool $is_sent): self
    {
        $this->is_sent = $is_sent;

        return $this;
    }

  
}