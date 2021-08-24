<?php

namespace AidenKR\ScaleManager;

use AidenKR\ScaleManager\command\ScaleCommand;
use AidenKR\ScaleManager\command\ScaleShopCommand;
use AidenKR\ScaleManager\listener\EventListener;
use pocketmine\plugin\PluginBase;

class ScaleManager extends PluginBase
{
    public static string $prefix = '§l§b[알림] §r§7';

    private static ?ScaleManager $instance = null;

    public array $scale;

    public static function getInstance(): ?ScaleManager
    {
        return self::$instance;
    }

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->registerAll('AidenKR', [
            new ScaleCommand($this), new ScaleShopCommand($this)
        ]);

        if (!file_exists($this->getDataFolder() . 'scale.json')) {
            $this->scale = json_decode(file_get_contents($this->getDataFolder() . 'scale.json'), true);
        }
    }

    public function getScale(string $name) :?float
    {
        return $this->scale[strtolower($name)]["scale"];
    }

    public function addScale(string $name, float $scale) :?self {
        $this->scale[strtolower($name)]["scale"] += $scale;
        return $this;
    }

    public function setScale(string $name, float $scale) :?self {
        $this->scale[strtolower($name)]["scale"] = $scale;
        return $this;
    }

    public function reduceScale(string $name, float $scale) :?self {
        $this->scale[strtolower($name)]["scale"] -= $scale;
        return $this;
    }

    public function onDisable()
    {
        file_put_contents($this->getDataFolder() . 'scale.json', json_encode($this->scale, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
