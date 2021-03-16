<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
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
                    $sender->sendMessage(Manager::PREFIX . "Please do /removeperm [name of rank] [permission].");
                    return;
                }else{
                    if (!Chat::existsRank($args[0])){
                        $sender->sendMessage(Manager::PREFIX . "This rank does not exist.");
                        return;
                    }
                    if (!Chat::existsPermission($args[0], $args[1])){
                        $sender->sendMessage(Manager::PREFIX . "This permission does not exist on this rank.");
                        return;
                    }
                    Chat::removePermission($args[0], $args[1]);
                    $sender->sendMessage(Manager::PREFIX . "You have removed the §e" . $args[1] . " §fpermission on the §e" . $args[0] . " §frank.");
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . "§4You do not have permission to use this command.");
                return;
            }
        }
    }

}