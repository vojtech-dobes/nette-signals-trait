## For Nette Framework

Learn any of your components to understand signals!

##### License

New BSD

##### Dependencies

- PHP 5.4
- Nette 2.0.0

## Installation

Get the source code from Github or via Composer (`vojtech-dobes/nette-signals-trait`).

## Usage

1.. Make your component (implementation of `Nette\ComponentModel\IComponent`) implement `Nette\Application\UI\ISignalReceiver` interface.
```php
class AutocompleteField extends Nette\Forms\Controls\TextInput implementes Nette\Application\UI\ISignalReceiver
```

2.. Use `Nextras\Signals\Receiver` trait in your component .
```php
use Nextras\Signals\Receiver;
```

3.. Register signals:
```php
public function __construct()
{
	$this->__signals()->addSignal('autocomplete', function (Nette\Application\UI\Presenter $presenter, $phrase) {
		$presenter->sendJson( ... );
	});
}
```

Now the component supports signal `autocomplete`, in same way as `handleAutocomplete()` method would work in your component inheriting `Nette\Application\UI\Control`.

You will probably also need to create link to it:

```php
$this->__signals()->createLink('autocomplete', 'testPhrase');
```
