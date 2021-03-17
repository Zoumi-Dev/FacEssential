<?php

namespace Zoumi\FacEssential\api;

use pocketmine\Player;
use pocketmine\Server;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;

class Chat {

    public static function createRank(string $rankname)
    {
        $config = Main::getInstance()->rank;
        $config->set($rankname, [
            "name" => $rankname,
            "permissions" => [],
            "format" => "§7$rankname §2{name} §7- {msg}",
            "default" => false
        ]);
        $config->save();
    }

    public static function removeRank(string $rankname)
    {
        $config = Main::getInstance()->rank;
        $config->remove($rankname);
        $config->save();
    }

    public static function existsRank(string $rank): bool{
        $config = Main::getInstance()->rank;
        if ($config->exists($rank)){
            return true;
        }
        return false;
    }

    public static function existsPlayer(string $player): bool{
        $result = Main::getInstance()->database->query("SELECT pseudo FROM rank WHERE pseudo='" . $player . "'");
        return $result->fetchArray(SQLITE3_ASSOC) ? true : false;
    }

    public static function setRankOfPlayer(Player $player, string $rank){
        try {

            Main::getInstance()->database->query("INSERT OR REPLACE INTO rank (pseudo, rank) VALUES ('" . $player->getName() . "', '$rank')");

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getRankOfPlayer(Player $player){
        try {

            $res = Main::getInstance()->database->query("SELECT * FROM rank WHERE pseudo='" . $player->getName() . "'");

            $result = $res->fetchArray(SQLITE3_ASSOC);
            if ($res->fetchArray(SQLITE3_ASSOC)){
                return $result["rank"];
            }
        }catch (\mysqli_sql_exception $exception){

        }
        return "none";
    }

    public static function addPermission(string $rank, string $permission){
        $config = Main::getInstance()->rank;
        $perm_actus = $config->get($rank)["permissions"];
        $perm_actus[] = $permission;
        $config->set($rank, [
            "name" => $rank,
            "permissions" => $perm_actus,
            "format" => $config->get($rank)["format"],
            "default" => $config->get($rank)["default"]
        ]);
        $config->save();
    }

    public static function removePermission(string $rank, string $permission){
        $config = Main::getInstance()->rank;
        unset($config->get($rank)["permissions"][$permission]);
        $config->save();
    }

    public static function getFormat(string $rank){
        $config = Main::getInstance()->rank;
        return $config->get($rank)["format"] ?? "<{name}> {msg}";
    }

    public static function getDefaultRank(){
        $config = Main::getInstance()->rank;
        foreach ($config->getAll() as $rank){
            if ($rank["default"] === true){
                return $rank["name"];
            }
        }
        return "none";
    }

    public static function updateAll(string $rankToRemove): void {
        try {

            $res = Main::getInstance()->database->query("SELECT * FROM rank;");

            while ($result = $res->fetchArray(SQLITE3_ASSOC)){
                if ($result["rank"] == $rankToRemove){
                    if (Chat::getDefaultRank() !== "none"){
                        $target = Server::getInstance()->getPlayer($result["pseudo"]);
                        if ($target instanceof FEPlayer){
                            Main::getInstance()->dataPlayers[$target->getName()]["rank"] = Chat::getDefaultRank();
                        }
                        Main::getInstance()->database->query("UPDATE rank SET rank='" . Chat::getDefaultRank() . "' WHERE pseudo='" . $result["pseudo"] . "'");
                    }else{
                        $target = Server::getInstance()->getPlayer($result["pseudo"]);
                        if ($target instanceof FEPlayer){
                            Main::getInstance()->dataPlayers[$target->getName()]["rank"] = "none";
                        }
                        Main::getInstance()->database->query("DELETE FROM rank WHERE pseudo='" . $result["pseudo"] . "'");
                    }
                }
            }


        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function existsPermission(string $rank, string $permission){
        $config = Main::getInstance()->rank;
        if (in_array($permission, $config->get($rank)["permissions"])){
            return true;
        }
        return false;
    }

    public static function setFormat(string $rank, string $format){
        $config = Main::getInstance()->rank;
        $config->set($rank, [
            "name" => $rank,
            "permissions" => $config->get($rank)["permissions"],
            "format" => $format,
            "default" => $config->get($rank)["default"]
        ]);
        $config->save();
    }

    public static function setDefault(string $rank){
        $config = Main::getInstance()->rank;
        if (Chat::getDefaultRank() !== "none"){
            $config->set(Chat::getDefaultRank(), [
                "name" => Chat::getDefaultRank(),
                "permissions" => $config->get(Chat::getDefaultRank())["permissions"],
                "format" => $config->get(Chat::getDefaultRank())["format"],
                "default" => false
            ]);
        }else {
            $config->set($rank, [
                "name" => $rank,
                "permissions" => $config->get($rank)["permissions"],
                "format" => $config->get($rank)["format"],
                "default" => true
            ]);
        }
        $config->save();
    }

}