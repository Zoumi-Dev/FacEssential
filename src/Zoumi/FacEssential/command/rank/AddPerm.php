<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Manager;

class AddPerm extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer){
            if ($sender->hasPermission("use.add.perm")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX . "Please do /addperm [name of rank] [permission].");
                    return;
                }else{
                    if (!Chat::existsRank($args[0])){
                        $sender->sendMessage(Manager::PREFIX . "This rank does not exist.");
                        return;
                    }
                    Chat::addPermission($args[0], $args[1]);
                    $sender->sendMessage(Manager::PREFIX . "You have added the §e" . $args[1] . " §fpermission on the §e" . $args[0] . " §frank.");
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . "§4You do not have permission to use this command.");
                return;
            }
        }
    }

}