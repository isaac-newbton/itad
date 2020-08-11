<?php

namespace App\Entity;

use App\Repository\FileDownloadRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=FileDownloadRepository::class)
 */
class FileDownload
{
    use EntityIdTrait;

    /**
     * @ORM\OneToOne(targetEntity=MediaFile::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $file;

    /**
     * @ORM\OneToOne(targetEntity=MediaFile::class, cascade={"persist", "remove"})
     */
    private $thumbnail;

    /**
     * @ORM\ManyToOne(targetEntity=YearlyReport::class, inversedBy="fileDownloads")
     */
    private $yearlyReport;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $niceName;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function __toString()
    {
        return $this->niceName ?? $this->file->__toString();
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

    public function getThumbnail(): ?MediaFile
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?MediaFile $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

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

    public function getThumbnailImgSrc(): ?string{
        if(!$this->thumbnail) return null;
        return str_replace(['/public', '\\'], ['', '/'], $this->thumbnail->getPath());
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
}
