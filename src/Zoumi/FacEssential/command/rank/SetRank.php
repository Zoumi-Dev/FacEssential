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
                    $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/setrank [player] [rank]", Main::getInstance()->lang->get("please-do")));
                    return;
                }else{
                    $target = Server::getInstance()->getPlayer($args[0]);
                    if ($target instanceof FEPlayer){
                        if (!Chat::existsRank($args[1])){
                            $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("rank-not-exist"));
                            return;
                        }
                        Chat::setRankOfPlayer($target, $args[1]);
                        if (Chat::existsPlayer($target)) {
                            $target->delPerms(Main::getInstance()->rank->get($args[1])["permissions"]);
                        }
                        Main::getInstance()->dataPlayers[$target->getName()]["rank"] = $args[1];
                        $target->addPerms(Main::getInstance()->rank->get($args[1])["permissions"]);
                        $sender->sendMessage(Manager::PREFIX . str_replace(["{rank}", "{player}"], [$args[1], $target->getName()], Main::getInstance()->lang->get("succes-set-rank")));
                        $target->sendMessage(Manager::PREFIX . str_replace(["{player}", "{rank}"], [$sender->getName(), $args[1]], Main::getInstance()->lang->get("player-succes-set-rank")));
                    }else{
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("player-not-exist"));
                        return;
                    }
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }

}