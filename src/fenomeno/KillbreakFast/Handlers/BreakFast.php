<?php

namespace fenomeno\KillbreakFast\Handlers;

use pocketmine\item\GoatHornType;
use pocketmine\item\VanillaItems;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\BellRingSound;
use pocketmine\world\sound\GoatHornSound;

class BreakFast extends FastEventHandler
{

    public function handleStart(): void
    {
        $server = Server::getInstance();

        foreach ($server->getOnlinePlayers() as $player) {
            $player->getWorld()->addSound($player->getPosition(), new GoatHornSound(GoatHornType::CALL));
            $player->sendTitle(
                TextFormat::BOLD . TextFormat::GOLD . "BREAKFAST COMMENCE !",
                TextFormat::GRAY . "Cassez un maximum de plantes pour gagner !",
            );
        }

        $server->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . "🌾 BREAKFAST EVENT 🌾" . TextFormat::RESET);
        $server->broadcastMessage(TextFormat::GRAY . "L'événement a commencé ! Chaque plante cassée rapporte 1 point !");
        $server->broadcastMessage(TextFormat::GREEN . "🥇 Soyez dans le top 3 pour gagner une récompense !");
    }

    public function handleStop(): void
    {
        $server = Server::getInstance();
        $topPlayers = $this->getTop();

        $server->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . "🌾 FIN DU BREAKFAST 🌾");
        $server->broadcastMessage(TextFormat::GRAY . "L'événement est terminé !");

        $server->broadcastMessage(TextFormat::BOLD . TextFormat::YELLOW . "🥇 CLASSEMENT FINAL :");
        $position = 1;
        foreach ($topPlayers as $playerName => $points) {
            $server->broadcastMessage(
                TextFormat::GOLD . "#{$position} " . TextFormat::AQUA . $playerName . TextFormat::GRAY . " - " .
                TextFormat::GREEN . "{$points} points"
            );
            $position++;
        }

        foreach ($server->getOnlinePlayers() as $player) {
            $player->getWorld()->addSound($player->getPosition(), new BellRingSound());
            $player->sendTitle(
                TextFormat::BOLD . TextFormat::GOLD . "BREAKFAST TERMINÉ !",
                TextFormat::GRAY . "Merci pour votre participation !",
            );
        }
    }

    public function handleResume(): void
    {
        // TODO: Implement handleResume() method.
    }

    public function getRewards(): array
    {
        return [
            1 => VanillaItems::EMERALD(),
            2 => VanillaItems::DIAMOND(),
            3 => VanillaItems::GOLD_INGOT()
        ];
    }

    public function getName(): string
    {
        return "BreakFast";
    }
}