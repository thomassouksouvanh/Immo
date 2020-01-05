<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class PropertySearch
{
    public function __construct()
    {
        $this->optionals = new ArrayCollection();
    }

    /**
     * @var int|integer
     */
    private $maxPrice;

    /**
     * @return int
     * @assert\Range(min=5000, max=1000000)
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * @param int $maxPrice
     * @return PropertySearch
     */
    public function setMaxPrice(int $maxPrice): PropertySearch
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @return int
     * @Assert\Range(min=10, max=400)
     */
    public function getMinSurface()
    {
        return $this->minSurface;
    }

    /**
     * @param int $minSurface
     * @return PropertySearch
     */
    public function setMinSurface(int $minSurface): PropertySearch
    {
        $this->minSurface = $minSurface;
        return $this;
    }

    /**
     * @var int|integer
     */
    private $minSurface;

    /**
     * @var ArrayCollection
     */
    private $optionals;

    /**
     * @return ArrayCollection
     */
    public function getOptionals(): ArrayCollection
    {
        return $this->optionals;
    }

    /**
     * @param ArrayCollection $optionals
     * @return PropertySearch
     */
    public function setOptionals(ArrayCollection $optionals): PropertySearch
    {
        $this->optionals = $optionals;
        return $this;
    }

}

