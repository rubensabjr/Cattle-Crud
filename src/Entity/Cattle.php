<?php

namespace App\Entity;

use App\Repository\CattleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CattleRepository::class)
 * @UniqueEntity(fields={"code"}, message="Código já cadastrado")
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
     * @Assert\Positive(message="Somente valores positivos permitidos")
     * @Assert\Length(
     *      min = 1,
     *      max = 7,
     *      maxMessage = "Limite de 7 caracteres"
     * )
     */
    private $code;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message="Somente valores iguais ou maiores que 0 permitidos")
     */
    private $milk;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message="Somente valores iguais ou maiores que 0 permitidos")
     */
    private $ration;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(message="Somente valores iguais ou maiores que 0 permitidos")
     */
    private $weight;

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

    public function getMilk(): ?float
    {
        return $this->milk;
    }

    public function setMilk(float $milk): self
    {
        $this->milk = $milk;

        return $this;
    }

    public function getRation(): ?float
    {
        return $this->ration;
    }

    public function setRation(float $ration): self
    {
        $this->ration = $ration;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

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
