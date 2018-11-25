<?php

namespace Zestfule\UserBundle\Model;

interface GroupInterface
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
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return string
     */
    public function setName(string $name);
}