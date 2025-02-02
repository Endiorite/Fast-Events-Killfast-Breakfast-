<?php
namespace fenomeno\KillbreakFast;

use fenomeno\KillbreakFast\Commands\FastEventCommand;
use fenomeno\KillbreakFast\Handlers\BreakFast;
use fenomeno\KillbreakFast\Handlers\KillFast;
use fenomeno\KillbreakFast\libs\xenialdan\apibossbar\API;
use fenomeno\KillbreakFast\Listener\FastListener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase
{

    private KillFast  $killFast;
    private BreakFast $breakFast;

    protected function onEnable(): void
    {
        API::load($this);

        $this->killFast = new KillFast();
        $this->breakFast = new BreakFast();

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (){
            $this->killFast->tick();
            $this->breakFast->tick();
        }), 20);

        $this->getServer()->getPluginManager()->registerEvents(new FastListener($this), $this);
        $this->getServer()->getCommandMap()->register('killbreak', new FastEventCommand($this, $this->killFast, 'killfast', "Gérer le killfast event"));
        $this->getServer()->getCommandMap()->register('killbreak', new FastEventCommand($this, $this->breakFast, 'breakfast', "Gérer le breakfast event"));
    }

    public function getKillFast(): KillFast
    {
        return $this->killFast;
    }

    public function getBreakFast(): BreakFast
    {
        return $this->breakFast;
    }

}