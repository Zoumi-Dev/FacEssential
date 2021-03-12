<?php

namespace Zoumi\FacEssential\listener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use Zoumi\FacEssential\Main;
use Zoumi\FacEssential\Manager;
use Zoumi\FacEssential\task\CombatLogger;

class EntityListener implements Listener
{

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event)
    {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if ($entity instanceof Player && $damager instanceof Player) {
            if (Main::getInstance()->manager->get("teleport")["immune-players"]) {
                if (isset(Main::getInstance()->immune[$damager->getName()])) {
                    if (time() < Main::getInstance()->immune[$damager->getName()]) {
                        $damager->sendMessage(Manager::PREFIX . "§4You cannot attack when you are immune.");
                        if ($event->isCancelled()) return;
                        $event->setCancelled(true);
                        return;
                    }
                }
                if (isset(Main::getInstance()->immune[$entity->getName()])) {
                    if (time() < Main::getInstance()->immune[$entity->getName()]) {
                        if ($event->isCancelled()) return;
                        $event->setCancelled(true);
                        return;
                    }
                }
            }
            if (!isset(Main::getInstance()->combatLogger[$damager->getName()])) {
                Main::getInstance()->combatLogger[$damager->getName()] = time() + Main::getInstance()->manager->get("cooldown-combat-logger");
                $damager->sendMessage(Manager::PREFIX . "§4You are now in combat, do not disconnect or you will be killed.");
                Main::getInstance()->getScheduler()->scheduleRepeatingTask(new CombatLogger($damager, $entity), 20);
            } else {
                Main::getInstance()->combatLogger[$damager->getName()] = time() + Main::getInstance()->manager->get("cooldown-combat-logger");
            }
            if (!isset(Main::getInstance()->combatLogger[$entity->getName()])) {
                Main::getInstance()->combatLogger[$entity->getName()] = time() + Main::getInstance()->manager->get("cooldown-combat-logger");
                $entity->sendMessage(Manager::PREFIX . "§4You are now in combat, do not disconnect or you will be killed.");
                Main::getInstance()->getScheduler()->scheduleRepeatingTask(new CombatLogger($entity, $damager), 20);
            } else {
                Main::getInstance()->combatLogger[$entity->getName()] = time() + Main::getInstance()->manager->get("cooldown-combat-logger");
            }
        }
    }

    public function onEntityDamage(EntityDamageEvent $event)
    {
        $cause = $event->getCause();
        switch ($cause) {
            case EntityDamageEvent::CAUSE_FALL:
                if (!Main::getInstance()->manager->get("fall-damage")) {
                    if ($event->isCancelled()) return;
                    $event->setCancelled(true);
                }
                break;
            case EntityDamageEvent::CAUSE_LAVA:
                if (!Main::getInstance()->manager->get("lava-damage")) {
                    if ($event->isCancelled()) return;
                    $event->setCancelled(true);
                }
                break;
        }
    }

}