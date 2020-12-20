<?php

namespace App\Entity;

use App\Repository\MatriceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatriceRepository::class)
 */
class Matrice
{
    const MAX_INCREMENT = 20;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Block::class, mappedBy="matrice")
     */
    private $blocks;

    /**
     * @ORM\Column(type="integer")
     */
    private $multiple;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $incrementNewBlock;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Block[]
     */
    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function addBlock(Block $block): self
    {
        if (!$this->blocks->contains($block)) {
            $this->blocks[] = $block;
            $block->setMatrice($this);
        }

        return $this;
    }

    public function removeBlock(Block $block): self
    {
        if ($this->blocks->removeElement($block)) {
            // set the owning side to null (unless already changed)
            if ($block->getMatrice() === $this) {
                $block->setMatrice(null);
            }
        }

        return $this;
    }

    public function getMultiple(): ?int
    {
        return $this->multiple;
    }

    public function setMultiple(int $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function getIncrementNewBlock(): ?int
    {
        return $this->incrementNewBlock;
    }

    public function setIncrementNewBlock(?int $incrementNewBlock): self
    {
        $this->incrementNewBlock = $incrementNewBlock;

        return $this;
    }
}
