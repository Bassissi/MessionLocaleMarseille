<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 */
class Section
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subsection", mappedBy="section", orphanRemoval=true)
     */
    private $subsections;

    /**
     * @ORM\Column(type="string", length=255)
     *    @Assert\Length(
     *      min =4,
     *      max = 100,
     *      minMessage = "Name Catégoie must be at least {{ limit }} characters long",
     *      maxMessage = "Name Catégoie cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tchat", mappedBy="section", orphanRemoval=true)
     */
    private $tchats;

    public function __construct()
    {
        $this->subsections = new ArrayCollection();
        $this->tchats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Subsection[]
     */
    public function getSubsections(): Collection
    {
        return $this->subsections;
    }

    public function addSubsection(Subsection $subsection): self
    {
        if (!$this->subsections->contains($subsection)) {
            $this->subsections[] = $subsection;
            $subsection->setSection($this);
        }

        return $this;
    }

    public function removeSubsection(Subsection $subsection): self
    {
        if ($this->subsections->contains($subsection)) {
            $this->subsections->removeElement($subsection);
            // set the owning side to null (unless already changed)
            if ($subsection->getSection() === $this) {
                $subsection->setSection(null);
            }
        }

        return $this;
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
     * @return Collection|Tchat[]
     */
    public function getTchats(): Collection
    {
        return $this->tchats;
    }

    public function addTchat(Tchat $tchat): self
    {
        if (!$this->tchats->contains($tchat)) {
            $this->tchats[] = $tchat;
            $tchat->setSection($this);
        }

        return $this;
    }

    public function removeTchat(Tchat $tchat): self
    {
        if ($this->tchats->contains($tchat)) {
            $this->tchats->removeElement($tchat);
            // set the owning side to null (unless already changed)
            if ($tchat->getSection() === $this) {
                $tchat->setSection(null);
            }
        }

        return $this;
    }
}
