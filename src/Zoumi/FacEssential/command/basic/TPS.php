<?php

namespace Zoumi\FacEssential\command\basic;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\Manager;
use pocketmine\Server;

class TPS extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $s = Server::getInstance()->getTicksPerSecond();

        if ($sender instanceof Player){
            $p = $sender->getPing();
            $sender->sendMessage(Manager::PREFIX . "Your ping: {$p}");
            $sender->sendMessage(Manager::PREFIX . "Server TPS: {$s}");
        }else{
            if(isset($args[0])){
                $player = Server::getInstance()->getPlayer($args[0]);
                if($player instanceof Player){
                    $sender->sendMessage(Manager::PREFIX . "{$player->getName()}'s Ping: {$player->getPing()}");
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . "No player name specified");
            }
            
            $sender->sendMessage(Manager::PREFIX . "Server TPS: {$s}");
        }
    }

}
