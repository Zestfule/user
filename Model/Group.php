<?php

namespace Zestfule\UserBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class Group implements GroupInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=36, unique=true)
     * @ORM\Id()
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     minMessage="Your Groupname must be at least {{ limit }} characters long.",
     *     max="64",
     *     maxMessage="Your Groupname cannot be more than {{ limit }} characters long."
     * )
     */
    protected $name;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Group
     */
    public function setId(string $id): Group
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Group
     */
    public function setName(string $name): Group
    {
        $this->name = $name;
        return $this;
    }
}