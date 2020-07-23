<?php

namespace App\Entity;

use App\Repository\YearlyReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=YearlyReportRepository::class)
 */
class YearlyReport
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="yearlyReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=ReportLineItem::class, mappedBy="report", orphanRemoval=true)
     */
    private $reportLineItems;

    /**
     * @ORM\ManyToMany(targetEntity=Laboratory::class, inversedBy="yearlyReports")
     */
    private $participatingLaboratories;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->reportLineItems = new ArrayCollection();
        $this->participatingLaboratories = new ArrayCollection();
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

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
            $reportLineItem->setReport($this);
        }

        return $this;
    }

    public function removeReportLineItem(ReportLineItem $reportLineItem): self
    {
        if ($this->reportLineItems->contains($reportLineItem)) {
            $this->reportLineItems->removeElement($reportLineItem);
            // set the owning side to null (unless already changed)
            if ($reportLineItem->getReport() === $this) {
                $reportLineItem->setReport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Laboratory[]
     */
    public function getParticipatingLaboratories(): Collection
    {
        return $this->participatingLaboratories;
    }

    public function addParticipatingLaboratory(Laboratory $participatingLaboratory): self
    {
        if (!$this->participatingLaboratories->contains($participatingLaboratory)) {
            $this->participatingLaboratories[] = $participatingLaboratory;
        }

        return $this;
    }

    public function removeParticipatingLaboratory(Laboratory $participatingLaboratory): self
    {
        if ($this->participatingLaboratories->contains($participatingLaboratory)) {
            $this->participatingLaboratories->removeElement($participatingLaboratory);
        }

        return $this;
    }
}
