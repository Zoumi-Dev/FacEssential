<?php

namespace Zoumi\FacEssential;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Zoumi\FacEssential\command\basic\Craft;
use Zoumi\FacEssential\command\basic\EnderChest;
use Zoumi\FacEssential\command\basic\Feed;
use Zoumi\FacEssential\command\basic\Heal;
use Zoumi\FacEssential\command\basic\Top;
use Zoumi\FacEssential\command\money\AddMoney;
use Zoumi\FacEssential\command\money\Money;
use Zoumi\FacEssential\command\money\RemoveMoney;
use Zoumi\FacEssential\command\money\SetMoney;
use Zoumi\FacEssential\command\money\TakeMoney;
use Zoumi\FacEssential\command\money\TopMoney;
use Zoumi\FacEssential\command\rank\AddPerm;
use Zoumi\FacEssential\command\rank\CreateRank;
use Zoumi\FacEssential\command\rank\ListRank;
use Zoumi\FacEssential\command\rank\RemovePerm;
use Zoumi\FacEssential\command\rank\RemoveRank;
use Zoumi\FacEssential\command\rank\SetDefault;
use Zoumi\FacEssential\command\rank\SetFormat;
use Zoumi\FacEssential\command\rank\SetRank;
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
    public $combatLogger = [];
    /** @var array $teleport */
    public $teleport = [];
    /** @var array $immune */
    public $immune = [];
    /** @var array $scoreboard */
    public $scoreboard = [];
    /** @var array $dataPlayers */
    public $dataPlayers = [];

    /* CONFIG */
    /** @var Config $manager */
    public $manager;
    /** @var Config $rank*/
    public $rank;
    /** @var Config $lang*/
    public $lang;

    /* SQL */
    public \SQLite3 $database;

    public static function getInstance(): self{
        return self::$instance;
    }

    public function onEnable()
    {
        $this->getLogger()->info("is enable.");
        self::$instance = $this;

        /* SETUP */
        $this->setupFile();

        /* CONFIG */
        $this->manager = new Config($this->getDataFolder() . "manager.yml", Config::YAML);
        $this->rank = new Config($this->getDataFolder() . "rank.yml", Config::YAML);
        $this->lang = new Config($this->getDataFolder() . "lang.yml", Config::YAML);

        $this->unloadCommand();

        /* Commande */
        if (Main::getInstance()->manager->get("enable-money")){
            $this->getServer()->getCommandMap()->registerAll("FacEssential-Money", [
                new Money("money", "Allows you to see your money or a player's money.", "/money", []),
                new TakeMoney("takemoney", "Allows you to send money to a player.", "/takemoney", []),
                new AddMoney("addmoney", "Allows you to add money to a player.", "/addmoney", []),
                new RemoveMoney("removemoney", "Allows you to withdraw money from a player.", "/removemoney", []),
                new TopMoney("topmoney", "Allows you to see the top 10 players with the most money.", "/topmoney", []),
                new SetMoney("setmoney", "Allows you to define a player's money.", "/setmoney", [])
            ]);
        }
        $this->getServer()->getCommandMap()->registerAll("FacEssential", [
            /* TELEPORT */
            new TPA("tpa", "Allows you to teleport to a player.", "/tpa", []),
            new TPAHere("tpahere", "Allows to teleport to you.", "tpahere", []),
            new TPAccept("tpaccept", "Allows you to accept a teleportation request.", "/tpaccept", []),
            new TPDeny("tpdeny", "Allows you to refuse a teleportation request.", "/tpdeny", []),

            /* RANK */
            new CreateRank("createrank", "Allows you to create a rank.", "/createrank", []),
            new RemoveRank("removerank", "Allows you to delete a rank.", "/removerank", []),
            new SetRank("setrank", "Allows you to set the rank of a player.", "/setrank", []),
            new ListRank("listrank", "Allows you to see the list of available ranks.", "/listrank", []),
            new AddPerm("addperm", "Allows you to add a permission to a rank.", "/addperm", []),
            new RemovePerm("removeperm", "Allows you to withdraw permission from a rank.","/removerank", []),
            new SetFormat("setformat", "Allows you to define the format of a rank", "/setformat", []),
            new SetDefault("setdefault", "Allows you to set the default rank.", "/setdefault", []),

            /* BASIC */
            new Feed("feed", "Allows you to feed a player or yourself.", "/feed", []),
            new Heal("heal", "Allows you to heal a player or yourself.", "/heal", []),
            new Craft("craft", "Allows you to open a crafting table.", "/craft", []),
            new EnderChest("enderchest", "Allows you to open an enderchest.", "/enderchest", ["ec"]),
            new Top("top", "Allows you to teleport to the surface.", "/top", ["surface"])
        ]);

        /* Events */
        $this->getServer()->getPluginManager()->registerEvents(new EntityListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);

        /* Task */

        /* SQL */
        $this->database = new \SQLite3($this->getDataFolder() . "DataBase.db");
        $this->database->query("CREATE TABLE IF NOT EXISTS money (pseudo VARCHAR(55) PRIMARY KEY, money INT)");
        $this->database->query("CREATE TABLE IF NOT EXISTS home (id INT INCREMENT PRIMARY KEY, pseudo VARCHAR(55), home VARCHAR(15), x FLOAT, y INT, z FLOAT)");
        $this->database->query("CREATE TABLE IF NOT EXISTS rank (pseudo VARCHAR(55), rank VARCHAR(55))");

        /* SUPPORT */
        if ($this->manager->get("faction-system") === true) {
            $faction = $this->getServer()->getPluginManager()->getPlugin("SimpleFaction");
            if ($faction !== null) {
                $version = $faction->getDescription()->getVersion();
                $this->getLogger()->notice("Simple faction V" . $version . " successfully loaded !");
            } else {
                $this->getLogger()->notice("§cNo valid version of SimpleFaction found ! Please download a valid version or disable the faction support !");
                $this->getServer()->getPluginManager()->disablePlugin($this);
            }
        }
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
            $this->saveResource("manager.yml");
        }
        if (!file_exists($this->getDataFolder() . "rank.yml")){
            $this->saveResource("rank.yml");
        }
        if (!file_exists($this->getDataFolder() . "lang.yml")){
            $this->saveResource("lang.yml");
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