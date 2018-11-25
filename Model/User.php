<?php

namespace Zestfule\UserBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class User implements UserInterface, \Serializable
{
    public const ROLE_DEFAULT = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_ALL_ACCESS = 'ROLE_SUPER_ADMIN';

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
     * @ORM\Column(name="username", type="string", length=64, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     minMessage="Your Username must be at least {{ limit }} characters long.",
     *     max="64",
     *     maxMessage="Your Username cannot be more than {{ limit }} characters long."
     * )
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Email(
     *     mode="html5",
     *     message="'{{ value }}' is not a valid email address."
     * )
     * @Assert\Length(
     *     max="128",
     *     maxMessage="Your email address cannot be more than {{ limit }} long."
     * )
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=false)
     */
    protected $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    protected $isEnabled = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_locked", type="boolean")
     */
    protected $isLocked = false;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="confirmation_token", type="string", length=256, unique=true, nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password_reset_token", type="string", length=256, unique=true, nullable=true)
     */
    protected $passwordResetToken;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="password_requested_at", type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var ProfileInterface
     *
     * @ORM\OneToOne(targetEntity="Profile", cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     * @Assert\Valid()
     */
    protected $profile;

    /**
     * @var ArrayCollection
     *
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(
     *     name="user_group_map",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
     *     }
     * )
     */
    protected $groups;

    public function __construct()
    {
        $this->roles = [static::ROLE_DEFAULT];
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return User
     */
    public function setId(string $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return User
     */
    public function setEnabled(bool $isEnabled): User
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isLocked(): ?bool
    {
        return $this->isLocked;
    }

    /**
     * @param bool $isLocked
     * @return User
     */
    public function setLocked(bool $isLocked): User
    {
        $this->isLocked = $isLocked;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     * @return User
     */
    public function setLastLogin(?\DateTime $lastLogin = null): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * @param null|string $confirmationToken
     * @return User
     */
    public function setConfirmationToken(?string $confirmationToken): User
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return string|void
     * @throws \Exception
     */
    public function createConfirmationToken()
    {
        $token = bin2hex(random_bytes(64));
        $this->setConfirmationToken($token);
    }

    /**
     * @return null|string
     */
    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    /**
     * @param null|string $passwordResetToken
     * @return User
     */
    public function setPasswordResetToken(?string $passwordResetToken): User
    {
        $this->passwordResetToken = $passwordResetToken;
        return $this;
    }

    /**
     * @return string|void
     * @throws \Exception
     */
    public function createPasswordResetToken()
    {
        $token = bin2hex(random_bytes(64));
        $this->setPasswordResetToken($token);
    }

    /**
     * @return \DateTime|null
     */
    public function getPasswordRequestedAt(): ?\DateTime
    {
        return $this->passwordRequestedAt;
    }

    /**
     * @param int $requestTime
     * @return bool
     */
    public function isPasswordRequestNotExpired(int $requestTime)
    {
        return
            ($this->getPasswordRequestedAt() instanceof \DateTime) &&
            ($this->getPasswordRequestedAt()->add(\DateInterval::createFromDateString($requestTime . ' seconds')) > new \DateTime('now', new \DateTimeZone('UTC')));
    }

    /**
     * @param \DateTime|null $passwordRequestedAt
     * @return User
     */
    public function setPasswordRequestedAt(?\DateTime $passwordRequestedAt = null): User
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     * @return User
     */
    public function setCreatedAt(?\DateTime $createdAt = null): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return ProfileInterface
     */
    public function getProfile(): ProfileInterface
    {
        return $this->profile;
    }

    /**
     * @param ProfileInterface $profile
     * @return User
     */
    public function setProfile(ProfileInterface $profile): User
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param Group $group
     * @return bool
     */
    public function hasGroup(Group $group)
    {
        if ($this->getGroups()->contains($group))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param Group $group
     * @return User
     */
    public function addGroup(Group $group): User
    {
        $this->groups->add($group);
        return $this;
    }

    /**
     * @param Group $group
     * @return User
     */
    public function removeGroup(Group $group): User
    {
        $this->groups->remove($group);
        return $this;
    }

    /**
     * Group combined Roles.
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        return array_unique($roles);
    }

    /**
     * @return array
     */
    public function getRolesUser()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * @param $role
     *
     * @return $this
     */
    public function addRole($role)
    {
        $role = mb_strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @param $role
     *
     * @return $this
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(mb_strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array(mb_strtoupper($role), $this->getRoles(), true);
    }

    /**
     *
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return null|string|void
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @see \Serializable::serialize()
     *
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->isEnabled,
            $this->lastLogin,
            $this->createdAt
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->isEnabled,
            $this->lastLogin,
            $this->createdAt
            ) = unserialize($serialized);
    }
}