<?php

namespace App\Entity;

use App\Repository\ExcelDataFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ExcelDataFileRepository::class)
 */
class ExcelDataFile
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $niceName;

    /**
     * @ORM\OneToOne(targetEntity=MediaFile::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity=YearlyReport::class, inversedBy="excelDataFiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $yearlyReport;

    /**
     * @ORM\ManyToOne(targetEntity=user::class)
     */
    private $user;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function __toString()
    {
        return $this->niceName ?? $this->file->__toString();
    }

    public function getNiceName(): ?string
    {
        return $this->niceName;
    }

    public function setNiceName(?string $niceName): self
    {
        $this->niceName = $niceName;

        return $this;
    }

    public function getFile(): ?MediaFile
    {
        return $this->file;
    }

    public function setFile(MediaFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getYearlyReport(): ?YearlyReport
    {
        return $this->yearlyReport;
    }

    public function setYearlyReport(?YearlyReport $yearlyReport): self
    {
        $this->yearlyReport = $yearlyReport;

        return $this;
    }

    public function getHref(): string{
        return str_replace(['/public', '\\'], ['', '/'], $this->file->getPath());
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
