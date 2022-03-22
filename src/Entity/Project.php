<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity("name")]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'boolean')]
    private $is_online;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'boolean')]
    private $in_biography;

    #[ORM\Column(type: 'smallint')]
    private $year;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectImage::class, cascade: ["persist", "remove"])]
    private $projectImages;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updated_at;

    public function __construct()
    {
        $this->projectImages = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->year = date('Y');
        $this->slug = "";
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->is_online;
    }

    public function setIsOnline(bool $is_online): self
    {
        $this->is_online = $is_online;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getInBiography(): ?bool
    {
        return $this->in_biography;
    }

    public function setInBiography(bool $in_biography): self
    {
        $this->in_biography = $in_biography;

        return $this;
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

    /**
     * @return Collection<int, ProjectImage>
     */
    public function getProjectImages(): Collection
    {
        $iterator = $this->projectImages->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });
        $collection = new ArrayCollection(iterator_to_array($iterator));

        return $collection;
    }

    public function addProjectImage(ProjectImage $projectImage): self
    {
        if (!$this->projectImages->contains($projectImage)) {
            $this->projectImages[] = $projectImage;
            $projectImage->setProject($this);
        }

        return $this;
    }

    public function removeProjectImage(ProjectImage $projectImage): self
    {
        if ($this->projectImages->removeElement($projectImage)) {
            // set the owning side to null (unless already changed)
            if ($projectImage->getProject() === $this) {
                $projectImage->setProject(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    #[ORM\PreUpdate]
    public function preUpdate() {
        $this->setUpdatedAt(new \DateTimeImmutable());
    }
}
