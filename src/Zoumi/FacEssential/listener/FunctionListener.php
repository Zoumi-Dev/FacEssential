<?php

namespace Zoumi\FacEssential\listener;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

class FunctionListener implements Listener {

    public static function sendBroadcastByFormat(string $format, string $message){
        switch ($format){
            case "popup":
                Server::getInstance()->broadcastPopup($message);
                break;
            case "message":
                Server::getInstance()->broadcastMessage($message);
                break;
            case "tip":
                Server::getInstance()->broadcastTip($message);
                break;
            default:
                Server::getInstance()->broadcastMessage($message);
                break;
        }
    }

}