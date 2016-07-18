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
namespace TheliaStore\Event;

use Thelia\Core\Event\ActionEvent;
use TheliaStore\Model\StoreExtension;

class StoreExtensionEvent extends ActionEvent
{

    protected $extensionId;
    protected $productExtensionId;
    protected $token;
    protected $code;
    protected $extensionName;
    protected $installationState;

    /** @var StoreExtension  */
    protected $storeExtension;

    /**
     * StoreExtensionEvent constructor.
     * @param $extensionId
     * @param $productExtensionId
     * @param $token
     * @param $code
     * @param $extensionName
     * @param $installationState
     */
    public function __construct($extensionId, $productExtensionId, $token, $code, $extensionName, $installationState)
    {
        $this->extensionId = $extensionId;
        $this->productExtensionId = $productExtensionId;
        $this->token = $token;
        $this->code = $code;
        $this->extensionName = $extensionName;
        $this->installationState = $installationState;
    }

    /**
     * @return mixed
     */
    public function getExtensionId()
    {
        return $this->extensionId;
    }

    /**
     * @param mixed $extensionId
     * @return StoreExtensionEvent
     */
    public function setExtensionId($extensionId)
    {
        $this->extensionId = $extensionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductExtensionId()
    {
        return $this->productExtensionId;
    }

    /**
     * @param mixed $productExtensionId
     * @return StoreExtensionEvent
     */
    public function setProductExtensionId($productExtensionId)
    {
        $this->productExtensionId = $productExtensionId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return StoreExtensionEvent
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return StoreExtensionEvent
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtensionName()
    {
        return $this->extensionName;
    }

    /**
     * @param mixed $extensionName
     * @return StoreExtensionEvent
     */
    public function setExtensionName($extensionName)
    {
        $this->extensionName = $extensionName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstallationState()
    {
        return $this->installationState;
    }

    /**
     * @param mixed $installationState
     * @return StoreExtensionEvent
     */
    public function setInstallationState($installationState)
    {
        $this->installationState = $installationState;
        return $this;
    }

    /**
     * @return StoreExtension
     */
    public function getStoreExtension()
    {
        return $this->storeExtension;
    }

    /**
     * @param StoreExtension $storeExtension
     * @return StoreExtensionEvent
     */
    public function setStoreExtension($storeExtension)
    {
        $this->storeExtension = $storeExtension;
        return $this;
    }
}