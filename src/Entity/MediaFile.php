<?php

namespace App\Entity;

use App\Repository\MediaFileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(repositoryClass=MediaFileRepository::class)
 */
class MediaFile
{
    use EntityIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="mediaFiles")
     */
    private $articles;

    /**
     * @ORM\OneToOne(targetEntity=Adulterant::class, mappedBy="thumbnail", cascade={"persist", "remove"})
     */
    private $adulterant;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $niceName;

    public function __construct(){
        $this->uuid = Uuid::uuid4();
        $this->articles = new ArrayCollection();
    }

    public function __toString(){
        if(trim($this->niceName)!='') return $this->niceName;
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addMediaFile($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeMediaFile($this);
        }

        return $this;
    }

    public function getAdulterant(): ?Adulterant
    {
        return $this->adulterant;
    }

    public function setAdulterant(?Adulterant $adulterant): self
    {
        $this->adulterant = $adulterant;

        // set (or unset) the owning side of the relation if necessary
        $newThumbnail = null === $adulterant ? null : $this;
        if ($adulterant->getThumbnail() !== $newThumbnail) {
            $adulterant->setThumbnail($newThumbnail);
        }

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
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
