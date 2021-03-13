<?php

namespace Zoumi\FacEssential\task;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use Zoumi\FacEssential\Main;

class CombatLogger extends Task {

    private Player $damager;

    private Player $entity;

    public function __construct(Player $damager, Player $entity)
    {
        $this->damager = $damager;
        $this->entity = $entity;
    }

    public function onRun(int $currentTick)
    {
        if (!$this->entity->isOnline()){
            Main::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            unset(Main::getInstance()->combatLogger[$this->damager->getName()]);
            unset(Main::getInstance()->combatLogger[$this->entity->getName()]);
        }
        if (Main::getInstance()->manager->get("combat-logger")["display-cooldown-combat-logger"]){
            $this->damager->sendPopup("Time remaining: " . Main::getInstance()->convert(Main::getInstance()->combatLogger[$this->damager->getName()] - time()));
        }
    }

}