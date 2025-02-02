<?php

namespace fenomeno\KillbreakFast\Commands\subCommands;

use fenomeno\KillbreakFast\Events\variants\FastEventEndEvent;
use fenomeno\KillbreakFast\Handlers\FastEventHandler;
use fenomeno\KillbreakFast\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class StopSubCommand extends BaseSubCommand
{

    public function __construct(private readonly FastEventHandler $handler){
        parent::__construct("stop", "Arrêter l'event");
    }

    protected function prepare(): void
    {

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(! $this->handler->isEnabled()){
            $sender->sendMessage(TextFormat::RED . "Event déjà OFF");
            return;
        }

        $ev = new FastEventEndEvent($this->handler);
        $ev->call();

        $this->handler->setEnabled(false);
        $this->handler->setPlayers([]);
        $sender->getServer()->broadcastMessage(TextFormat::GOLD . "Event arrêté");
    }
}