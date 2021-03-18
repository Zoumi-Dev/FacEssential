<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Main;
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
                    $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/setmoney [player] [money]", Main::getInstance()->lang->get("please-do")));
                    return;
                }else{
                    if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                        if ($args[1] >= 0){
                            \Zoumi\FacEssential\api\Money::setMoney($args[0], $args[1]);
                            $sender->sendMessage(Manager::PREFIX . str_replace(["{player}", "{money}"], [$args[0], $args[1]], Main::getInstance()->lang->get("money-set")));
                            $target = Server::getInstance()->getPlayer($args[0]);
                            if ($target instanceof Player){
                                $target->sendMessage(Manager::PREFIX . str_replace(["{player}", "{money}"], [$sender->getName(), $args[1]], Main::getInstance()->lang->get("money-set")));
                            }
                        }else{
                            $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-number-or-decimal"));
                            return;
                        }
                    }else{
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-in-database"));
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