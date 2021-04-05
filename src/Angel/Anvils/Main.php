<?php

declare(strict_types=1);

namespace Angel\Anvils;

use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\block\BlockFactory;
use Angel\Anvils\block\Anvil;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\{InventoryTransactionPacket,
    FilterTextPacket,
    types\ContainerIds,
    types\NetworkInventoryAction};
use Angel\Anvils\inventory\AnvilInventory;


/**
 * Class Main
 * @package Angel\Anvils
 */
class Main extends PluginBase implements Listener{

    public function onEnable(){
        BlockFactory::registerBlock(new Anvil(), true);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        $player = $event->getPlayer();

        if ($pk instanceof InventoryTransactionPacket) {
            $window = $player->getWindow(7);

            if (!($window instanceof AnvilInventory)) return;

            foreach ($pk->actions as $action) {

                switch ($action->sourceType){

                    case NetworkInventoryAction::SOURCE_TODO://anvil event
                        if ($action->windowId === NetworkInventoryAction::SOURCE_TYPE_ANVIL_RESULT){
                            $window->finalItem = $action->oldItem->setCustomName($window->itemName);
                        }

                        if ($action->windowId === -10){
                           if ($player->getCursorInventory()->getItem(0)->getId() === $window->finalItem->getId()){
                               $player->getCursorInventory()->setItem(0, $window->finalItem);
                           }
                        }

                        break;

                    case NetworkInventoryAction::SOURCE_CONTAINER:
                        $slot = $action->inventorySlot;

                        if ($action->windowId === ContainerIds::UI && (1<=$slot) && ($slot<=2)){
                            $window->setItem($slot-1, $action->newItem);
                        } else {
                            $player->getWindow($action->windowId)->setItem($slot, $action->newItem);
                        }

                        break;
                }
            }

        } elseif ($pk instanceof FilterTextPacket) {

            $window = $player->getWindow(7);

            if ($window instanceof AnvilInventory) {
                if ($window->getItem(1)->getId() === Item::AIR){
                    $window->setItem(1, Item::get(Item::CLOCK));
                    $window->setItem(1, Item::get(Item::AIR));
                    $window->itemName = $pk->getText();
                }
            }
        }
    }
}
