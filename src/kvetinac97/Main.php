<?php

namespace kvetinac97;

/*
 * This plugin was written by © kvetinac97 2016
 * You can use it on modify it without any restricitions
 * (You must write that it was originally developped by kvetinac97
 * Read full LICENSE in file LICENSE
 */

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase {

    const VERSION = 3;

    /** @var String $drops */
    public $drops;

    /** @var String $config */
    public $config;

    /** @var EventListener $listener */
    public $listener;

    /*
     * Loading, Enabling, Disabling... START
     */

    public function onLoad(){
        $this->getLogger()->notice(TF::YELLOW."Loading InstantBreak version ".TF::AQUA.self::VERSION.".0.0...");
    }

    public function onEnable(){
        $this->getLogger()->info(TF::GREEN."InstantBreak enabled!");
        if(!file_exists($cfg = $this->getDataFolder()."config.yml") or !file_exists($drops = $this->getDataFolder()."drops.yml")){
            $this->saveResource("config.yml", false);
            $this->saveResource("drops.yml", false);
        }
        $this->config = \yaml_parse_file($cfg);
        $this->drops = \yaml_parse_file($drops);
        $this->getServer()->getPluginManager()->registerEvents($this->listener = new EventListener($this), $this);
        $this->getLogger()->info(TF::GREEN."All files have been successfully iniated!");
    }

    public function onDisable(){
        $this->getLogger()->info(TF::DARK_RED."InstantBreak disabled!");
    }

    /*
     * Loading, Enabling, Disabling END
     */

    /*
     * Commands /ib, /ib-on, /ib-off START
     */




}