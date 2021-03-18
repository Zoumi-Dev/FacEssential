<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class RemoveMoney extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.remove.money")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/removemoney [player] [money]", Main::getInstance()->lang->get("please-do")));
                    return;
                }else{
                    if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                        if (!is_float($args[1]) or !is_int($args[1])){
                            $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-number-or-decimal"));
                            return;
                        }
                        if ($args[1] > 0 or \Zoumi\FacEssential\api\Money::getMoney($args[0]) >= $args[1]){
                            \Zoumi\FacEssential\api\Money::removeMoney($args[0], $args[1]);
                            $sender->sendMessage(Manager::PREFIX . str_replace(["{player}", "{money}"], [$args[0], $args[1]], Main::getInstance()->lang->get("money-receive")));
                            $target = Server::getInstance()->getPlayer($args[0]);
                            if ($target instanceof Player){
                                $sender->sendMessage(Manager::PREFIX . str_replace(["{player}", "{money}"], [$sender->getName(), $args[1]], Main::getInstance()->lang->get("money-remove-receive")));
                            }
                            return;
                        }else{
                            $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-zero-or-player-dosent-have-money"));
                            return;
                        }
                    }else{
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-in-database"));
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