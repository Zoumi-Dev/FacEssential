<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class SetDefault extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer){
            if ($sender->hasPermission("use.set.default")){
                if (!isset($args[0])){
                    $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/setdefault [rank]", Main::getInstance()->lang->get("please-do")));
                    return;
                }
                if (!Chat::existsRank($args[0])){
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("rank-not-exist"));
                    return;
                }
                if (Chat::getDefaultRank() !== "none"){
                    if ($args[0] === Chat::getDefaultRank()){
                        Chat::setDefault($args[0]);
                        $sender->sendMessage(Manager::PREFIX . str_replace("{rank}", $args[0], Main::getInstance()->lang->get("succes-remove-default")));
                        return;
                    }else{
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("already-rank-default"));
                        return;
                    }
                }
                Chat::setDefault($args[0]);
                $sender->sendMessage(Manager::PREFIX . str_replace("{rank}", $args[0], Main::getInstance()->lang->get("succed-set-default")));
                return;
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }


}