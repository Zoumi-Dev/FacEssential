<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class SetRank extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer){
            if ($sender->hasPermission("use.set.rank")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX . "Please do /setrank [player] [rank].");
                    return;
                }else{
                    $target = Server::getInstance()->getPlayer($args[0]);
                    if ($target instanceof FEPlayer){
                        if (!Chat::existsRank($args[1])){
                            $sender->sendMessage(Manager::PREFIX . "This rank does not exist, please do /listrank to see the list of available ranks");
                            return;
                        }
                        Chat::setRankOfPlayer($target, $args[1]);
                        if (Chat::existsPlayer($target)) {
                            $target->delPerms(Main::getInstance()->rank->get($args[1])["permissions"]);
                        }
                        Main::getInstance()->dataPlayers[$target->getName()]["rank"] = $args[1];
                        $target->addPerms(Main::getInstance()->rank->get($args[1])["permissions"]);
                        $sender->sendMessage(Manager::PREFIX . "The player §e" . $target->getName() . " §fnow has the §e" . $args[1] . " §frank.");
                        $target->sendMessage(Manager::PREFIX . "§e" . $sender->getName() . " §fhas set your rank to §e" . $args[1] . "§f.");
                    }else{
                        $sender->sendMessage(Manager::PREFIX . "This player is not online.");
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