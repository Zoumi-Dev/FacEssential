<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\FacEssential\Manager;

class Money extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!isset($args[0])){
                $sender->sendMessage(Manager::PREFIX . "You have §e" . \Zoumi\FacEssential\api\Money::getMoney($sender->getName()) . " §ffrom money.");
                return;
            }else{
                if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                    $sender->sendMessage(Manager::PREFIX . "The player §e" . $args[0] . " §fhas §e" . \Zoumi\FacEssential\api\Money::getMoney($args[0]) . " §fmoney.");
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX . "This player is not in the database.");
                    return;
                }
            }
        }else{
            if (!isset($args[0])){
                $sender->sendMessage(Manager::PREFIX . "Please do /money [player].");
                return;
            }else{
                if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                    $sender->sendMessage(Manager::PREFIX . "The player §e" . $args[0] . " §fhas §e" . \Zoumi\FacEssential\api\Money::getMoney($args[0]) . " §fmoney.");
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX . "This player is not in the database.");
                    return;
                }
            }
        }
    }

}