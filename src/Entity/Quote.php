<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $character = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'quotes')]
    private Collection $favoriteOf;

    public function __construct()
    {
        $this->favoriteOf = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCharacter(): ?string
    {
        return $this->character;
    }

    public function setCharacter(string $character): static
    {
        $this->character = $character;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoriteOf(): Collection
    {
        return $this->favoriteOf;
    }

    public function addFavoriteOf(User $favoriteOf): static
    {
        if (!$this->favoriteOf->contains($favoriteOf)) {
            $this->favoriteOf->add($favoriteOf);
        }

        return $this;
    }

    public function removeFavoriteOf(User $favoriteOf): static
    {
        $this->favoriteOf->removeElement($favoriteOf);

        return $this;
    }
}
