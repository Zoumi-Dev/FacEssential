<?php

namespace Zoumi\FacEssential\api;

use Ayzrix\SimpleFaction\API\FactionsAPI;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\SQLiteTask;

class Money {

    public static function createAccount(Player $player){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new SQLiteTask("INSERT INTO money (pseudo, money) VALUES ('" . $player->getName() . "', '" . Main::getInstance()->manager->get("money-start") . "')"));

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

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new SQLiteTask("UPDATE money SET money='" . $calcul . "' WHERE pseudo='" . $player . "'"));

            Main::getInstance()->database->close();

        }catch (\Exception $exception){

        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof FEPlayer){
            if (Main::getInstance()->manager->get("scoreboard")["enable"]) {
                $scoreboard = Main::getInstance()->scoreboard[$player->getName()];
                $config = Main::getInstance()->manager;
                $line_actus = 0;
                if (Main::getInstance()->manager->get("faction-system") === true) {
                    if (FactionsAPI::isInFaction($player)) {
                        $faction = FactionsAPI::getFaction($player);
                        $factionRank = FactionsAPI::getRank($player->getName());
                        $factionPower = FactionsAPI::getPower($faction);
                        $factionBank = FactionsAPI::getMoney($faction);
                    } else {
                        $faction = "...";
                        $factionRank = "...";
                        $factionPower = "...";
                        $factionBank = "...";
                    }

                    foreach ($config->get("scoreboard")["lines"] as $line) {
                        $scoreboard
                            ->setLine($line_actus, str_replace(["{money}", "{rank}", "{faction_name}", "{faction_rank}", "{faction_power}", "{faction_bank}"], [Money::getMoney($player->getName()), Main::getInstance()->dataPlayers[$player->getName()]["rank"], $faction, $factionRank, $factionPower, $factionBank], $line))->set();
                        $line_actus++;
                    }
                } else {
                    foreach ($config->get("scoreboard")["lines"] as $line) {
                        $scoreboard
                            ->setLine($line_actus, str_replace(["{money}", "{rank}"], [Money::getMoney($player->getName()), Main::getInstance()->dataPlayers[$player->getName()]["rank"]], $line))->set();
                        $line_actus++;
                    }
                }
            }
        }
    }

    public static function removeMoney(string $player, float $money)
    {
        try {

            $res = Main::getInstance()->database->query("SELECT * FROM money WHERE pseudo='" . $player . "'");

            $row = $res->fetchArray(SQLITE3_ASSOC);

            $calcul = $row['money'] - $row;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new SQLiteTask("UPDATE money SET money='" . $calcul . "' WHERE pseudo='" . $player . "'"));

            Main::getInstance()->database->close();

        } catch (\Exception $exception) {

        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof FEPlayer) {
            if (Main::getInstance()->manager->get("scoreboard")["enable"]) {
                $scoreboard = Main::getInstance()->scoreboard[$player->getName()];
                $config = Main::getInstance()->manager;
                $line_actus = 0;
                if (Main::getInstance()->manager->get("faction-system") === true) {
                    if (FactionsAPI::isInFaction($player)) {
                        $faction = FactionsAPI::getFaction($player);
                        $factionRank = FactionsAPI::getRank($player->getName());
                        $factionPower = FactionsAPI::getPower($faction);
                        $factionBank = FactionsAPI::getMoney($faction);
                    } else {
                        $faction = "...";
                        $factionRank = "...";
                        $factionPower = "...";
                        $factionBank = "...";
                    }

                    foreach ($config->get("scoreboard")["lines"] as $line) {
                        $scoreboard
                            ->setLine($line_actus, str_replace(["{money}", "{rank}", "{faction_name}", "{faction_rank}", "{faction_power}", "{faction_bank}"], [Money::getMoney($player->getName()), Main::getInstance()->dataPlayers[$player->getName()]["rank"], $faction, $factionRank, $factionPower, $factionBank], $line))->set();
                        $line_actus++;
                    }
                } else {
                    foreach ($config->get("scoreboard")["lines"] as $line) {
                        $scoreboard
                            ->setLine($line_actus, str_replace(["{money}", "{rank}"], [Money::getMoney($player->getName()), Main::getInstance()->dataPlayers[$player->getName()]["rank"]], $line))->set();
                        $line_actus++;
                    }
                }
            }
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

    public static function setMoney(string $player, int $money){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new SQLiteTask("UPDATE money SET money='" . $money . "' WHERE pseudo='" . $player . "'"));

        }catch (\mysqli_sql_exception $exception){

        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof FEPlayer) {
            if (Main::getInstance()->manager->get("scoreboard")["enable"]) {
                $scoreboard = Main::getInstance()->scoreboard[$player->getName()];
                $config = Main::getInstance()->manager;
                $line_actus = 0;
                if (Main::getInstance()->manager->get("faction-system") === true) {
                    if (FactionsAPI::isInFaction($player)) {
                        $faction = FactionsAPI::getFaction($player);
                        $factionRank = FactionsAPI::getRank($player->getName());
                        $factionPower = FactionsAPI::getPower($faction);
                        $factionBank = FactionsAPI::getMoney($faction);
                    } else {
                        $faction = "...";
                        $factionRank = "...";
                        $factionPower = "...";
                        $factionBank = "...";
                    }

                    foreach ($config->get("scoreboard")["lines"] as $line) {
                        $scoreboard
                            ->setLine($line_actus, str_replace(["{money}", "{rank}", "{faction_name}", "{faction_rank}", "{faction_power}", "{faction_bank}"], [Money::getMoney($player->getName()), Main::getInstance()->dataPlayers[$player->getName()]["rank"], $faction, $factionRank, $factionPower, $factionBank], $line))->set();
                        $line_actus++;
                    }
                } else {
                    foreach ($config->get("scoreboard")["lines"] as $line) {
                        $scoreboard
                            ->setLine($line_actus, str_replace(["{money}", "{rank}"], [Money::getMoney($player->getName()), Main::getInstance()->dataPlayers[$player->getName()]["rank"]], $line))->set();
                        $line_actus++;
                    }
                }
            }
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