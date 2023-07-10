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
use mayuri\form\elements\Element;
use mayuri\form\Form;
use mayuri\form\queue\FormQueue;
use mayuri\form\response\CustomFormResponse;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use function gettype;
use function is_array;

class CustomForm extends Form {
	/** @var Element[] */
	protected array $elements;
	/** @var Closure */
	protected Closure $onSubmit;
	/** @var Closure|null */
	protected ?Closure $onClose;

	public function __construct(string $title, array $elements, Closure $onSubmit, ?Closure $onClose = null) {
		parent::__construct($title);
		$this->elements = $elements;
		$this->onSubmit = $onSubmit;
		$this->onClose = $onClose;
		Utils::validateCallableSignature(function (Player $player, CustomFormResponse $response): void {}, $onSubmit);
		if ($onClose !== null) {
			Utils::validateCallableSignature(function (Player $player, CustomFormResponse $response): void {}, $onClose);
			$this->onClose = $onClose;
		}
	}

	final public function getType(): string {
		return self::TYPE_CUSTOM;
	}

	public function append(Element ...$elements): self {
		$this->elements = array_merge($this->elements, $elements);
		return $this;
	}

	public function serializeFormData(): array {
		return ["\x63\x6f\x6e\x74\x65\x6e\x74" => $this->elements];
	}

	final public function handleResponse(Player $player, $data): void {
		FormQueue::getInstance()->removeForm($player);
		if ($data === null) {
			if ($this->onClose !== null) {
				($this->onClose)($player);
			}
		} elseif (is_array($data)) {
			foreach ($data as $index => $value) {
				if (!isset($this->elements[$index])) {
					throw new FormValidationException("\x45\x6c\x65\x6d\x65\x6e\x74\x20\x61\x74\x20\x69\x6e\x64\x65\x78\x20" . $index . "\x20\x64\x6f\x65\x73\x20\x6e\x6f\x74\x20\x65\x78\x69\x73\x74");
				}
				$element = $this->elements[$index];
				$element->validate($value);
				$element->setValue($value);
			}
			($this->onSubmit)($player, new CustomFormResponse($this->elements));
		} else {
			throw new FormValidationException("\x45\x78\x70\x65\x63\x74\x65\x64\x20\x61\x72\x72\x61\x79\x20\x6f\x72\x20\x6e\x75\x6c\x6c\x2c\x20\x67\x6f\x74\x20" . gettype($data));
		}
	}
}