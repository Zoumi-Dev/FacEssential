<?php

/*
 * Thanks to Ayzrix for the code.
 */

namespace Zoumi\FacEssential\command\basic;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\CraftingGrid;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use Zoumi\FacEssential\Manager;

class Craft extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.craft")){
                $this->sendCraftingTable($sender);
                $sender->setCraftingGrid(new CraftingGrid($sender, CraftingGrid::SIZE_BIG));
                if (!array_key_exists($windowId = Player::HARDCODED_CRAFTING_GRID_WINDOW_ID, $sender->openHardcodedWindows)) {
                    $pk = new ContainerOpenPacket();
                    $pk->windowId = $windowId;
                    $pk->type = WindowTypes::WORKBENCH;
                    $pk->x = $sender->getFloorX();
                    $pk->y = $sender->getFloorY() - 2;
                    $pk->z = $sender->getFloorZ();
                    $sender->sendDataPacket($pk);
                    $sender->openHardcodedWindows[$windowId] = true;
                    return;
                }
            }else{
                $sender->sendMessage(Manager::PREFIX . "§4You do not have permission to use this command.");
                return;
            }
        }
    }

    /**
     * @param Player $player
     */
    public function sendCraftingTable(Player $player)
    {
        $block = Block::get(Block::CRAFTING_TABLE);
        $block->x = (int)floor($player->x);
        $block->y = (int)floor($player->y) - 2;
        $block->z = (int)floor($player->z);
        $block->level = $player->getLevel();
        $block->level->sendBlocks([$player], [$block]);
    }

}