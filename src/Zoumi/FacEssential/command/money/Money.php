<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\FacEssential\Main;
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
                $sender->sendMessage(Manager::PREFIX . str_replace("{money}", \Zoumi\FacEssential\api\Money::getMoney($sender->getName()), Main::getInstance()->lang->get("current-money")));
                return;
            }else{
                if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                    $sender->sendMessage(Manager::PREFIX . str_replace(["{money}", "{player}"], [\Zoumi\FacEssential\api\Money::getMoney($args[0]), $args[0]], Main::getInstance()->lang->get("player-have-money")));
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-in-database"));
                    return;
                }
            }
        }else{
            if (!isset($args[0])){
                $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/money [player]", Main::getInstance()->lang->get("please-do")));
                return;
            }else{
                if (\Zoumi\FacEssential\api\Money::haveAccount($args[0])){
                    $sender->sendMessage(Manager::PREFIX . str_replace(["{money}", "{player}"], [\Zoumi\FacEssential\api\Money::getMoney($args[0]), $args[0]], Main::getInstance()->lang->get("player-have-money")));
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-in-database"));
                    return;
                }
            }
        }
    }

}