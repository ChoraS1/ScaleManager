<?php

namespace AidenKR\ScaleManager\command;

use AidenKR\ScaleManager\ScaleManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ScaleCommand extends Command {

    private ScaleManager $plugin;

    public function __construct(ScaleManager $plugin) {
        parent::__construct("크기", "플레이어의 크기를 조정합니다.");
        $this->setPermission("scale.command");
        $this->setUsage("/크기 [0.1~99]");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $player = $sender;

        if($player instanceof Player) {
            if($player->hasPermission($this->getPermission())) {
                if(!isset($args[0]) or !is_numeric($args[0])) {
                    $player->sendMessage(ScaleManager::$prefix . $this->getUsage());
                    return;
                }
                $this->plugin->setScale($player->getName(), (float)$args[0]);
                $player->sendMessage(ScaleManager::$prefix . "크기를 설정했습니다.");
            }
        }
    }
}
