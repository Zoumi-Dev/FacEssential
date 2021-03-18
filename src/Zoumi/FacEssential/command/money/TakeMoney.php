<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class TakeMoney extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!isset($args[1])){
                $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/takemoney [player] [money]", Main::getInstance()->lang->get("please-do")));
                return;
            }else{
                if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                    if (!is_float($args[1]) or !is_int($args[1])){
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-number-or-decimal"));
                        return;
                    }
                    if (\Zoumi\FacEssential\api\Money::getMoney($sender->getName()) >= $args[1]){
                        if ($args[0] === $sender->getName()){
                            $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("yourself-money"));
                            return;
                        }
                        \Zoumi\FacEssential\api\Money::removeMoney($sender->getName(), $args[1]);
                        \Zoumi\FacEssential\api\Money::addMoney($args[0], $args[1]);
                        $sender->sendMessage(Manager::PREFIX . "You just sent §e" . $args[1] . " §fof money to the player §e" . $args[0] . "§f.");
                        $target = Server::getInstance()->getPlayer($args[0]);
                        if ($target instanceof Player){
                            $target->sendMessage(Manager::PREFIX . "You have just received §e" . $args[1] . " §fmoney from §e" . $sender->getName() . ".");
                        }
                        return;
                    }else{
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-have-money"));
                        return;
                    }
                }else{
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-in-database"));
                    return;
                }
            }
        }
    }

}