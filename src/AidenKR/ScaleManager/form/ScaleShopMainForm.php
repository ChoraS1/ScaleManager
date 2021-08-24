<?php

namespace AidenKR\ScaleManager\form;

use AidenKR\ScaleManager\ScaleManager;
use pocketmine\form\Form;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\Player;

class ScaleShopMainForm implements Form {

    private ScaleManager $plugin;

    private Player $player;

    private int $count = 0;

    public function __construct(ScaleManager $plugin, Player $player) {
        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function getCoinCount(Player $player, Item $item) :?int
    {
        foreach ($player->getInventory()->all($item) as $item) {
            $this->count += $item->getCount();
        }
        return $this->count;
    }

    public function jsonSerialize() :array
    {
        $content = "\n";
        $content .= "§l§6[!] §r§f나의 크기 : " . ScaleManager::getInstance()->getScale($this->player->getName());
        $content .= "\n";
        $content .= "§l§a[!] §r§f보유중인 크기 코인 : " . $this->getCoinCount($this->player, Item::get(ItemIds::CLAY, 10));
        $content .= "\n";
        $content .= "§r§b▶ §f최대 크기 : 30";
        $content .= "\n§r§b▶ §f최소 크기 : 0.5";
        $content .= "\n\n";

        return [
            "type" => "form",
            "title" => "§l초라서버 크기상점",
            "content" => $content,
            "buttons" => [
                ["text" => "§l크기 구매\n§r§0- 크기 0.1을 구매합니다. -"],
                ["text" => "§l크기 구매\n§r§0- 크기 -0.1을 구매합니다. -"],
                ["text" => "§l크기 복구\n§r§0- 구매한 크기를 복구합니다. -"],
                ["text" => "§l크기 기본설정\n§r§0- 기본 크기로 설정합니다. -"],
                ["text" => "§l창 닫기\n§r§0- 시스템을 종료합니다. -"]
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if(!isset($data)) return;

        $name = $player->getName();
        $scale = $this->plugin;

        $coin = Item::get(ItemIds::CLAY, 10, 1);
        Item::addCreativeItem($coin);
        ItemFactory::registerItem($coin);

        switch ($data) {
            case 0:
                if ($player->getInventory()->contains($coin)) {
                    if($scale->scale[strtolower($name)]["scale"] < 30) {
                        $player->getInventory()->remove($coin);
                        $player->setScale($scale->getScale($name));
                        $scale->addScale($name, 0.1);
                        $player->sendMessage(ScaleManager::$prefix . "크기를 구매하셨습니다.");
                    } else {
                        $player->sendMessage(ScaleManager::$prefix . "최대크기로 성장하셨습니다.");
                    }
                } else {
                    $player->sendMessage(ScaleManager::$prefix . "코인이 부족합니다.");
                }
                break;

            case 1:
                if ($player->getInventory()->contains($coin)) {
                    if($scale->scale[strtolower($name)]["scale"] > 0.5) {
                        $player->getInventory()->remove($coin);
                        $player->setScale($scale->getScale($name));
                        $scale->reduceScale($name, 0.1);
                        $player->sendMessage(ScaleManager::$prefix . "크기를 구매하셨습니다.");
                    } else {
                        $player->sendMessage(ScaleManager::$prefix . "최소크기로 비성장하셨습니다.");
                    }
                } else {
                    $player->sendMessage(ScaleManager::$prefix . "코인이 부족합니다.");
                }
                break;

            case 2:
                $player->sendMessage(ScaleManager::$prefix . "크기를 복구했습니다.");
                $player->setScale($scale->getScale($name));
                break;

            case 3:
                $player->sendMessage(ScaleManager::$prefix . "크기를 기본으로 설정했습니다.");
                $player->setScale(1);
                break;
        }
    }
}
