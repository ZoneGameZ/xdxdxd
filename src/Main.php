<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener
{

	protected function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {
		if($cmd->getName() === "nick"){
			$sender->sendMessage("done");
		$p = $this->getServer()->getPlayer($args[0]);
		$p->setNameTag([$args[1]);
			return true;
		}
	}

	public function keepInventory($event) {
		$player = $event->getPlayer();
		$event->setKeepInventory(true);
		$msgAfterDeath = $this->getConfig()->get("MsgAfterDeath");
		switch ($this->getConfig()->get("MsgType")) {
			case "message":
				$player->sendMessage($msgAfterDeath);
				break;
			case "title":
				$player->sendTitle($msgAfterDeath);
				break;
			case "popup":
				$player->sendPopup($msgAfterDeath);
				break;
			case "tip":
				$player->sendTip($msgAfterDeath);
				break;
			case "actionbar":
				$player->sendActionBarMessage($msgAfterDeath);
				break;
		}
	}

	public function PlayerDeath(PlayerDeathEvent $event)
	{
		if ($this->getConfig()->get("KeepInventory") == true) {
			$worldName = $event->getPlayer()->getWorld()->getDisplayName();
			$worlds = $this->getConfig()->get("Worlds");
			switch ($this->getConfig()->get("Mode")) {
				case "all":
					$this->keepInventory($event);
					break;
				case "whitelist":
					if (in_array($worldName, $worlds)) {
						$this->keepInventory($event);
					}
					break;
				case "blacklist":
					if (!in_array($worldName, $worlds)) {
						$this->keepInventory($event);
					}
					break;
			}
		} else {
			$event->setKeepInventory(false);
		}
	}
}
