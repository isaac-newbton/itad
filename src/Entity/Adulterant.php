<?php

namespace App\Entity;

use App\Repository\AdulterantRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=AdulterantRepository::class)
 */
class Adulterant
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $synonyms;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $spanishName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $drugClass;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $occuranceUsage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $physiologicalEffect;

    /**
     * @ORM\OneToOne(targetEntity=MediaFile::class, inversedBy="adulterant", cascade={"persist", "remove"})
     */
    private $thumbnail;

    public function __construct(){
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

    public function getSynonyms(): ?string
    {
        return $this->synonyms;
    }

    public function setSynonyms(?string $synonyms): self
    {
        $this->synonyms = $synonyms;

        return $this;
    }

    public function getSpanishName(): ?string
    {
        return $this->spanishName;
    }

    public function setSpanishName(?string $spanishName): self
    {
        $this->spanishName = $spanishName;

        return $this;
    }

    public function getDrugClass(): ?string
    {
        return $this->drugClass;
    }

    public function setDrugClass(?string $drugClass): self
    {
        $this->drugClass = $drugClass;

        return $this;
    }

    public function getOccuranceUsage(): ?string
    {
        return $this->occuranceUsage;
    }

    public function setOccuranceUsage(?string $occuranceUsage): self
    {
        $this->occuranceUsage = $occuranceUsage;

        return $this;
    }

    public function getPhysiologicalEffect(): ?string
    {
        return $this->physiologicalEffect;
    }

    public function setPhysiologicalEffect(?string $physiologicalEffect): self
    {
        $this->physiologicalEffect = $physiologicalEffect;

        return $this;
    }

    public function getThumbnail(): ?MediaFile
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?MediaFile $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
