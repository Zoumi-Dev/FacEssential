<?php

namespace Zoumi\FacEssential\command\rank;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\FEPlayer;
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
                    $sender->sendMessage(Manager::PREFIX . "Please do /createrank [name of rank].");
                    return;
                } else {
                    if (strlen($args[0]) > 15) {
                        $sender->sendMessage(Manager::PREFIX . "The rank argument is too long! Maximum 15 characters.");
                        return;
                    }
                    if (Chat::existsRank($args[0])) {
                        $sender->sendMessage(Manager::PREFIX . "This rank already exists.");
                        return;
                    }
                    Chat::createRank($args[0]);
                    $sender->sendMessage(Manager::PREFIX . "The §e" . $args[0] . " §frank has been successfully created, to add a format do /setformat to add a permission do /setperm.");
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . "§4You do not have permission to use this command.");
                return;
            }
        }
    }

}