<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class ListRank extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FEPlayer) {
            if ($sender->hasPermission("use.list.rank")) {
                $config = Main::getInstance()->rank->getAll();
                $ranks = [];
                foreach ($config as $rank => $permission) {
                    $ranks[] = $rank;
                }
                if (empty($ranks)) {
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("list-rank"));
                    $sender->sendMessage(Main::getInstance()->lang->get("none"));
                }else {
                    $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("list-rank"));
                    $ranks = implode("§f, §e", $ranks);
                    $sender->sendMessage("§e" . $ranks . "§f.");
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . Main::getInstance()->lang->get("not-perm"));
                return;
            }
        }
    }

}