<?php

namespace Zoumi\FacEssential;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Zoumi\FacEssential\command\basic\Feed;
use Zoumi\FacEssential\command\basic\Heal;
use Zoumi\FacEssential\command\teleport\TPA;
use Zoumi\FacEssential\command\teleport\TPAccept;
use Zoumi\FacEssential\command\teleport\TPAHere;
use Zoumi\FacEssential\command\teleport\TPDeny;
use Zoumi\FacEssential\listener\EntityListener;
use Zoumi\FacEssential\listener\PlayerListener;

class Main extends PluginBase implements Listener {

    /* STATIC */
    /** @var static $instance */
    public static $instance;

    /* ARRAY */
    /** @var array $combatLogger */
    public array $combatLogger = [];
    /** @var array $teleport */
    public array $teleport = [];
    /** @var array $immune */
    public array $immune = [];

    /* CONFIG */
    public Config $manager;

    public static function getInstance(): self{
        return self::$instance;
    }

    public function onEnable()
    {
        $this->getLogger()->info("est activer.");
        self::$instance = $this;

        /* Commande */
        $this->getServer()->getCommandMap()->registerAll("FacEssential", [
            /* TELEPORT */
            new TPA("tpa", "Allows you to teleport to a player.", "/tpa", []),
            new TPAHere("tpahere", "Allows to teleport to you.", "tpahere", []),
            new TPAccept("tpaccept", "Allows you to accept a teleportation request.", "/tpaccept", []),
            new TPDeny("tpdeny", "Allows you to refuse a teleportation request.", "/tpdeny", []),

            /* BASIC */
            new Feed("feed", "Allows you to feed a player or yourself.", "/feed", []),
            new Heal("heal", "Allows you to heal a player or yourself.", "/heal", [])
        ]);

        /* Events */
        $this->getServer()->getPluginManager()->registerEvents(new EntityListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);

        /* Task */

        /* CONFIG */
        $this->manager = new Config($this->getDataFolder() . "manager.yml", Config::YAML);

        /* SETUP */
        $this->setupFile();
        $this->unloadCommand();
    }

    public function convert($time){
        if($time >= 60){
            $m = $time / 60;
            $mins = floor($m);
            $s = $m - $mins;
            $secs = floor($s * 60);
            if($mins >= 60){
                $h = $mins / 60;
                $hrs = floor($h);
                $m = $h - $hrs;
                $mins = floor($m * 60);
                return "§e" . $hrs . " §fhour(s) §e" . $mins . " §fminute(s)§e " . $secs . " §fsecond(s)";
            } else {
                return  "§e" . $mins . " §fminute(s)";
            }
        } else {
            return "§e" . $time . " second(s)";
        }
    }

    public function setupFile(): void{
        if (!file_exists($this->getDataFolder() . "manager.yml")){
            $this->saveResource("manager.yml", true);
        }
    }

    public function unloadCommand(): void {
        $cmd = Main::getInstance()->manager->getNested("disable-command");

        if (empty($cmd)){
            return;
        }

        $map = $this->getServer()->getCommandMap();

        foreach ($cmd as $command) {
            try {
                if ($map->getCommand($command)) {
                    $map->unregister($map->getCommand($command));
                    Main::getInstance()->getLogger()->info("The §4" . $command . " §fcommand has been unload");
                } else {
                    throw new CommandErrorException("Command $command not found.");
                }
            }catch (CommandErrorException $exception){
                throw new CommandErrorException("Command $command not found.");
            }
        }
    }

}