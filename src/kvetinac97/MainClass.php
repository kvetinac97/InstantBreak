<?php
namespace kvetinac97;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\plugin\PluginBase;

class MainClass extends PluginBase implements Listener{

public function onLoad(){
		$this->getLogger()->info(TextFormat::WHITE . "Loaded!");
}
public function onEnable(){
$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(TextFormat::DARK_GREEN . "InstantBreaking enabled!");
}
public function onDisable(){
		$this->getLogger()->info(TextFormat::DARK_RED . "InstantBreaking disabled");
} 
  
public function onCommand(CommandSender $sender, Command $command, $label, array $args){
 if ($command->getName() == "ib-on"){
 if ($sender->hasPermission("ib.on")) {

$sender->sendMessage("§7Now hold Iron Shovel");
$sender->getInventory()->addItem(Item::get(256));

return true;
}
 elseif ($sender->hasPermission("ib")) {

$sender->sendMessage("§7Now hold Iron Shovel");
$sender->getInventory()->addItem(Item::get(256));
return true;
}
 else {

$sender->sendTip("§o§cError: No permission");

return true;
break;
}
 }

  if ($command->getName() == "ib-off") {
  if ($sender->hasPermission("ib.off")) {
 
$sender->sendMessage("§o§7Disabled!");
$sender->getInventory()->removeItem(Item::get(256));

return true;
}
  elseif ($sender->hasPermission("ib")) {
 
$sender->sendMessage("§o§7Disabled!");
$sender->getInventory()->removeItem(Item::get(256)); 
  
  }
  else {$sender->sendTip("§cError: No permission found");

}      
break;
}
return true;
}

public function onPlayerItemHeldEvent (PlayerItemHeldEvent $event) {

$item = $event->getItem();
$id = $item->getId();

if ($id == 256) {

$player = $event->getPlayer();

$player->sendTip("§aInstantBreaking ENABLED!");
}
}

public function onTouch (PlayerInteractEvent $event) {

$item = $event->getItem();
$id = $item->getId();

 if ($id === 256) {

 if ($event->getPlayer()->hasPermission("ib.use")) {

$block = $event->getBlock();
$x = $block->getX();
$y = $block->getY();
$z = $block->getZ();
$level = $block->getLevel();
$id = $block->getId();

$level->dropItem(new Vector3($x,$y,$z), Item::get($id));
$level->setBlock(new Vector3($x,$y,$z), Block::get(0));

}

 elseif ($event->getPlayer()->hasPermission("ib")) {
 
$block = $event->getBlock();
$x = $block->getX();
$y = $block->getY();
$z = $block->getZ();
$level = $block->getLevel();
$id = $block->getId();

$level->dropItem(new Vector3($x,$y,$z), Item::get($id));
$level->setBlock(new Vector3($x,$y,$z), Block::get(0));
 
}

 else {

$player = $event->getPlayer();
$player->sendPopup("§cYou don't have permissions for this!");

}
}
}
}
