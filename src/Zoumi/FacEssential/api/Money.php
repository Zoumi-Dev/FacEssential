<?php

namespace Zoumi\FacEssential\api;

use pocketmine\Player;
use Zoumi\FacEssential\Main;

class Money {

    public static function createAccount(Player $player){
        try {

            Main::getInstance()->database->query("INSERT INTO money (pseudo, money) VALUES ('" . $player->getName() . "', '" . Main::getInstance()->manager->get("money-start") . "')");

        }catch (\Exception $exception){

        }
    }

    public static function haveAccount(string $player){
        $result = Main::getInstance()->database->query("SELECT pseudo FROM money WHERE pseudo='" . $player . "'");
        return $result->fetchArray(SQLITE3_ASSOC) ? true : false;
    }

    public static function addMoney(string $player, float $money){
        try {

            $res = Main::getInstance()->database->query("SELECT * FROM money WHERE pseudo='" . $player . "'");

            $row = $res->fetchArray(SQLITE3_ASSOC);

            $calcul = $row['money'] + $row;

            Main::getInstance()->database->query("UPDATE money SET money='" . $calcul . "' WHERE pseudo='" . $player . "'");

            Main::getInstance()->database->close();

        }catch (\Exception $exception){

        }
    }

    public static function removeMoney(string $player, float $money){
        try {

            $res = Main::getInstance()->database->query("SELECT * FROM money WHERE pseudo='" . $player . "'");

            $row = $res->fetchArray(SQLITE3_ASSOC);

            $calcul = $row['money'] - $row;

            Main::getInstance()->database->query("UPDATE money SET money='" . $calcul . "' WHERE pseudo='" . $player . "'");

            Main::getInstance()->database->close();

        }catch (\Exception $exception){

        }
    }

    public static function getMoney(string $player){
        try {

            $res = Main::getInstance()->database->query("SELECT * FROM money WHERE pseudo='" . $player . "'");

            if ($res->numColumns() > 0){
                return $res->fetchArray(SQLITE3_ASSOC)["money"];
            }

        }catch (\Exception $exception){

        }
    }

    public static function getTopMoney($player){
        try{

            $res = Main::getInstance()->database->query("SELECT pseudo,money FROM money ORDER BY money desc LIMIT 10;");
            $ret = [];
            while ($result = $res->fetchArray(SQLITE3_ASSOC)){
                $ret[$result["pseudo"]] = $result["money"];
            }
            $player->sendMessage("§7- §fTop §3Money §7-");
            $top = 1;
            foreach ($ret as $pseudo => $money){
                $player->sendMessage("§f#§7$top §3$pseudo §fwith §b$money §fof money");
                $top++;
            }
            return $ret;
        }catch (\mysqli_sql_exception $mySQLErrorException){

        }
    }

}