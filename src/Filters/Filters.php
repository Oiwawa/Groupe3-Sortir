<?php

namespace App\Filters;

use App\Entity\Campus;
use App\Entity\Event;
use DateTime;
use App\Entity\User;


class Filters
{

    /**
     * @var string
     */
    public $text = '';

    /**
     * @var Campus
     */
    public $campus;

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
     * @var Event
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
