<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToOne(targetEntity=MediaFile::class, cascade={"persist", "remove"})
     */
    private $flag;

    /**
     * @ORM\OneToMany(targetEntity=YearlyReport::class, mappedBy="country", orphanRemoval=true)
     * @ORM\OrderBy({"year" = "DESC"})
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFlag(): ?MediaFile
    {
        return $this->flag;
    }

    public function setFlag(?MediaFile $flag): self
    {
        $this->flag = $flag;

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
            $yearlyReport->setCountry($this);
        }

        return $this;
    }

    public function removeYearlyReport(YearlyReport $yearlyReport): self
    {
        if ($this->yearlyReports->contains($yearlyReport)) {
            $this->yearlyReports->removeElement($yearlyReport);
            // set the owning side to null (unless already changed)
            if ($yearlyReport->getCountry() === $this) {
                $yearlyReport->setCountry(null);
            }
        }

        return $this;
    }
}
