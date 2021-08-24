<?php

namespace AidenKR\ScaleManager\listener;

use AidenKR\ScaleManager\ScaleManager;
use pocketmine\block\BlockIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;

class EventListener implements Listener {

    public ScaleManager $plugin;

    public function __construct(ScaleManager $plugin) {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event) {

        $name = $event->getPlayer()->getName();

        if(!isset($this->plugin->scale[strtolower($name)])) {
            $this->plugin->scale[strtolower($name)]["scale"] = 1;
        }
    }

    public function onMove(PlayerMoveEvent $event) {

        $player = $event->getPlayer();
        $name = $player->getName();
        $block = $player->level->getBlock($player->add(0, 1, 0));

        if($block->getId() === BlockIds::REDSTONE_BLOCK) {
            $player->setScale($this->plugin->getScale($name));
            $player->sendMessage(ScaleManager::$prefix . "크기를 복구했습니다.");
        }

        if($block->getId() === BlockIds::LAPIS_BLOCK) {
            $player->setScale($this->plugin->getScale($name));
            $player->sendMessage(ScaleManager::$prefix . "크기를 복구했습니다.");
        }
    }
}
