<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sentLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $liker = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'receivedLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $liked = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'is_like', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isLike = true;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getLiker(): ?User
    {
        return $this->liker;
    }

    public function setLiker(?User $liker): static
    {
        if ($this->liker !== null && $this->liker !== $liker) {
            $this->liker->getSentLikes()->removeElement($this);
        }
        
        $this->liker = $liker;
        
        if ($liker !== null && !$liker->getSentLikes()->contains($this)) {
            $liker->getSentLikes()->add($this);
        }
        
        return $this;
    }

    public function getLiked(): ?User
    {
        return $this->liked;
    }

    public function setLiked(?User $liked): static
    {
        if ($this->liked !== null && $this->liked !== $liked) {
            $this->liked->getReceivedLikes()->removeElement($this);
        }
        
        $this->liked = $liked;
        
        if ($liked !== null && !$liked->getReceivedLikes()->contains($this)) {
            $liked->getReceivedLikes()->add($this);
        }
        
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function isLike(): bool
    {
        return $this->isLike;
    }

    public function setIsLike(bool $isLike): static
    {
        $this->isLike = $isLike;
        return $this;
    }
}