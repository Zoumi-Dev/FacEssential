<?php

namespace Zoumi\FacEssential\command\basic;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\level\Position;
use pocketmine\Player;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class Top extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.top")){
                $get_top = $sender->getLevel()->getHighestBlockAt($sender->getX(), $sender->getZ());
                $sender->teleport(new Position($sender->getX(), $get_top + 4, $sender->getZ(), $sender->getLevel()));
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("surface-succeded"));
                return;
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }

}