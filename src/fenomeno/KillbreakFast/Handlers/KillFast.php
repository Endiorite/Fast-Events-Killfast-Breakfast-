<?php

namespace fenomeno\KillbreakFast\Handlers;

use pocketmine\item\GoatHornType;
use pocketmine\item\VanillaItems;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\BlazeShootSound;
use pocketmine\world\sound\GoatHornSound;

class KillFast extends FastEventHandler
{

    public function handleStart(): void
    {
        $server = Server::getInstance();

        // Son de corne de chèvre pour tous les joueurs
        foreach ($server->getOnlinePlayers() as $player) {
            $player->getWorld()->addSound($player->getPosition(), new GoatHornSound(GoatHornType::CALL));
            $player->sendTitle(
                TextFormat::BOLD . TextFormat::RED . "KILLFAST COMMENCE !",
                TextFormat::GRAY . "Tuez un maximum de joueurs en 15 minutes !",
            );
        }

        $server->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . "⚔ KILLFAST EVENT ⚔" . TextFormat::RESET);
        $server->broadcastMessage(TextFormat::GRAY . "L'événement a commencé ! Chaque kill rapporte 1 point !");
        $server->broadcastMessage(TextFormat::GREEN . "🏆 Soyez dans le top 3 pour gagner une récompense !");
    }

    public function handleStop(): void
    {
        $server = Server::getInstance();
        $topPlayers = $this->getTop();

        $server->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . "⚔ FIN DU KILLFAST ⚔");
        $server->broadcastMessage(TextFormat::GRAY . "L'événement est terminé !");

        $server->broadcastMessage(TextFormat::BOLD . TextFormat::YELLOW . "🏆 CLASSEMENT FINAL :");
        $position = 1;
        foreach ($topPlayers as $playerName => $points) {
            $server->broadcastMessage(
                TextFormat::GOLD . "#{$position} " . TextFormat::AQUA . $playerName . TextFormat::GRAY . " - " .
                TextFormat::GREEN . "{$points} points"
            );
            $position++;
        }


        foreach ($server->getOnlinePlayers() as $player) {
            $player->getWorld()->addSound($player->getPosition(), new BlazeShootSound());
            $player->sendTitle(
                TextFormat::BOLD . TextFormat::RED . "KILLFAST TERMINÉ !",
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
            1 => VanillaItems::NETHERITE_SWORD(),
            2 => VanillaItems::DIAMOND_SWORD(),
            3 => VanillaItems::GOLDEN_SWORD()
        ];
    }

    public function getName(): string
    {
        return "KillFast";
    }
}