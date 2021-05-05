<?php


namespace Angel\Anvils;


use pocketmine\event\Event;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * Class AnvilResultEvent
 * @package Angel\Anvils
 */
class AnvilResultEvent extends Event{

    /** @var Player */
    private $user;

    /** @var Item */
    private $item;

    /**
     * @param Player $user
     * @param Item $item
     */
    public function __construct(Player $user, Item $item){
        $this->user = $user;
        $this->item = $item;
    }

    /**
     * @return Player
     */
    public function getUser() : Player{
        return $this->user;
    }

    /**
     * @return Item
     */
    public function getItem() : Item{
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item) : void{
        $this->item = $item;
    }
}