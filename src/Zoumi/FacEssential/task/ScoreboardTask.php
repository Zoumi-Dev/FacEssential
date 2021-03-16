<?php

namespace Zoumi\FacEssential\task;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use Zoumi\FacEssential\api\Money;
use Zoumi\FacEssential\Main;

class ScoreboardTask extends Task {

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function onRun(int $currentTick)
    {
        if ($this->player->isOnline()){
            $config = Main::getInstance()->manager;
            $line_actus = 0;
            foreach ($config->get("scoreboard")["lines"] as $line){
                Main::getInstance()->scoreboard[$this->player->getName()]
                ->setLine($line_actus, str_replace(["{money}", "{rank}"], [Money::getMoney($this->player->getName()), Main::getInstance()->dataPlayers[$this->player->getName()]["rank"]], $line))->set();
                $line_actus++;
            }
        }else{
            Main::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            unset(Main::getInstance()->scoreboard[$this->player->getName()]);
        }
    }

}