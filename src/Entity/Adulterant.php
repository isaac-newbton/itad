<?php

namespace App\Entity;

use App\Repository\AdulterantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private $occurrenceUsage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $physiologicalEffect;

    /**
     * @ORM\OneToOne(targetEntity=MediaFile::class, inversedBy="adulterant", cascade={"persist", "remove"})
     */
    private $thumbnail;

    /**
     * @ORM\OneToMany(targetEntity=ReportLineItem::class, mappedBy="adulterant", orphanRemoval=true)
     */
    private $reportLineItems;

    public function __construct(){
        $this->uuid = Uuid::uuid4();
        $this->reportLineItems = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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

    public function getOccurrenceUsage(): ?string
    {
        return $this->occurrenceUsage;
    }

    public function setOccurrenceUsage(?string $occurrenceUsage): self
    {
        $this->occurrenceUsage = $occurrenceUsage;

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

    /**
     * @return Collection|ReportLineItem[]
     */
    public function getReportLineItems(): Collection
    {
        return $this->reportLineItems;
    }

    public function addReportLineItem(ReportLineItem $reportLineItem): self
    {
        if (!$this->reportLineItems->contains($reportLineItem)) {
            $this->reportLineItems[] = $reportLineItem;
            $reportLineItem->setAdulterant($this);
        }

        return $this;
    }

    public function removeReportLineItem(ReportLineItem $reportLineItem): self
    {
        if ($this->reportLineItems->contains($reportLineItem)) {
            $this->reportLineItems->removeElement($reportLineItem);
            // set the owning side to null (unless already changed)
            if ($reportLineItem->getAdulterant() === $this) {
                $reportLineItem->setAdulterant(null);
            }
        }

        return $this;
    }
}
