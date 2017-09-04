<?php

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;


class Main extends PluginBase{
  
  public $chechks = [];
  
  public function onEnable(){
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new Checker($this), 0);
  }
}

class Checker extends PluginTask{
  
  public function __construct(Main $plugin){
    $this->plugin = $plugin;
  }
  
  public function getPlugin(){
    return $this->plugin;
  }
  
  public function onRun(int $currentTick){
    $this->getOwner();
    foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $players){
      $player = $players;
      if($player->getItemInHand() !== null){
        $player->getItemInHand()->setCustomName($player->getItemInHand()->getCustomName());
      }
    }
  }
}
