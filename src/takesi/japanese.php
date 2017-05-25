<?php

namespace takesi;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;

class japanese extends PluginBase implements Listener {

	public function OnEnable() {
		$this->getLogger()->notice("This plugin made by takesi.");
		$this->getLogger()->notice("If you have a bug etc https://github.com/Takesikaityo/OnlyJapanese");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onLogin(PlayerPreLoginEvent $event) {
		$player = $event->getPlayer();
		$ip = $player->getAddress();
		$name = $player->getName();
		$info = json_decode(Utils::getURL("http://freegeoip.net/json/" . $ip), true);
		$innet = str_replace("192.168.", "999.999.", $ip);
		if ($ip == $innet) {
			if (!$info['country_code'] == "JP") {
				$player->kick("You arenot Japanese.", false);
				$this->getLogger()->notice($name . "が" . $info['country_name'] . "の" . $info['city'] . "からログインしようとしました。");
			} else {
				$this->getLogger()->notice($name . "が" . $info['country_name'] . "の" . $info['city'] . "からログインしました。");
			}
		} else {
			$this->getLogger()->notice($name . "が内部インターネットからログインしました。");
		}
	}

}