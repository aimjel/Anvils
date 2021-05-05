<?php

declare(strict_types=1);

namespace Angel\Anvils;


use pocketmine\block\BlockIds;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\AnvilInventory;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\{InventoryTransactionPacket,
    FilterTextPacket,
    LevelSoundEventPacket,
    types\ContainerIds,
    types\NetworkInventoryAction};



/**
 * Class Main
 * @package Angel\Anvils
 */
class Main extends PluginBase implements Listener{

    /** @var array  */
    private $windowIds = [];

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event) : void{
        if ($event->getBlock()->getId() === BlockIds::ANVIL){
            $event->setCancelled(true);//Cancels the anvil inventory from being sent

            $player = $event->getPlayer();
            $this->windowIds[$player->getName()] =  $player->addWindow(new AnvilInventory($event->getBlock()));
        }
    }

    /**
     * @param DataPacketReceiveEvent $event
     */
    public function onReceive(DataPacketReceiveEvent $event) : void{
        $player = $event->getPlayer();

        if (!isset($this->windowIds[$player->getName()])) return;

        if(($inv = $player->getWindow($this->windowIds[$player->getName()])) instanceof AnvilInventory){

            $pk = $event->getPacket();

            if ($pk instanceof FilterTextPacket){
                $event = new AnvilRenameEvent($player, $pk->getText());
                $event->call();

                if (!$event->isCancelled()){
                    $player->sendDataPacket(FilterTextPacket::create($event->getText(), true), false, true);
                }

            } elseif ($pk instanceof InventoryTransactionPacket){

                foreach ($pk->trData->getActions() as $action){

                    switch ($action->sourceType){
                        case NetworkInventoryAction::SOURCE_CONTAINER:
                            $slot = $action->inventorySlot;

                            if ($action->windowId === ContainerIds::UI && (1<=$slot) && ($slot<=2)){
                                $inv->setItem($slot-1, $action->newItem->getItemStack());
                            } else {
                                $player->getWindow($action->windowId)->setItem($slot, $action->newItem->getItemStack());
                            }

                            break;

                        case NetworkInventoryAction::SOURCE_TODO:
                            if ($action->windowId === NetworkInventoryAction::SOURCE_TYPE_ANVIL_RESULT){
                                $event = new AnvilResultEvent($player, $action->oldItem->getItemStack());
                                $event->call();

                                $player->getLevel()->broadcastLevelSoundEvent($player->getPosition(), LevelSoundEventPacket::SOUND_RANDOM_ANVIL_USE);
                            }
                    }
                }
            }
        }
    }
}