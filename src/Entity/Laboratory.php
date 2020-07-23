<?php

namespace App\Entity;

use App\Repository\LaboratoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=LaboratoryRepository::class)
 */
class Laboratory
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=YearlyReport::class, mappedBy="participatingLaboratories")
     */
    private $yearlyReports;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->yearlyReports = new ArrayCollection();
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

    /**
     * @return Collection|YearlyReport[]
     */
    public function getYearlyReports(): Collection
    {
        return $this->yearlyReports;
    }

    public function addYearlyReport(YearlyReport $yearlyReport): self
    {
        if (!$this->yearlyReports->contains($yearlyReport)) {
            $this->yearlyReports[] = $yearlyReport;
            $yearlyReport->addParticipatingLaboratory($this);
        }

        return $this;
    }

    public function removeYearlyReport(YearlyReport $yearlyReport): self
    {
        if ($this->yearlyReports->contains($yearlyReport)) {
            $this->yearlyReports->removeElement($yearlyReport);
            $yearlyReport->removeParticipatingLaboratory($this);
        }

        return $this;
    }
}
