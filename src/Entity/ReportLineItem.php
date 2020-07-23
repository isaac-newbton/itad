<?php

namespace App\Entity;

use App\Repository\ReportLineItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ReportLineItemRepository::class)
 */
class ReportLineItem
{
    use EntityIdTrait;

    /**
     * @ORM\ManyToOne(targetEntity=YearlyReport::class, inversedBy="reportLineItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity=Adulterant::class, inversedBy="reportLineItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adulterant;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $value;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getReport(): ?YearlyReport
    {
        return $this->report;
    }

    public function setReport(?YearlyReport $report): self
    {
        $this->report = $report;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
