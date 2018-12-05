<?php

namespace App\EventWithoutSymfony;

use App\Entity\User;
use DateTime;
use Symfony\Component\EventDispatcher\EventDispatcher;

require  __DIR__.'/../../vendor/autoload.php';

$user = new User("007","Pablo","Escobar",new DateTime("01-01-1993"));

$event = new SendMailEvent($user);

$dispatcher = new EventDispatcher();

$listener = new SendMailListener();

//si cette event et demander appliquer  ce listener;.
//$dispatcher->addListener('send.mail',  [$listener, 'sendMailAction']);


$dispatcher->dispatch("send.mail", $event);


// différence entre un Listener et un Subscriber ?

