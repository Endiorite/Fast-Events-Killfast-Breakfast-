<?php

namespace fenomeno\KillbreakFast\Listener;

use fenomeno\KillbreakFast\Main;
use pocketmine\block\Crops;
use pocketmine\block\Melon;
use pocketmine\block\Pumpkin;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

readonly class FastListener implements Listener
{

    public function __construct(
        private Main $main
    ){}

    public function onKill(EntityDeathEvent $event) : void {
        if ($this->main->getKillFast()->isEnabled()){
            $victim = $event->getEntity();
            $lastDamageCause = $victim->getLastDamageCause();
            if ($lastDamageCause instanceof EntityDamageByEntityEvent){
                $damager = $lastDamageCause->getDamager();
                if ($damager instanceof Player && $victim instanceof Player){
                    //TODO faire un event lorsqu'un joueur est kill dans l'event
                    $this->main->getKillFast()->setPlayerData($damager, $this->main->getKillFast()->getPlayerData($damager) + 1);
                    $damager->getServer()->broadcastPopup("§7KillFast - {$damager->getDisplayName()} vient de tuer {$victim->getDisplayName()}");
                }
            }
        }
    }

    public function onBreak(BlockBreakEvent $event) : void {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        if ($this->main->getBreakFast()->isEnabled()){
            //TODO AJOUTEZ :
            /**
             * si on casse et repose ça donne pas le point
             * faire un event pour cassage de bloc
             */
            if (($block instanceof Crops && $block->getAge() >= Crops::MAX_AGE) || $block instanceof Melon || $block instanceof Pumpkin){
                $player->getServer()->broadcastPopup("§7BreakFast §f- {$player->getName()} +1");
                $this->main->getBreakFast()->setPlayerData($player, $this->main->getBreakFast()->getPlayerData($player) + 1);
            }
        }
    }

}