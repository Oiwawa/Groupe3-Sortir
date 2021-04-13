<?php

namespace App\Filters;

use App\Entity\Campus;
use App\Entity\EventState;
use DateTime;
use App\Entity\User;


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
    public $organizer = [];

    /**
     * @var User[]
     */
    public $subscribed = [];
    /**
     * @var EventState[]
     */
    public $state = [];

    /**
     * @var DateTime
     */
    public $dateStart;

    /**
     * @var DateTime
     */
    public $dateEnd;


}
