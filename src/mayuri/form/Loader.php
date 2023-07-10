<?php

/*
 *
 *  __  __                        _
 * |  \/  | __ _ _   _ _   _ _ __(_)
 * | |\/| |/ _` | | | | | | | '__| |
 * | |  | | (_| | |_| | |_| | |  | |
 * |_|  |_|\__,_|\__, |\__,_|_|  |_|
 *               |___/
 *
 * Copyright (c) 2022-2023 Mayuri and contributors
 *
 * Permission is hereby granted to any persons and/or organizations
 * using this software to copy, modify, merge, publish, and distribute it.
 * Said persons and/or organizations are not allowed to use the software or
 * any derivatives of the work for commercial use or any other means to generate
 * income, nor are they allowed to claim this software as their own.
 *
 * The persons and/or organizations are also disallowed from sub-licensing
 * and/or trademarking this software without explicit permission from Mayuri.
 *
 * Any persons and/or organizations using this software must disclose their
 * source code and have it publicly available, include this license,
 * provide sufficient credit to the original authors of the project (IE: Mayuri),
 * as well as provide a link to the original project.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,FITNESS FOR A PARTICULAR
 * PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE
 * USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author Mayuri
 *
 */

declare(strict_types=1);

namespace mayuri\form;

use mayuri\form\event\ServerSettingsRequestEvent;
use mayuri\form\queue\FormQueue;
use mayuri\form\type\CustomForm;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ServerSettingsRequestPacket;
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\VersionInfo;

final class Loader extends PluginBase implements Listener {
	use SingletonTrait {
		setInstance as protected;
		getInstance as protected _getInstance;
	}
	public function onLoad(): void {
		FormQueue::getInstance();
	}

	public function onEnable(): void {
        $this->getLogger()->info("\x5b\x49\x4e\x46\x4f\x5d\x3a\x20\x54\x68\x65\x20\x70\x6c\x75\x67\x69\x6e\x20\x68\x61\x73\x20\x62\x65\x65\x6e\x20\x61\x63\x74\x69\x76\x61\x74\x65\x64\x2e");
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDisable(): void {
		$this->getLogger()->info("\x5b\x49\x4e\x46\x4f\x5d\x3a\x20\x54\x68\x65\x20\x70\x6c\x75\x67\x69\x6e\x20\x68\x61\x73\x20\x62\x65\x65\x6e\x20\x63\x6c\x6f\x73\x65\x64\x21");
	}

	private function sendSetting(Player $player, CustomForm $form) : void {
		$reflection = new \ReflectionObject($player);
		$idProperty = $reflection->getProperty("formIdCounter"); //TODO: sync it with SOF3 PR
		$idProperty->setAccessible(true);
		$id = $idProperty->getValue($player);
		$idProperty->setValue($player, ++$id);
		$id--;
		$pk = new ServerSettingsResponsePacket();
		$pk->formId = $id;
		$pk->formData = json_encode($form);
		if ($player->getNetworkSession()->sendDataPacket($pk)) {
			$formsProperty = $reflection->getProperty("forms");
			$formsProperty->setAccessible(true);
			$formsValue = $formsProperty->getValue($player);
			$formsValue[$id] = $form;
			$formsProperty->setValue($player, $formsValue);
		}
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $event) : void {
		$packet = $event->getPacket();
		$player = $event->getOrigin()->getPlayer();
		if ($packet instanceof ServerSettingsRequestPacket) {
			$ev = new ServerSettingsRequestEvent($player);
			$ev->call();
			if (($form = $ev->getForm()) !== null) {
				$this->sendSetting($player, $form);
			}
		}
	}
}