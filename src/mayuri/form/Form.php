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

abstract class Form implements \pocketmine\form\Form {
	/** @var string */
	protected const TYPE_MODAL = "\x6d\x6f\x64\x61\x6c";
	/** @var string */
	protected const TYPE_SIMPLE = "\x66\x6f\x72\x6d";
	/** @var string */
	protected const TYPE_CUSTOM = "\x63\x75\x73\x74\x6f\x6d\x5f\x66\x6f\x72\x6d";
	/** @var string */
	private string $title;

	public function __construct(string $title) {
		$this->title = $title;
	}

	final public function jsonSerialize(): array {
		return array_merge(["\x74\x69\x74\x6c\x65" => $this->getTitle(), "\x74\x79\x70\x65" => $this->getType()], $this->serializeFormData());
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function setTitle(string $title): self {
		$this->title = $title;
		return $this;
	}

	abstract public function getType(): string;

	abstract protected function serializeFormData(): array;
}