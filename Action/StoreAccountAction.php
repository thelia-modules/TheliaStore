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
namespace TheliaStore\Action;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Thelia\Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Core\Security\Token\TokenProvider;
use TheliaStore\Classes\TheliaStoreUser;
use TheliaStore\TheliaStore;

class StoreAccountAction implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    protected $request;

    use \Thelia\Tools\RememberMeTrait;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function boot()
    {
        try {
            if (!TheliaStore::isConnected()) {
                $myCookie = $this->getRememberMeKeyFromCookie($this->request, 'theliastore');
                if ($myCookie) {
                    $tokenProvider = new TokenProvider();

                    /** @var array('username'=>string, 'token'=>string 'serial'=>string) $decodeKey */
                    $decodeKey = $tokenProvider->decodeKey($myCookie);

                    $data = unserialize($decodeKey['serial']);
                    $session = new Session();
                    $session->set('isconnected', '1');
                    $session->set('storecustomer', $data);
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::FINISH_REQUEST => array("boot", 0),
        );
    }
}
