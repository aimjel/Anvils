<?php


namespace Angel\Anvils\inventory;

use pocketmine\inventory\CustomInventory;

use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\types\WindowTypes;

/**
 * Class AnvilInventory
 * @package Angel\Anvils\inventory
 */
class AnvilInventory extends CustomInventory{

    /** @var string */
    public $itemName;

    /** @var Item */
    public $finalItem;

    public function getNetworkType() : int{
        return WindowTypes::ANVIL;
    }

    public function getName() : string{
        return "Anvil";
    }

    public function getDefaultSize() : int{
        return 2;
    }
}
