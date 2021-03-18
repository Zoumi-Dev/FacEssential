<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class SetFormat extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer){
            if ($sender->hasPermission("use.set.format")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/setformat [name of rank] [format]. {name} = name of player, {msg} = message, {displayName} = display name and {rank} = rank of player", Main::getInstance()->lang->get("please-do")));
                    return;
                }else{
                    if (!Chat::existsRank($args[0])){
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("rank-not-exist"));
                        return;
                    }
                    $rank = array_shift($args);
                    $format = implode(" ", $args);
                    Chat::setFormat($rank, $format);
                    $sender->sendMessage(Manager::PREFIX . str_replace(["{rank}", "{format}"], [$rank, $format], Main::getInstance()->lang->get("succes-set-format")));
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }

}