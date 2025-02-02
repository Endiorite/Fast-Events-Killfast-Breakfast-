<?php

namespace fenomeno\KillbreakFast\Events\variants;

use fenomeno\KillbreakFast\Events\FastEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

class FastEventStartEvent extends FastEvent implements Cancellable
{
    use CancellableTrait;

}