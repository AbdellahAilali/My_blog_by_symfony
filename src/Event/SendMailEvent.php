<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class SendMailEvent extends Event
{
    const NAME = "send.mail";
    /**
     * @var User
     */
    private $userCreated;

    public function __construct(User $userCreated)
    {
        $this->userCreated = $userCreated;
    }

    public function getUserCreated()
    {
        return $this->userCreated;
    }
}