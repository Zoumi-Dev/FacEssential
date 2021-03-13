<?php

namespace Zoumi\FacEssential\command\money;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class TopMoney extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        \Zoumi\FacEssential\api\Money::getTopMoney($sender);
    }

}