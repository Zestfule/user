<?php

namespace Zestfule\UserBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id);

    /**
     * @return Profile
     */
    public function getProfile();

    /**
     * @param ProfileInterface $profile
     * @return $this
     */
    public function setProfile(ProfileInterface $profile);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email);

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled);

    /**
     * @return bool
     */
    public function isLocked();

    /**
     * @param bool $locked
     * @return $this
     */
    public function setLocked(bool $locked);

    /**
     * @return \DateTime
     */
    public function getLastLogin();

    /**
     * @param \DateTime|null $time
     * @return $this
     */
    public function setLastLogin(\DateTime $time = null);

    /**
     * @return string
     */
    public function getConfirmationToken();

    /**
     * @param string $confirmationToken
     * @return $this
     */
    public function setConfirmationToken(string $confirmationToken);

    /**
     * @return string
     */
    public function createConfirmationToken();

    /**
     * @return \DateTime
     */
    public function getPasswordRequestedAt();

    /**
     * @param \DateTime|null $time
     * @return $this
     */
    public function setPasswordRequestedAt(\DateTime $time = null);

    /**
     * @param int $requestTime
     * @return bool
     */
    public function isPasswordRequestNotExpired(int $requestTime);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime|null $time
     * @return $this
     */
    public function setCreatedAt(\DateTime $time = null);

    /**
     * @return ArrayCollection
     */
    public function getGroups();

    /**
     * @param Group $group
     * @return bool
     */
    public function hasGroup(Group $group);

    /**
     * @param Group $group
     * @return $this
     */
    public function addGroup(Group $group);

    /**
     * @param Group $group
     * @return $this
     */
    public function removeGroup(Group $group);
}