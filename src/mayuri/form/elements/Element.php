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

namespace mayuri\form\elements;

use pocketmine\form\FormValidationException;
use function gettype;
use function is_int;

abstract class Element implements \JsonSerializable {
	/** @var string */
	protected string $text;
	/** @var mixed */
	protected mixed $value;

	public function __construct(string $text) {
		$this->text = $text;
	}

	public function getValue(): mixed {
		return $this->value;
	}

	public function setValue(mixed $value): void {
		$this->value = $value;
	}

	public function getText(): string {
		return $this->text;
	}

	final public function jsonSerialize(): array {
		$array = ["\x74\x65\x78\x74" => $this->getText()];
		if ($this->getType() !== null) {
			$array["\x74\x79\x70\x65"] = $this->getType();
		}
		return $array + $this->serializeElementData();
	}

	public function validate(mixed $value): void {
		if (!is_int($value)) {
			throw new FormValidationException("\x45\x78\x70\x65\x63\x74\x65\x64\x20\x69\x6e\x74\x2c\x20\x67\x6f\x74\x20" . gettype($value));
		}
	}

	abstract public function getType(): ?string;

	abstract public function serializeElementData(): array;
}