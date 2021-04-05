<?php

namespace Angel\Anvils\block;


use Angel\Anvils\inventory\AnvilInventory;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Class Anvil
 * @package Angel\Anvils\block
 */
class Anvil extends \pocketmine\block\Anvil {

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null): bool{
        if($player instanceof Player){
            $player->addWindow(new AnvilInventory($this), 7);
        }

        return true;
    }
}
