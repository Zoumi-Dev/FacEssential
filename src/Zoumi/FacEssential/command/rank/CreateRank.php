<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class CreateRank extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer) {
            if ($sender->hasPermission("use.create.rank")) {
                if (!isset($args[0])) {
                    $sender->sendMessage(Manager::PREFIX . str_replace("{command}", "/createrank [name of rank]", Main::getInstance()->lang->get("please-do")));
                    return;
                } else {
                    if (strlen($args[0]) > 15) {
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("rank-name-lenght"));
                        return;
                    }
                    if (Chat::existsRank($args[0])) {
                        $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("rank-already-exist"));
                        return;
                    }
                    Chat::createRank($args[0]);
                    $sender->sendMessage(Manager::PREFIX . str_replace("{rank}", $args[0], Main::getInstance()->lang->get("succes-create-rank")));
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }

}