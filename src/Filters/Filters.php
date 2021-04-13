<?php

namespace App\Filters;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\EventState;
use DateTime;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;


class Filters
{

    /**
     * @var string
     */
    public $text = '';

    /**
     * @var Campus[]
     */
    public $campus = [];

    /**
     * @var User
     */
    public $organizer;

    /**
     * @var User
     */
    public $subscribed;
    /**
     * @var User
     */
    public $notSubscribed;

    /**
     * @var Event[]
     */
    public $passedEvents;


    /**
     * @var DateTime
     */
    public $dateStart;

    /**
     * @var DateTime
     */
    public $dateEnd;





}
