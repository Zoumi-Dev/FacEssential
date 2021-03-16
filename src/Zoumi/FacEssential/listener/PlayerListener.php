<?php

namespace Zoumi\FacEssential\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use Zoumi\FacEssential\api\Chat;
use Zoumi\FacEssential\api\Home;
use Zoumi\FacEssential\api\Money;
use Zoumi\FacEssential\api\Scoreboard;
use Zoumi\FacEssential\FEPlayer;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;
use Zoumi\FacEssential\task\ScoreboardTask;

class PlayerListener implements Listener {

    public function onPlayerCreate(PlayerCreationEvent $event){
        $event->setPlayerClass(FEPlayer::class);
    }

    public function onPreLogin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        if (Main::getInstance()->manager->get("enable-money")){
            if (!Money::haveAccount($player->getName())) {
                Money::createAccount($player);
            }
        }
        if (Chat::getDefaultRank() !== "none"){
            if (Chat::existsPlayer($player->getName())){
                return;
            }
            Chat::setRankOfPlayer($player, Chat::getDefaultRank());
        }
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $event->setJoinMessage(null);
        if ($player instanceof FEPlayer) {
            if (!$player->hasPlayedBefore()) {
                FunctionListener::sendBroadcastByFormat("message", str_replace("{player}", $player->getName(), Main::getInstance()->manager->get("connect-disconnect")["first-connect-message"]));
            }
            /* CACHE */
            Main::getInstance()->dataPlayers[$player->getName()] = [
                "rank" => Chat::getRankOfPlayer($player)
            ];

            /* RANK */
            if (Chat::getRankOfPlayer($player) !== "none") {
                $permissions = Main::getInstance()->rank->get(Chat::getRankOfPlayer($player))["permissions"];
                $player->addPerms($permissions);
            }
            /* SCOREBOARD */
            if (Main::getInstance()->manager->get("scoreboard")["enable"]) {
                $scoreboard = Main::getInstance()->scoreboard[$player->getName()] = new Scoreboard($player);
                $config = Main::getInstance()->manager;
                $line_actus = 0;
                foreach ($config->get("scoreboard")["lines"] as $line) {
                    $scoreboard
                        ->setLine($line_actus, str_replace(["{money}", "{rank}"], [Money::getMoney($player->getName()), Main::getInstance()->dataPlayers[$player->getName()]["rank"]], $line))->set();
                    $line_actus++;
                }
                Main::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($player), $config->get("scoreboard")["update-tick"]);
            }
            FunctionListener::sendBroadcastByFormat(Main::getInstance()->manager->get("connect-disconnect")["format"], str_replace("{player}", $player->getName(), Main::getInstance()->manager->get("connect-disconnect")["connect-message"]));
        }
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

    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        if (!Main::getInstance()->dataPlayers[$player->getName()]["rank"] !== "none"){
            $event->setFormat(str_replace(["{rank}", "{name}", "{displayName}", "{msg}"], [Main::getInstance()->dataPlayers[$player->getName()]["rank"], $player->getName(), $player->getDisplayName(), $event->getMessage()], Chat::getFormat(Main::getInstance()->dataPlayers[$player->getName()]["rank"])));
        }
    }

}