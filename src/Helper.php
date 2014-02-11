<?php

namespace Nextras\Signals;

use Nette\Application\UI;
use Nette\ComponentModel\IComponent;


class Helper
{

	/** @var UI\ISignalReceiver */
	private $component;

	/** @var array */
	private $signals = [];



	public function __construct(UI\ISignalReceiver $component)
	{
		$this->component = $component;
	}



	/**
	 * Registers new signal.
	 *
	 * @param  string
	 * @param  callable
	 * @return SignalSupport provides a fluent interface
	 */
	public function addSignal($name, $handler)
	{
		$this->signals[$name] = $handler;
		return $this;
	}



	/**
	 * Creates link to given signal.
	 *
	 * @param  string
	 * @param  mixed
	 * @return UI\Link
	 */
	public function createLink($name)
	{
		$this->verifySignal($name);

		$args = func_get_args();
		array_shift($args);

		$prefix = $this->component->lookupPath('Nette\Application\UI\Presenter') . IComponent::NAME_SEPARATOR;

		$arguments = [];
		if (isset($args[0]) && is_array($args[0])) {
			foreach ($args[0] as $key => $value) {
				$arguments[$prefix . $key] = $value;
			}
		} else {
			$reflection = new \Nette\Reflection\GlobalFunction($this->signals[$name]);
			$parameters = $reflection->getParameters();
			array_shift($parameters); // skip UI\Presenter
			foreach ($parameters as $parameter) {
				$arguments[$prefix . $parameter->getName()] = array_shift($args);
				if (count($args) === 0) {
					break;
				}
			}
		}

		return new UI\Link(
			$this->component->lookup('Nette\Application\UI\Presenter'),
			$prefix . $name . '!',
			$arguments
		);
	}



	/**
	 * Tries to fire signal with given name.
	 *
	 * @param  string
	 */
	public function received($name)
	{
		$this->verifySignal($name);

		$presenter = $this->component->lookup('Nette\Application\UI\Presenter');
		$args = $presenter->popGlobalParameters(
			$this->component->lookupPath('Nette\Application\UI\Presenter')
		);
		array_unshift($args, $presenter);

		call_user_func_array(
			$this->signals[$name],
			$args
		);
	}



	private function verifySignal($name)
	{
		if (!isset($this->signals[$name])) {
			$class = get_class($this->component);
			throw new UI\BadSignalException("There is no handler for signal '$name' in class $class.");
		}
	}

}
