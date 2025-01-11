<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: "bigint")]
    private ?string $category_id = null;

    #[ORM\Column(type: "integer")]
    private ?int $pages = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $publication_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\OneToMany(mappedBy: "book", targetEntity: BookRead::class, cascade: ["persist", "remove"])]
    private Collection $bookReads;

    public function __construct()
    {
        $this->bookReads = new ArrayCollection();
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

    public function getCategoryId(): ?string
    {
        return $this->category_id;
    }

    public function setCategoryId(string $category_id): self
    {
        $this->category_id = $category_id;
        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;
        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function setPublicationDate(\DateTimeInterface $publication_date): self
    {
        $this->publication_date = $publication_date;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    //essai de code non abouti
    /**
     * @return Collection<int, BookRead>
     */
    public function getBookReads(): Collection
    {
        return $this->bookReads;
    }

    public function addBookRead(BookRead $bookRead): self
    {
        if (!$this->bookReads->contains($bookRead)) {
            $this->bookReads->add($bookRead);
            $bookRead->setBook($this);
        }

        return $this;
    }

    public function removeBookRead(BookRead $bookRead): self
    {
        if ($this->bookReads->removeElement($bookRead)) {
        
            if ($bookRead->getBook() === $this) {
                $bookRead->setBook(null);
            }
        }

        return $this;
    }
}
