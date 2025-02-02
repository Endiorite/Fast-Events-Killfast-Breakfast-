<?php

namespace fenomeno\KillbreakFast\Events\variants;

use fenomeno\KillbreakFast\Events\FastEvent;
use fenomeno\KillbreakFast\Handlers\FastEventHandler;

class FastEventEndEvent extends FastEvent
{

    private array $rewards;
    private array $top;

    public function __construct(FastEventHandler $fastEvent)
    {
        parent::__construct($fastEvent);
        $this->rewards = $fastEvent->getRewards();
        $this->top     = $fastEvent->getTop();
    }

    public function getRewards(): array
    {
        return $this->rewards;
    }

    public function setRewards(array $rewards): void
    {
        $this->rewards = $rewards;
    }

    public function getTop(): array
    {
        return $this->top;
    }

    public function setTop(array $top): void
    {
        $this->top = $top;
    }

}