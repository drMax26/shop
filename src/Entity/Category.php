<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    use ORMBehaviors\Translatable\Translatable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $defaultname;

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren(array $children = []): void
    {
        $this->children = $children;
        $this->setEnableChildren((bool) count($children));
    }

    private $children;

    private $enableChildren;
	
	private $fullParent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Products", mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getEnableChildren()
    {
        return $this->enableChildren;
    }

    /**
     * @param mixed $enableChildren
     */
    public function setEnableChildren(bool $enableChildren = false): void
    {
        $this->enableChildren = $enableChildren;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
	
	public function getFullParent()
    {
        return $this->fullParent;
    }

    public function setFullParent(Category $fullParent): self
    {
        $this->fullParent = $fullParent;

        return $this;
    }

    public function getDefaultname(): ?string
    {
        return $this->defaultname;
    }

    public function setDefaultname(string $defaultname): self
    {
        $this->defaultname = $defaultname;

        return $this;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}