<?php

namespace kvetinac97;

/*
 * EventListener class for InstantBreak v3.0.0 by © kvetinac97 2016
 * You can modify this file without restrictions
 * (You must write that it was originally written by kvetinac97)
 */

use pocketmine\event\Listener;

class EventListener implements Listener {

    /** @var Main $main */
    public $main;

    public function __construct(Main $main){
        $this->main = $main;
    }



}