<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 *  @UniqueEntity("title")
 * @Vich\Uploadable()
 */
class Property
{
    const HEAT = [
        0 => 'electrique',
        1 => 'gaz',
        2 => 'collectif',
        3 => 'autre'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="5")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min="10", max="400")
     */
    private $surface;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $bedrooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero
     */
    private $floor;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min="5000")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $heat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $postal_code;

    /**
     * @ORM\Column(type="boolean", options={"defaults"=false})
     */
    private $sold = false ;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Optional", inversedBy="properties")
     */
    private $optionals;



    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->optionals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title);
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

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price,0,'',' ' );
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    public function getHeatType(): string
    {
        return self::HEAT[$this->heat];
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Property
     */
    public function setFilename(?string $filename): Property
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Property
     * @throws Exception
     */
    public function setImageFile(?File $imageFile): Property
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile){
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Optional[]
     */
    public function getOptionals(): Collection
    {
        return $this->optionals;
    }

    public function addOptional(Optional $optional): self
    {
        if (!$this->optionals->contains($optional)) {
            $this->optionals[] = $optional;
            $optional->addProperty($this);
        }

        return $this;
    }

    public function removeOptional(Optional $optional): self
    {
        if ($this->optionals->contains($optional)) {
            $this->optionals->removeElement($optional);
            $optional->removeProperty($this);
        }

        return $this;
    }

}
