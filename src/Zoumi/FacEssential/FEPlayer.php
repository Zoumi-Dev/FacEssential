<?php

namespace Zoumi\FacEssential;

use pocketmine\Player;

class FEPlayer extends Player {

    /**
     * @param array $perms
     */
    public function addPerms(array $perms): void{
        foreach ($perms as $perm){
            $this->addAttachment(Main::getInstance())->setPermission($perm, true);
            $this->addAttachment(Main::getInstance(), $perm);
        }
    }

    public function delPerms(array $perms): void{
        foreach ($perms as $perm){
            $this->addAttachment(Main::getInstance())->unsetPermission($perm);
            $this->addAttachment(Main::getInstance(), $perm);
        }
    }

}