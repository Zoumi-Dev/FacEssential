<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
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
                    $sender->sendMessage(Manager::PREFIX . "Please do /setdefault [name of rank].");
                    return;
                }
                if (!Chat::existsRank($args[0])){
                    $sender->sendMessage(Manager::PREFIX . "This rank does not exist.");
                    return;
                }
                if (Chat::getDefaultRank() !== "none"){
                    if ($args[0] === Chat::getDefaultRank()){
                        Chat::setDefault($args[0]);
                        $sender->sendMessage(Manager::PREFIX . "You have removed the default §e" . $args[0] . " §frank.");
                        return;
                    }else{
                        $sender->sendMessage(Manager::PREFIX . "A rank is already in default do /setdefault [default rank] to remove it.");
                        return;
                    }
                }
                Chat::setDefault($args[0]);
                $sender->sendMessage(Manager::PREFIX . "The §e" . $args[0] . " §frank is now set as default.");
                return;
            }else{
                $sender->sendMessage(Manager::PREFIX . "§4You do not have permission to use this command.");
                return;
            }
        }
    }


}