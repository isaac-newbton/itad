<?php

namespace App\Entity;

use App\Repository\PresentationRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=PresentationRepository::class)
 */
class Presentation
{

    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=MediaFile::class, cascade={"persist", "remove"})
     */
    private $file;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->dt = new DateTime();
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

    public function getDt(): ?\DateTimeInterface
    {
        return $this->dt;
    }

    public function setDt(\DateTimeInterface $dt): self
    {
        $this->dt = $dt;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getFile(): ?MediaFile
    {
        return $this->file;
    }

    public function setFile(?MediaFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFileHref(): string{
        return $this->file ? str_replace(['/public', '\\'], ['', '/'], $this->file->getPath()) : '';
    }
}
