<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Manager;

class AddMoney extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.add.money")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX . "Please do /addmoney [player] [money].");
                    return;
                }else{
                    if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                        if (!is_float($args[1]) or !is_int($args[1])){
                            $sender->sendMessage(Manager::PREFIX . "The money argument must be a whole number or decimal.");
                            return;
                        }
                        if ($args[1] > 0){
                            \Zoumi\FacEssential\api\Money::addMoney($args[0], $args[1]);
                            $sender->sendMessage(Manager::PREFIX . "You just added §e" . $args[1] . " §fof money to the player §e" . $args[0] . "§f.");
                            $target = Server::getInstance()->getPlayer($args[0]);
                            if ($target instanceof Player){
                                $sender->sendMessage(Manager::PREFIX . "Player " . $sender->getName() . " §fadded §e" . $args[1] . " §fof money.");
                            }
                            return;
                        }else{
                            $sender->sendMessage(Manager::PREFIX . "The money argument must be greater than 0.");
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