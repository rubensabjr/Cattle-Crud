<?php

namespace App\Entity;

use App\Repository\CattleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CattleRepository::class)
 */
class Cattle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $milk;

    /**
     * @ORM\Column(type="integer")
     */
    private $ration;

    /**
     * @ORM\Column(type="date")
     */
    private $birth;

    /**
     * @ORM\Column(type="boolean")
     */
    private $slaughter;

    /**
     * @ORM\Column(type="boolean")
     */
    private $slaughtered;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMilk(): ?int
    {
        return $this->milk;
    }

    public function setMilk(int $milk): self
    {
        $this->milk = $milk;

        return $this;
    }

    public function getRation(): ?int
    {
        return $this->ration;
    }

    public function setRation(int $ration): self
    {
        $this->ration = $ration;

        return $this;
    }

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(\DateTimeInterface $birth): self
    {
        $this->birth = $birth;

        return $this;
    }

    public function getSlaughter(): ?bool
    {
        return $this->slaughter;
    }

    public function setSlaughter(bool $slaughter): self
    {
        $this->slaughter = $slaughter;

        return $this;
    }

    public function getSlaughtered(): ?bool
    {
        return $this->slaughtered;
    }

    public function setSlaughtered(bool $slaughtered): self
    {
        $this->slaughtered = $slaughtered;

        return $this;
    }
}
