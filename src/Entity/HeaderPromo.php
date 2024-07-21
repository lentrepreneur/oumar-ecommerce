<?php

namespace App\Entity;

use App\Repository\HeaderPromoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeaderPromoRepository::class)]
class HeaderPromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    private ?string $leftImage = null;

    #[ORM\Column(length: 255)]
    private ?string $rightImage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getLeftImage(): ?string
    {
        return $this->leftImage;
    }

    public function setLeftImage(string $leftImage): static
    {
        $this->leftImage = $leftImage;

        return $this;
    }

    public function getRightImage(): ?string
    {
        return $this->rightImage;
    }

    public function setRightImage(string $rightImage): static
    {
        $this->rightImage = $rightImage;

        return $this;
    }
}
