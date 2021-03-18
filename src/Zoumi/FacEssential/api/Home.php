<?php

namespace Zoumi\FacEssential\api;

use pocketmine\Player;
use Zoumi\FacEssential\Main;

class Home {

    public static function getHomeOfPlayer(string $player){
        try {

            $res = Main::getInstance()->database->query("SELECT * FROM home WHERE pseudo='" . $player . "'");

            return $res->fetchArray(SQLITE3_ASSOC) ? $res->fetchArray(SQLITE3_ASSOC) : "None.";

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function addHome(Player $player, string $home){
        try {

            Main::getInstance()->database->query("INSERT INTO home (pseudo, home, x, y, z) VALUES ('" . $player->getName() . "', '" . $home . "', '" . $player->getFloorX() + .5 . "', '" . $player->getY() . "', '" . $player->getFloorZ() + .5 . "')");

        }catch (\mysqli_sql_exception $exception){

        }
    }

}