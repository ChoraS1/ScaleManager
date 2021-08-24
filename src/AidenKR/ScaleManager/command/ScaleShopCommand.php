<?php

namespace AidenKR\ScaleManager\command;

use AidenKR\ScaleManager\form\ScaleShopMainForm;
use AidenKR\ScaleManager\ScaleManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ScaleShopCommand extends Command {

    private ScaleManager $plugin;

    public function __construct(ScaleManager $plugin) {
        parent::__construct("크기상점", "크기를 구매합니다.");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $player = $sender;

        if($player instanceof Player) {
            $player->sendForm(new ScaleShopMainForm($this->plugin, $player));
        }
    }
}
