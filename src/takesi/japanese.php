<?php

namespace takesi;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Utils;

class japanese extends PluginBase implements Listener {

	public function onEnable() {
		$this->getLogger()->notice("This plugin made by takesi.");
		$this->getLogger()->notice("If you have a bug etc https://github.com/Takesikaityo/OnlyJapanese");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onPlayerPreLogin(PlayerPreLoginEvent $event) {
		$player = $event->getPlayer();
		$ip = $player->getAddress();
		$name = $player->getName();

		[$allow, $message] = $this->checkLocation($ip);
		if ($allow === false) {
			$player->kick("Sorry, we accept only from japanese networks.", false);
		}
		$this->getLogger()->info($name . ": " . $message);
	}

	/**
	 * 国内からのログインかをチェックします
	 * 192.168.*.* と 127.0.0.1 はローカルネットワークとみなします
	 *
	 * @param string $ip
	 * @return array [国内か否か(bool), メッセージ(string)]
	 */
	private function checkLocation(string $ip): array {
		$ip_array = explode(".", $ip);
		if ($ip === "127.0.0.1" || $ip_array[0] . $ip_array[1] === "192.168") {
			return [true, "ローカルネットワークからのログインです"];
		} else {
			$data = json_decode(Utils::getURL("http://freegeoip.net/json/" . $ip), true);
			switch ($data["country_code"]) {
				case "JP":
					return [true, $data["city"] . "からのログインです"];

				case "":
					return [false, "不明な国からのログインです(ブロック)"];

				default:
					return [false, "国外(" . $data["country_name"] . ")からのログインです(ブロック)"];
			}
		}
	}

}