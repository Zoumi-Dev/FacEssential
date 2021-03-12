<?php

namespace Zoumi\FacEssential\command\teleport;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class TPAccept extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!isset(Main::getInstance()->teleport[$sender->getName()]) or time() >= Main::getInstance()->teleport[$sender->getName()]["timeLeft"]){
                $sender->sendMessage(Manager::PREFIX . "You have no request for teleportation.");
                return;
            }else{
                if (Main::getInstance()->teleport[$sender->getName()]["isHere"] === true){
                    $owner = Server::getInstance()->getPlayer(Main::getInstance()->teleport[$sender->getName()]["owner"]);
                    if ($owner instanceof Player){
                        if (Main::getInstance()->manager->get("teleport")["immune-players"]) {
                            Main::getInstance()->immune[$owner->getName()] = time() + Main::getInstance()->manager->get("teleport")["immune-time"];
                            Main::getInstance()->immune[$sender->getName()] = time() + Main::getInstance()->manager->get("teleport")["immune-time"];
                        }
                        $sender->teleport($owner->getPosition());
                        $owner->sendMessage(Manager::PREFIX . "§e" . $sender->getName() . " §fhas accepted your request for teleportation.");
                        $sender->sendMessage(Manager::PREFIX . "Teleportation accepted! You have been teleported to §e" . $owner->getName() . "§f.");
                        return;
                    }else{
                        $sender->sendMessage(Manager::PREFIX . "The player has disconnected.");
                        return;
                    }
                }
                if (Main::getInstance()->teleport[$sender->getName()]["isHere"] === false){
                    $owner = Server::getInstance()->getPlayer(Main::getInstance()->teleport[$sender->getName()]["owner"]);
                    if ($owner instanceof Player){
                        if (Main::getInstance()->manager->get("teleport")["immune-players"]) {
                            Main::getInstance()->immune[$owner->getName()] = time() + Main::getInstance()->manager->get("teleport")["immune-time"];
                            Main::getInstance()->immune[$sender->getName()] = time() + Main::getInstance()->manager->get("teleport")["immune-time"];
                        }
                        $owner->teleport($sender->getPosition());
                        $owner->sendMessage(Manager::PREFIX . "§e" . $sender->getName() . " §fhas accepted your request for teleportation.");
                        $sender->sendMessage(Manager::PREFIX . "Teleportation accepted! §e" . $owner->getName() . " §fhas just been teleported to you..");
                        return;
                    }else{
                        $sender->sendMessage(Manager::PREFIX . "The player has disconnected.");
                        return;
                    }
                }
            }
        }
    }

}