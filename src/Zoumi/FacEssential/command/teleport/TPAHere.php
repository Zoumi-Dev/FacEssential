<?php

namespace Zoumi\FacEssential\command\teleport;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class TPAHere extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!isset($args[0])){
                $sender->sendMessage(Manager::PREFIX . "You must do /tpahere [player].");
                return;
            }else{
                $target = Server::getInstance()->getPlayer($args[0]);
                if ($target instanceof Player){
                    Main::getInstance()->teleport[$target->getName()] = [
                        "owner" => $sender->getName(),
                        "timeLeft" => time() + 30,
                        "isHere" => true
                    ];
                    $sender->sendMessage(Manager::PREFIX . "Teleportation request sent.");
                    $target->sendMessage(Manager::PREFIX . "§e" . $sender->getName() . " §fwants you to teleport to it.\nDo §e/tpaccept §fto accept or §e/tpdeny §fto refuse.");
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX . "Cannot find player.");
                    return;
                }
            }
        }
    }
}