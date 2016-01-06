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
use pocketmine\utils\Config;

 class Mainx extends PluginBase implements Listener{

  /** @var  Config $drops */
  public $drops;
  /** @var  Config $config */
  public $config;
  /** @var  Config $players */
  public $players;


 public function onEnable(){
  $this->getServer()->getPluginManager()->registerEvents($this, $this);
  $this->saveDefaultConfig();
  $this->saveResource("drops.yml", true);
  $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
  $this->drops = new Config($this->getDataFolder()."drops.yml", Config::YAML);
  $this->players = new Config($this->getDataFolder()."players.yml", Config::YAML);
 }
 public function onDisable(){
  $this->config->save();
  $this->players->save();
  $this->getLogger()->info(TextFormat::GRAY . "All files were saved!");
  $this->getLogger()->info(TextFormat::DARK_RED . "InstantBreaking disabled!");
 } 


//COMMANDS /ib-on, /ib-off

public function onCommand(CommandSender $sender, Command $command, $label, array $args){

 if ($command->getName() == "ib-on"){
 if ($sender->hasPermission("ib.command.on")) {
 if ($sender instanceof Player){
 if (isset($args[0]) and $sender->hasPermission("ib.other.on")){
 $sender = $args[0];
 }
 if ($this->players->get($sender->getName() . "_ib-on-used") == "false" || $this->players->get($sender->getName() . "_ib-on-used") == null) {

  $silktouchitem = $this->config->get("silktouch-item");
  $touchitem = $this->config->get("touch-item");
  $color = str_replace("&", "§", $this->config->get("ib-on-enabled-msg"));
  $var1 = str_replace("{touch-item}", $touchitem, $color);
  $var2 = str_replace("{silktouch-item}", $silktouchitem, $var1);
  $var3 = str_replace("269", "Wooden Shovel", $var2);
  $var4 = str_replace("270", "Wooden Pickaxe", $var3);
  $var5 = str_replace("273", "Stone Shovel", $var4);
  $var6 = str_replace("274", "Stone Pickaxe", $var5);
  $var7 = str_replace("256", "Iron Shovel", $var6);
  $var8 = str_replace("257", "Iron Pickaxe", $var7);
  $var9 = str_replace("284", "Golden Shovel", $var8);
  $var10 = str_replace("285", "Gold Pickaxe", $var9);
  $var11 = str_replace("277", "Diamond Shovel", $var10);
  $msg = str_replace("278", "Diamond Pickaxe", $var11);

  $sender->sendMessage($msg);
  $sender->getInventory()->addItem(Item::get($silktouchitem));
  $sender->getInventory()->addItem(Item::get($touchitem));
  $this->players->set($sender->getName() . "_enabled", "true");
  $this->players->set($sender->getName() . "_ib-on-used", "true");
  $this->players->set($sender->getName() . "_ib-off-used", "false");

  $this->players->save();
 return true;
}
 else {
 $msg = str_replace("&", "§", $this->config->get("ib-on-already-used"));
 $sender->sendMessage($msg);
 return true;
 }
 }
 else {
 $this->getLogger()->info(TextFormat::RED."IB isn't avaible for Console!");
 return true;
 }
 }
 else {
 $tip = str_replace("&", "§", $this->config->get("permission-ib-on-not-found")); 
 if ($sender instanceof Player){
 $sender->sendTip($tip);
 }
 return true;
 }
 }
 if ($command->getName() == "ib-off") {
 if ($sender->hasPermission("ib.off")) {
 if (isset($args[0]) and $sender->hasPermission("ib.other.off")){
 $sender = $args[0];
 }
if ($sender instanceof Player){
 if ($this->players->get($sender->getName() . "_ib-off-used") == "false" || $this->players->get($sender->getName() . "_ib-off-used") == null) {

  $silktouchitem = $this->config->get("silktouch-item");
  $touchitem = $this->config->get("touch-item");
  $msg = str_replace("&", "§", $this->config->get("ib-off-disabled-msg"));

  $sender->sendMessage($msg);
  $sender->getInventory()->removeItem(Item::get($silktouchitem, null));
  $sender->getInventory()->removeItem(Item::get($touchitem, null));
  $this->players->remove($sender->getName() . "_enabled");
  $this->players->set($sender->getName() . "_ib-on-used", "false");
  $this->players->set($sender->getName() . "_ib-off-used", "true");
$this->players->save();
  $this->players->set($sender->getName() . "ib-not-enabled-1", "false");
  $this->players->save();
 return true;
 }
 else {
 $msg = str_replace("&", "§", $this->config->get("ib-off-already-used")); 
 $sender->sendMessage($msg);
 return true; 
 }
 }
 else {
 $this->getLogger()->info(TextFormat::RED."IB isn't avaible for Console!");
return true;
 }
 }
 else {
  $tip = str_replace("&", "§", $this->config->get("permission-ib-off-not-found"));
if ($sender instanceof Player){
 $sender->sendTip($tip);
}
 return true;
 }
 }
}
//Holding items -> sending Enabled/Disabled Messages

 public function onHold (PlayerItemHeldEvent $event) {
  $id = $event->getItem()->getId();
  $silktouchid = $this->config->get("silktouch-item");
  if ($silktouchid == $id) {
   if ($event->getPlayer()->hasPermission("ib.use.silktouch")) {
    if ($this->players->get($event->getPlayer()->getName() . "popup-enable-touch") == "true") {
     $this->players->set($event->getPlayer()->getName() . "popup-enable-touch", "false");
     $this->players->save();
    }
    $silktouchenabled = $this->players->get($event->getPlayer()->getName() . "_enabled");
    if ($silktouchenabled == "true" and $this->config->get("silktouch") == "true") {
     $popup = str_replace("&", "§", $this->config->get("ib-silktouch-hold-enable"));
     $event->getPlayer()->sendPopup($popup);
     $this->players->set($event->getPlayer()->getName() . "popup-enable-silktouch", "true");
     $this->players->save();
    }
    elseif ($this->players->get($event->getPlayer()->getName() . "ib-not-enabled-1") == "false") {
     $msg = str_replace("&", "§", $this->config->get("ib-not-enabled"));
     $event->getPlayer()->sendMessage($msg);
     $this->players->set($event->getPlayer()->getName() . "ib-not-enabled-1", "true");
     $this->players->save();
    }
    }
    else {
     $popup = str_replace("&", "§", $this->config->get("permission-ib-use-silktouch-not-found"));
     $event->getPlayer()->sendPopup(1,$popup);
    }
   }
   $touchid = $this->config->get("touch-item");
   $id = $event->getItem()->getId();
   if ($touchid == $id) {
    if ($event->getPlayer()->hasPermission("ib.use.touch")) {
     if ("true" == $this->players->get($event->getPlayer()->getName() . "popup-enable-silktouch")) {
      $this->players->set($event->getPlayer()->getName() . "popup-enable-silktouch", "false");
      $this->players->save();
     }
    $touchenabled = $this->players->get($event->getPlayer()->getName() . "_enabled");
    if ($touchenabled == "true" and $this->config->get("touch") == "true") {
     $popup = str_replace("&", "§", $this->config->get("ib-touch-hold-enable"));
     $event->getPlayer()->sendPopup(1,$popup);
     $this->players->set($event->getPlayer()->getName() . "popup-enable-touch", "true");
     $this->players->save();
    }
    elseif ($this->players->get($event->getPlayer()->getName() . "ib-not-enabled-1") == "false") {
     $msg = str_replace("&", "§", $this->config->get("ib-not-enabled"));
     $event->getPlayer()->sendMessage($msg);
     $this->players->set($event->getPlayer()->getName() . "ib-not-enabled-1", "true");
     $this->players->save();
    }
    }
    else {
     $popup = str_replace("&", "§", $this->config->get("permission-ib-use-not-found"));
     $event->getPlayer()->sendPopup(1,$popup);
    }
   }
   $id = $event->getItem()->getId();
   if ($id != $this->config->get("silktouch-item") and $id != $this->config->get("touch-item")) {
    if ($this->players->get($event->getPlayer()->getName() . "popup-enable-silktouch") == "true") {
     $popup = str_replace("&", "§", $this->config->get("ib-silktouch-hold-disable"));
     $event->getPlayer()->sendPopup(1,$popup);
     $this->players->set($event->getPlayer()->getName() . "popup-enable-silktouch", "false");
     $this->players->save();
   }
   elseif ($this->players->get($event->getPlayer()->getName() . "popup-enable-touch") == "true") {
    $popup = str_replace("&", "§", $this->config->get("ib-touch-hold-disable"));
    $event->getPlayer()->sendPopup(1,$popup);
    $this->players->set($event->getPlayer()->getName() . "popup-enable-touch", "false");
    $this->players->save();
   }
  }
 }

//onPlayerInteractEvent -> The Main thing about this plugin

 public function onTouch (PlayerInteractEvent $event) {
  $penabled = $this->players->get($event->getPlayer()->getName() . "_enabled");
  if ($penabled == "true") {
 
 //SilkTouch
 if ($event->getItem()->getId() == $this->config->get("silktouch-item")) {
 if ($this->config->get("silktouch") == "true") {
 if ($event->getPlayer()->hasPermission("ib.use.silktouch")) {

$event->getBlock()->getLevel()->setBlock(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ()), Block::get(0));

 if ($event->getBlock()->getId() != 7) {
 
$event->getBlock()->getLevel()->dropItem(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ()), Item::get($event->getBlock()->getId(), $event->getBlock()->getDamage(), 1)); 
}

elseif ($event->getPlayer()->hasPermission("ib.unbreakable")) {

$event->getBlock()->getLevel()->dropItem(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ()), Item::get(7, 0, 1));
}
else {

$popup = str_replace("&", "§", $this->config->get("permission-ib-unbreakable-not-found"));
$event->getPlayer()->sendPopup(1,$popup);
}
}
 else {

 $popup = str_replace("&", "§", $this->config->get("permission-ib-use-silktouch-not-found"));
 $event->getPlayer()->sendPopup(1,$popup);

}
}
else {

$popup = str_replace("&", "§", $this->config->get("ib-silktouch-not-enabled"));
$event->getPlayer()->sendPopup(1,$popup);
}
}
 //Touch with custom drops -> set in drops.yml
 if ($event->getItem()->getId() == $this->config->get("touch-item")) {
 if ($this->config->get("touch") == "true") {
 if ($event->getPLayer()->hasPermission("ib.use.touch")) {
 $event->getBlock()->getLevel()->setBlock(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ()), Block::get(0));

 if ($event->getBlock()->getId() != 7) {

$id = $this->drops->get($event->getBlock()->getName());
$damage = $this->drops->get($event->getBlock()->getName() . "_Damage");
$cnt = $this->drops->get($event->getBlock()->getName() . "_Count");
 
 if ($cnt == null) {
 $cont = "1";
 }
 else {
 $cont = $cnt;
 }
 
$event->getBlock()->getLevel()->dropItem(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ()), Item::get($id, $damage, $cont)); 
}

elseif ($event->getPlayer()->hasPermission("ib.unbreakable")) {

$event->getBlock()->getLevel()->dropItem(new Vector3($event->getBlock()->getX(),$event->getBlock()->getY(),$event->getBlock()->getZ()), Item::get(0));
}
 }
 else {
 $popup = str_replace("&", "§", $this->config->get("permission-ib-use-touch-not-found"));
 $event->getPlayer()->sendPopup(1,$popup);
 }
 }
 else {
 $popup = str_replace("&", "§", $this->config->get("ib-touch-not-enabled"));
 $event->getPlayer()->sendPopup(1,$popup);
 
 }
 } 
}
}
}
