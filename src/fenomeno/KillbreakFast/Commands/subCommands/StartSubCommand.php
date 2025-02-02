<?php

namespace fenomeno\KillbreakFast\Commands\subCommands;

use fenomeno\KillbreakFast\Events\variants\FastEventStartEvent;
use fenomeno\KillbreakFast\Handlers\FastEventHandler;
use fenomeno\KillbreakFast\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class StartSubCommand extends BaseSubCommand
{

    public function __construct(private readonly FastEventHandler $handler){
        parent::__construct("start", "Démarrer l'event");
    }

    protected function prepare(): void
    {

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if($this->handler->isEnabled()){
            $sender->sendMessage(TextFormat::RED . "Event déjà ON");
            return;
        }

        $ev = new FastEventStartEvent($this->handler);
        $ev->call();
        if($ev->isCancelled()){
            return;
        }

        $this->handler->setEnabled(true);
        $this->handler->setPlayers([]);
        $this->handler->handleStart();
    }
}