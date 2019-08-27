<?php

declare (strict_types = 1);

namespace App\Orchid\Fields;

use Orchid\Screen\Field;

/**
 * Class Label.
 *
 * @method self name(string $value = null)
 * @method self popover(string $value = null)
 */
class QRCode extends Field {
	/**
	 * @var string
	 */
	public $view = 'platform::fields.qrcode';

	public $attributes = [
		'id' => null,
		'value' => null,
	];

	public $inlineAttributes = [
		'class',
		'value',
	];

	/**
	 * @param string|null $name
	 *
	 * @return QRCode
	 */
	public static function make(string $name = null): self {
		return (new static())->name($name);
	}
}
