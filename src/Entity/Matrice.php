<?php

namespace App\Entity;

use App\Repository\MatriceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatriceRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Matrice
{
    const MAX_INCREMENT = 20;

    const EMPTY_BLOCK = 999;
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

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="matrices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isTraining;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shuffledCount;

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

    public function getNotEmptyBlocks()
    {
        $blocks = [];
        foreach ($this->blocks as $block) {
            if ($block->getNumber() !== self::EMPTY_BLOCK) {
                $blocks[] = $block;
            }
        }
        return $blocks;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
    }

    public function getIsTraining(): ?bool
    {
        return $this->isTraining;
    }

    public function setIsTraining(bool $isTraining): self
    {
        $this->isTraining = $isTraining;

        return $this;
    }

    public function getShuffledCount(): ?int
    {
        return $this->shuffledCount;
    }

    public function setShuffledCount(?int $shuffledCount): self
    {
        $this->shuffledCount = $shuffledCount;

        return $this;
    }

    public function useOneShuffle(): void
    {
        $this->shuffledCount = $this->shuffledCount -1;
    }
}
