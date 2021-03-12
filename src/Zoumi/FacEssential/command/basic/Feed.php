<?php

namespace Zoumi\FacEssential\command\basic;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Manager;

class Feed extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.feed")) {
                if (!isset($args[0])) {
                    if ($sender->getFood() >= 20) {
                        $sender->sendMessage(Manager::PREFIX . "Your food bar is already full.");
                        return;
                    }
                    $sender->setFood(20);
                    $sender->sendMessage(Manager::PREFIX . "Your food bar is now full.");
                    return;
                }else{
                    $target = Server::getInstance()->getPlayer($args[0]);
                    if ($target instanceof Player){
                        if ($target->getFood() >= 20){
                            $sender->sendMessage(Manager::PREFIX . "His food bar is already full.");
                            return;
                        }
                        $target->setFood(20);
                        $sender->sendMessage(Manager::PREFIX . "His food bar is now full.");
                        $target->sendMessage(Manager::PREFIX . "§e" . $sender->getName() . " §fhas fed you.");
                        return;
                    }else{
                        $sender->sendMessage(Manager::PREFIX . "This player is not online.");
                        return;
                    }
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . "§4You do not have permission to use this command.");
                return;
            }
        }
    }

}