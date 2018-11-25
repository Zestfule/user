<?php

namespace Zestfule\UserBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class Profile implements ProfileInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=36, unique=true)
     * @ORM\Id()
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="firstname", type="string", length=64, nullable=true)
     * @Assert\Length(
     *     min="1",
     *     minMessage="Your Firstname must be at least {{ limit }} character long.",
     *     max="64",
     *     maxMessage="Your Firstname cannot be more than {{ limit }} characters long."
     * )
     */
    protected $firstname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lastname", type="string", length=64, nullable=true)
     * @Assert\Length(
     *     min="1",
     *     minMessage="Your Lastname must be at least {{ limit }} character long.",
     *     max="64",
     *     maxMessage="Your Lastname cannot be more than {{ limit }} characters long."
     * )
     */
    protected $lastname;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Profile
     */
    public function setId(string $id): Profile
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param null|string $firstname
     * @return Profile
     */
    public function setFirstname(?string $firstname): Profile
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param null|string $lastname
     * @return Profile
     */
    public function setLastname(?string $lastname): Profile
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->getFirstname() . " " . $this->getLastname();
    }
}