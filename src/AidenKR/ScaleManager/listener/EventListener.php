<?php

namespace AidenKR\ScaleManager\listener;

use AidenKR\ScaleManager\ScaleManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener {
    
    public ScaleManager $plugin;
    
    public function __construct(ScaleManager $plugin) {
        $this->plugin = $plugin;
    }
    
    public function onJoin(PlayerJoinEvent $event) {
        
        $name = $event->player->getName();
        
        if(!isset($this->plugin->scale[strtolower($name)])) {
            $this->plugin->scale[strtolower($name)]["scale"] = 1;
        }
    }
}
