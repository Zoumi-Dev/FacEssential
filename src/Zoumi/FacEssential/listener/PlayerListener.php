<?php

namespace Zoumi\FacEssential\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use Zoumi\FacEssential\api\Money;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;

class PlayerListener implements Listener {

    public function onPreLogin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        if (Main::getInstance()->manager->get("enable-money")){
            if (!Money::haveAccount($player)) {
                Money::createAccount($player);
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $event->setJoinMessage(null);
        if (!$player->hasPlayedBefore()){
            FunctionListener::sendBroadcastByFormat("message", str_replace("{player}", $player->getName(), Main::getInstance()->manager->get("connect-disconnect")["first-connect-message"]));
        }
        FunctionListener::sendBroadcastByFormat(Main::getInstance()->manager->get("connect-disconnect")["format"], str_replace("{player}", $player->getName(), Main::getInstance()->manager->get("connect-disconnect")["connect-message"]));
    }

    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $event->setQuitMessage(null);
        if (isset(Main::getInstance()->combatLogger[$player->getName()])){
            $player->kill();
            unset(Main::getInstance()->combatLogger[$player->getName()]);
        }
        FunctionListener::sendBroadcastByFormat(Main::getInstance()->manager->get("connect-disconnect")["format"], str_replace("{player}", $player->getName(), Main::getInstance()->manager->get("connect-disconnect")["disconnect-message"]));
    }

    public function onCommandPreProcess(PlayerCommandPreprocessEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if (isset(Main::getInstance()->combatLogger[$player->getName()])) {
            if (strpos($message, "/") === 0 or strpos($message, "/") === 1) {
                if ($event->isCancelled()) return;
                $event->setCancelled(true);
                $player->sendMessage(Manager::PREFIX . "The commands are disabled in combat.");
                return;
            }
        }
    }

}