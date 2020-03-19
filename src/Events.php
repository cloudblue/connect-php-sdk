<?php

/**
 * This file is part of the Ingram Micro Cloud Blue Connect SDK.
 *
 * @copyright (c) 2018-2020. Ingram Micro. All Rights Reserved.
 */

namespace Connect;

/**
 * Class Events
 * @package Connect
 */
class Events extends Model
{
    /**
     * @var string|null|Event
     */
    public $inquired;
    /**
     * @var string|null|Event
     */
    public $updated;
    /**
     * @var string|null|Event
     */
    public $created;

    /**
     * @var string|null|Event
     */
    public $approved;

    /**
     * @var string|null|Event
     */
    public $pended;

    /**
     * @var string|null|Event
     */
    public $uploaded;

    /**
     * @var string|null|Event
     */
    public $submitted;

    /**
     * @var string|null|Event
     */
    public $accepted;

    /**
     * @var string|null|Event
     */
    public $rejected;

    /**
     * @var string|null|Event
     */
    public $closed;
}
