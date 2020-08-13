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
     * @ORM\OrderBy({"value" = "DESC"})
     */
    private $reportLineItems;

    /**
     * @ORM\ManyToMany(targetEntity=Laboratory::class, inversedBy="yearlyReports")
     */
    private $participatingLaboratories;

    /**
     * @ORM\OneToMany(targetEntity=FileDownload::class, mappedBy="yearlyReport")
     */
    private $fileDownloads;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ExcelDataFile::class, mappedBy="yearlyReport", orphanRemoval=true)
     */
    private $excelDataFiles;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->reportLineItems = new ArrayCollection();
        $this->participatingLaboratories = new ArrayCollection();
        $this->fileDownloads = new ArrayCollection();
        $this->excelDataFiles = new ArrayCollection();
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

    /**
     * @return Collection|FileDownload[]
     */
    public function getFileDownloads(): Collection
    {
        return $this->fileDownloads;
    }

    public function addFileDownload(FileDownload $fileDownload): self
    {
        if (!$this->fileDownloads->contains($fileDownload)) {
            $this->fileDownloads[] = $fileDownload;
            $fileDownload->setYearlyReport($this);
        }

        return $this;
    }

    public function removeFileDownload(FileDownload $fileDownload): self
    {
        if ($this->fileDownloads->contains($fileDownload)) {
            $this->fileDownloads->removeElement($fileDownload);
            // set the owning side to null (unless already changed)
            if ($fileDownload->getYearlyReport() === $this) {
                $fileDownload->setYearlyReport(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ExcelDataFile[]
     */
    public function getExcelDataFiles(): Collection
    {
        return $this->excelDataFiles;
    }

    public function addExcelDataFile(ExcelDataFile $excelDataFile): self
    {
        if (!$this->excelDataFiles->contains($excelDataFile)) {
            $this->excelDataFiles[] = $excelDataFile;
            $excelDataFile->setYearlyReport($this);
        }

        return $this;
    }

    public function removeExcelDataFile(ExcelDataFile $excelDataFile): self
    {
        if ($this->excelDataFiles->contains($excelDataFile)) {
            $this->excelDataFiles->removeElement($excelDataFile);
            // set the owning side to null (unless already changed)
            if ($excelDataFile->getYearlyReport() === $this) {
                $excelDataFile->setYearlyReport(null);
            }
        }

        return $this;
    }
}
