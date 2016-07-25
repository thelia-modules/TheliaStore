<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace TheliaStore\Classes;

use Thelia\Core\Security\Role\Role;
use Thelia\Core\Security\User\UserInterface;

class TheliaStoreUser implements UserInterface
{
    protected $id;
    protected $username;
    protected $token;
    protected $serial;

    /**
     * TheliaStoreUser constructor.
     * @param int $id
     * @param mixed $data
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getId();
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    /**
     * @param string $password
     * @return mixed
     */
    public function checkPassword($password)
    {
        // TODO: Implement checkPassword() method.
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    /**
     * @return mixed
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return mixed
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param string $serial
     * @return mixed
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
        return $this;
    }

}