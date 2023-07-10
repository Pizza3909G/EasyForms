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
use mayuri\form\Form;
use mayuri\form\queue\FormQueue;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use function gettype;
use function is_bool;

class ModalForm extends Form {
	/** @var string */
	protected string $text;
	/** @var string */
	private string $yesButton;
	/** @var string */
	private string $noButton;
	/** @var Closure */
	private Closure $onSubmit;

	public function __construct(string $title, string $text, Closure $onSubmit, string $yesButton = "\x67\x75\x69\x2e\x79\x65\x73", string $noButton = "\x67\x75\x69\x2e\x6e\x6f") {
		parent::__construct($title);
		$this->text = $text;
		$this->yesButton = $yesButton;
		$this->noButton = $noButton;
		Utils::validateCallableSignature(function (Player $player, bool $response): void {}, $onSubmit);
		$this->onSubmit = $onSubmit;
	}

	public static function createConfirmForm(string $title, string $text, Closure $onConfirm): self {
		Utils::validateCallableSignature(function (Player $player): void {}, $onConfirm);
		return new self($title, $text, function (Player $player, bool $response) use ($onConfirm): void {
			if ($response) {
				$onConfirm($player);
			}
		});
	}

	final public function getType(): string {
		return self::TYPE_MODAL;
	}

	public function getYesButtonText(): string {
		return $this->yesButton;
	}

	public function getNoButtonText(): string {
		return $this->noButton;
	}

	protected function serializeFormData(): array {
		return ["\x63\x6f\x6e\x74\x65\x6e\x74" => $this->text, "\x62\x75\x74\x74\x6f\x6e\x31" => $this->yesButton, "\x62\x75\x74\x74\x6f\x6e\x32" => $this->noButton];
	}

	final public function handleResponse(Player $player, $data): void {
		FormQueue::getInstance()->removeForm($player);
		if (!is_bool($data)) {
			throw new FormValidationException("\x45\x78\x70\x65\x63\x74\x65\x64\x20\x62\x6f\x6f\x6c\x2c\x20\x67\x6f\x74\x20" . gettype($data));
		}
		($this->onSubmit)($player, $data);
	}
}