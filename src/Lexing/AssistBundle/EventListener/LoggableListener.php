<?php

namespace Lexing\AssistBundle\EventListener;

use Doctrine\Common\EventArgs;
use Gedmo\Mapping\MappedEventSubscriber;
use Gedmo\Loggable\Mapping\Event\LoggableAdapter;
use Gedmo\Tool\Wrapper\AbstractWrapper;
use Gedmo\Loggable\LoggableListener as GedmoLoggableListener;
use Lexing\DealerBundle\Entity\VehicleDealer;
use Lexing\LoanBundle\Entity\VehicleMortgage;
use Lexing\VehicleBundle\Entity\Vehicle;

/**
 * Loggable listener
 *
 */
class LoggableListener extends GedmoLoggableListener
{
    /**
     * @var User
     */
    private $user;

    /**
     * @override
     */
    public function setUsername($username)
    {
        parent::setUsername($username);
        if (is_object($username)) {
            $this->user = $username->getUser();
        }
    }

    /**
     * @override
     */
    protected function prePersistLogEntry($logEntry, $object)
    {
        if ($logEntry && $this->user) {
            $logEntry->setUser($this->user);
        }
    }
}
