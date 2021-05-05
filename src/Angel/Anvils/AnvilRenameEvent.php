<?php


namespace Angel\Anvils;


use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\Player;

/**
 * Class AnvilRenameEvent
 * @package Angel\Anvils
 */
class AnvilRenameEvent extends Event implements Cancellable {

    private $user;

    /** @var string  */
    private $text;

    /**
     * AnvilRenameEvent constructor.
     * @param Player $user
     * @param string $text
     */
    public function __construct(Player $user, string $text){
        $this->user = $user;
        $this->text = $text;
    }

    /**
     * @return Player
     */
    public function getUser() : Player{
        return $this->user;
    }

    /**
     * @return string
     */
    public function getText() : string{
        return $this->text;
    }

    public function setText(string $text) : void{
        $this->text = $text;
    }
}