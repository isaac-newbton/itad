<?php

namespace App\Entity;

use App\Repository\AdulterantCustomFieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AdulterantCustomFieldRepository::class)
 */
class AdulterantCustomField
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Adulterant::class, inversedBy="customFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adulterant;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
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

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAdulterant(): ?Adulterant
    {
        return $this->adulterant;
    }

    public function setAdulterant(?Adulterant $adulterant): self
    {
        $this->adulterant = $adulterant;

        return $this;
    }
}
