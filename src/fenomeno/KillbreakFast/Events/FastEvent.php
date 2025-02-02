<?php

namespace fenomeno\KillbreakFast\Events;

use fenomeno\KillbreakFast\Handlers\FastEventHandler;
use pocketmine\event\Event;

class FastEvent extends Event
{

    public function __construct(
        private readonly FastEventHandler $fastEvent
    ){}

    public function getFastEvent(): FastEventHandler
    {
        return $this->fastEvent;
    }

}