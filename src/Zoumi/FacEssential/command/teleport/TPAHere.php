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
                $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/tpahere [player]", Main::getInstance()->lang->get("please-do")));
                return;
            }else{
                $target = Server::getInstance()->getPlayer($args[0]);
                if ($target instanceof Player){
                    Main::getInstance()->teleport[$target->getName()] = [
                        "owner" => $sender->getName(),
                        "timeLeft" => time() + 30,
                        "isHere" => true
                    ];
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("succes-request-sent"));
                    $target->sendMessage(Manager::PREFIX . str_replace("{player}", $sender->getName(), Main::getInstance()->lang->get("tpahere-message")));
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("player-not-exist"));
                    return;
                }
            }
        }
    }
}