<?php

namespace Zoumi\FacEssential\command\basic;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class Heal extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.feed")) {
                if (!isset($args[0])) {
                    if ($sender->getHealth() >= 20) {
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("full-heal"));
                        return;
                    }
                    $sender->setHealth($sender->getMaxHealth());
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("now-full-heal"));
                    return;
                }else{
                    $target = Server::getInstance()->getPlayer($args[0]);
                    if ($target instanceof Player){
                        if ($target->getHealth() >= 20){
                            $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("player-full-heal"));
                            return;
                        }
                        $target->setHealth($sender->getMaxHealth());
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("player-now-full-heal"));
                        $target->sendMessage(Manager::PREFIX . str_replace("{player}", $sender->getName(), Main::getInstance()->lang->get("player-heal-you")));
                        return;
                    }else{
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("player-not-online"));
                        return;
                    }
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }

}