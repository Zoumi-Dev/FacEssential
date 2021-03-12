<?php

namespace Zoumi\FacEssential\command\teleport;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class TPDeny extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!!isset(Main::getInstance()->teleport[$sender->getName()]) or time() >= Main::getInstance()->teleport[$sender->getName()]["timeLeft"]){
                $sender->sendMessage(Manager::PREFIX . "You have no request for teleportation.");
                return;
            }else {
                unset(Main::getInstance()->teleport[$sender->getName()]);
                $sender->sendMessage(Manager::PREFIX . "You did refuse the teleportation request.");
                return;
            }
        }
    }

}