<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Manager;

class SetMoney extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.set.money")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX . "Please do /setmoney [player] [money].");
                    return;
                }else{
                    if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                        if ($args[1] >= 0){
                            \Zoumi\FacEssential\api\Money::setMoney($args[0], $args[1]);
                            $sender->sendMessage(Manager::PREFIX . "You have defined the money of the player §e" . $args[0] . " §fat §e" . $args[1] . "§f.");
                            $target = Server::getInstance()->getPlayer($args[0]);
                            if ($target instanceof Player){
                                $target->sendMessage(Manager::PREFIX . "The player §e" . $sender->getName() . " §fhas put your money at §e" . $args[1] . "§f.");
                            }
                        }else{
                            $sender->sendMessage(Manager::PREFIX . "The money argument must be greater than or equal to 0.");
                            return;
                        }
                    }else{
                        $sender->sendMessage(Manager::PREFIX . "This player is not in the database.");
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