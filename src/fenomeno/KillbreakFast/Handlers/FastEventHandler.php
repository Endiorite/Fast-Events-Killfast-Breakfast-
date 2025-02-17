<?php

namespace fenomeno\KillbreakFast\Handlers;

use endiorite\base\Main;
use endiorite\base\manager\ScoreboardManager;
use fenomeno\KillbreakFast\Events\variants\FastEventEndEvent;
use fenomeno\KillbreakFast\libs\xenialdan\apibossbar\BossBar;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use WeakMap;

abstract class FastEventHandler
{

    private bool $enabled  = false;
    private WeakMap $map;
    private int $timeRemaining = 10;
    private BossBar $bossBar;

    public function __construct()
    {
        $this->map = new WeakMap();
        $this->bossBar = new BossBar();
    }

    public function tick(): void
    {
        foreach ($this->map as $player => $value){
            if(! $player->isOnline()){
                unset($this->map[$player]);
            }
        }

        if($this->enabled){
            $this->timeRemaining--;

            if ($this->timeRemaining <= 0) {
                $ev = new FastEventEndEvent($this);
                $ev->call();
                $this->distributeRewards($ev->getRewards(), $ev->getTop());
                $this->handleStop();
                $this->enabled = false;
                return;
            }

            foreach(Server::getInstance()->getOnlinePlayers() as $player){
                $this->bossBar->addPlayer($player);
                $scoreboard = new ScoreboardManager($player);
                $scoreboard->addScoreboard(Main::SCOREBOARD_NAME, Main::SCOREBOARD_NAME);
                foreach ($this->getScoreboardLines($player) as $i => $line){
                    $scoreboard->removeLine(Main::SCOREBOARD_NAME, $i);
                    $scoreboard->setLine(Main::SCOREBOARD_NAME, $i, $line);
                    var_dump($line);
                }
            }

            $this->bossBar->setTitle(
                TextFormat::BOLD . TextFormat::GOLD . "{$this->getName()} Event" . TextFormat::RESET . TextFormat::EOL .
                TextFormat::GRAY . "Temps restant : " . TextFormat::GREEN . gmdate("i:s", $this->timeRemaining)
            );
            $this->bossBar->setPercentage($this->timeRemaining / 10);

            $this->handleResume();
        } else {
            $this->bossBar->removeAllPlayers();
            $this->timeRemaining = 10;
        }
    }

    public function getPlayerData(Player $player): int
    {
        return $this->map[$player] ?? 0;
    }

    public function setPlayerData(Player $player, int $value): void
    {
        $this->map[$player] = $value;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getTop(): array
    {
        $players = [];
        foreach ($this->map as $player => $score) {
            if(! $player instanceof Player) {
                continue;
            }
            $players[$player->getName()] = $score;
        }
        arsort($players);
        return $players;
    }

    private function distributeRewards(?array $rewards = null, ?array $topPlayers = null): void
    {
        $rewards ??= $this->getRewards();
        $topPlayers ??= $this->getTop();

        $topPlayers = array_slice($topPlayers, 0, 3, true);

        $position = 1;
        foreach ($topPlayers as $playerName => $score) {
            $player = Server::getInstance()->getPlayerExact($playerName);
            if ($player instanceof Player && isset($rewards[$position])) {
                $player->getInventory()->addItem($rewards[$position]);
                $player->sendMessage(TextFormat::GREEN .
                    "Félicitations ! Vous êtes dans le top " . $position .
                    " et avez reçu une récompense !");
            }
            $position++;
        }
    }

    public function getScoreboardLines(Player $player): array
    {
        $topPlayers = $this->getTop();

        $top1 = $topPlayers[0] ?? "Aucun";
        $top2 = $topPlayers[1] ?? "Aucun";
        $top3 = $topPlayers[2] ?? "Aucun";


        return [
            "§l§e{$this->getName()} §r§fEvent",
            "§fTemps restant: §7" . gmdate("i:s", $this->timeRemaining),
            "§a",
            "§l§6Top 3",
            "§f🥇 $top1",
            "§f🥈 $top2",
            "§f🥉 $top3",
            "§b",
            "§fVotre score: §7" . $this->getPlayerData($player),
            "§c",
            "§fplay.endiorite.fr"
        ];
    }

    public function setPlayers(array $players): void
    {
        foreach ($players as $playerName => $value) {
            $player = Server::getInstance()->getPlayerExact($playerName);
            if($player !== null){
                $this->map[$playerName] = $value;
            }
        }
    }

    abstract public function handleStart(): void;

    abstract public function handleStop(): void;

    abstract public function handleResume(): void;

    abstract public function getRewards(): array;

    abstract public function getName(): string;

}