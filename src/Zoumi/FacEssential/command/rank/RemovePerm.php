<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class RemovePerm extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer){
            if ($sender->hasPermission("use.remove.perm")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/removeperm [rank] [permission]", Main::getInstance()->lang->get("please-do")));
                    return;
                }else{
                    if (!Chat::existsRank($args[0])){
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("rank-not-exist"));
                        return;
                    }
                    if (!Chat::existsPermission($args[0], $args[1])){
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("permission-not-exist"));
                        return;
                    }
                    Chat::removePermission($args[0], $args[1]);
                    $sender->sendMessage(Manager::PREFIX . str_replace(["{rank}", "{permission}"], [$args[0], $args[1]], Main::getInstance()->lang->get("succes-remove-perm")));
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }

}