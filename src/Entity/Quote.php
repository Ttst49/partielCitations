<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("api")]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("api")]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("api")]
    private ?string $character = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'quotes')]
    private Collection $favoriteOf;

    #[ORM\Column]
    private ?int $counter = 0;


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

    public function getCounter(): ?int
    {
        return $this->counter;
    }

    public function setCounter(int $counter): static
    {
        $this->counter = $counter;

        return $this;
    }

}
