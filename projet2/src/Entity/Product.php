<?php

namespace App\Entity;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\CreatedAtTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use symfony\component\Validator\Constraints as  Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    use SlugTrait;
    use CreatedAtTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    //#[Assert\NotBlank(message:'le nom de produit ne peut pas être vide')]
   // #[Assert\Length(
      //  min:3,
       // max:200,
     //   minMessage:'le titre doit faire au moins {{limit}} caractères',
      //  maxMessage:'le titre ne doit pas faire plus de {{limit}} caractères'
   // )]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
   // #[assert\PositiveOrZero(message:'Le stock ne peut pas être négatif')]
    private ?int $stock = null;

  
    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Order::class)]
    private Collection $orders;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }


    public function getCategorie(): ?categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrder(): Collection
    {
        return $this->order;
    }

    public function addOrderDetail(Order $order): self
    {
        if (!$this->order->contains($order)) {
            $this->order->add($order);
            $order->setProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->order->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getProduct() === $this) {
                $order->setProduct(null);
            }
        }

        return $this;
    }
}