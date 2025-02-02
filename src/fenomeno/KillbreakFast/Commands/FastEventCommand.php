<?php

namespace fenomeno\KillbreakFast\Commands;

use fenomeno\KillbreakFast\Commands\subCommands\StartSubCommand;
use fenomeno\KillbreakFast\Commands\subCommands\StopSubCommand;
use fenomeno\KillbreakFast\Handlers\FastEventHandler;
use fenomeno\KillbreakFast\libs\CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class FastEventCommand extends BaseCommand
{

    public function __construct(
        Plugin $plugin,
        private readonly FastEventHandler $handler,
        string $name,
        Translatable|string $description = "",
        array $aliases = []
    ){
        parent::__construct($plugin, $name, $description, $aliases);
    }

    protected function prepare(): void
    {
        $this->setPermission($this->getPermission());
        $this->registerSubCommand(new StartSubCommand($this->handler));
        $this->registerSubCommand(new StopSubCommand($this->handler));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage(
            TextFormat::GRAY . "Utilisation : " . TextFormat::YELLOW . "/{$this->getName()} <start|stop>" . "\n\n" .
            TextFormat::WHITE . "• " . TextFormat::GREEN . "start" . TextFormat::WHITE . " : Lance l'événement et annonce son début." . "\n" .
            TextFormat::WHITE . "• " . TextFormat::RED . "stop" . TextFormat::WHITE . " : Termine l'événement et annonce les résultats." . "\n"
        );
    }

    public function getPermission(): string
    {
        return "killbreakfast.killbreak.command";
    }
}