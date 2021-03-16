<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class RemoveRank extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer){
            if ($sender->hasPermission("use.remove.rank")){
                if (!isset($args[0])){
                    $sender->sendMessage(Manager::PREFIX . "Please do /removerank [name of rank].");
                    return;
                }else{
                    if (!Chat::existsRank($args[0])){
                        $sender->sendMessage(Manager::PREFIX . "This rank does not exist.");
                        return;
                    }
                    Chat::removeRank($args[0]);
                    Chat::updateAll($args[0]);
                    $sender->sendMessage(Manager::PREFIX . "The rank §e" . $args[0] . " §fhas been removed.");
                    return;
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . "§4You do not have permission to use this command.");
                return;
            }
        }
    }

}