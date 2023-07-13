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

namespace mayuri\form\type;

use Closure;
use mayuri\form\elements\components\Button;
use mayuri\form\Form;
use mayuri\form\queue\FormQueue;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use function gettype;
use function is_int;
use function is_string;

class SimpleForm extends Form {
	/** @var Button[] */
	protected array $buttons = [];
	/** @var string */
	protected string $text;
	/** @var Closure|null */
	private ?Closure $onSubmit;
	/** @var Closure|null */
	private ?Closure $onClose;

	public function __construct(string $title, string $text = "", array $buttons = [], ?Closure $onSubmit = null, ?Closure $onClose = null) {
		parent::__construct($title);
		$this->text = $text;
		$this->append(...$buttons);
		if($onSubmit !== null){
			$this->setOnSubmit($onSubmit);
		}
		if($onClose !== null){
			$this->setOnClose($onClose);
		}
	}

	public function setText(string $text): self {
		$this->text = $text;
		return $this;
	}

	public function append(...$buttons): self {
		if (isset($buttons[0]) && is_string($buttons[0])) {
			$buttons = Button::createFromList(...$buttons);
		}
		$this->buttons = array_merge($this->buttons, $buttons);
		return $this;
	}

	public function setOnSubmit(?Closure $onSubmit): self {
		if ($onSubmit !== null) {
			Utils::validateCallableSignature(function (Player $player, Button $selected): void {}, $onSubmit);
			$this->onSubmit = $onSubmit;
		}
		return $this;
	}

	public function setOnClose(?Closure $onClose): self {
		if ($onClose !== null) {
			Utils::validateCallableSignature(function (Player $player): void {}, $onClose);
			$this->onClose = $onClose;
		}
		return $this;
	}

	final public function getType(): string {
		return self::TYPE_SIMPLE;
	}

	protected function serializeFormData(): array {
		return ["\x62\x75\x74\x74\x6f\x6e\x73" => $this->buttons, "\x63\x6f\x6e\x74\x65\x6e\x74" => $this->text];
	}

	final public function handleResponse(Player $player, $data): void {
		FormQueue::getInstance()->removeForm($player);
		if ($data === null) {
			if ($this->onClose !== null) {
				($this->onClose)($player, $data);
			}
		} elseif (is_int($data)) {
			if (!isset($this->buttons[$data])) {
				throw new FormValidationException("\x42\x75\x74\x74\x6f\x6e\x20\x77\x69\x74\x68\x20\x69\x6e\x64\x65\x78\x20" . $data . "\x20\x64\x6f\x65\x73\x20\x6e\x6f\x74\x20\x65\x78\x69\x73\x74");
			}
			if ($this->onSubmit !== null) {
				$button = $this->buttons[$data];
				$button->setValue($data);
				($this->onSubmit)($player, $button);
			}
		} else {
			throw new FormValidationException("\x45\x78\x70\x65\x63\x74\x65\x64\x20\x69\x6e\x74\x20\x6f\x72\x20\x6e\x75\x6c\x6c\x2c\x20\x67\x6f\x74\x20" . gettype($data));
		}
	}
}
