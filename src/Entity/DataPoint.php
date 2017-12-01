<?php

namespace Ontic\RFMap\Entity;

class DataPoint
{
    /** @var string */
    public $plugin;
    /** @var string */
    public $entityid;
    /** @var \DateTime */
    public $datetime;
    /** @var string */
    public $name;
    /** @var float */
    public $frequency;
    /** @var float */
    public $bandwidth;
    /** @var float */
    public $rssi;
    /** @var array */
    public $extra;
}