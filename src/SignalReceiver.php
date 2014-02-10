<?php

namespace Nextras\Signals;


trait Receiver
{

	/** @var Helper */
	private $__signalsHelper;



	/**
	 * Provides access to signal functionality.
	 *
	 * @return Helper
	 */
	public function __signals()
	{
		if (!isset($this->__signalsHelper)) {
			$this->__signalsHelper = new Helper($this);
		}
		return $this->__signalsHelper;
	}



	/**
	 * Implementation of ISignalReceiver interface.
	 *
	 * @param  string
	 */
	public function signalReceived($signal)
	{
		$this->__signalsHelper->received($signal);
	}

}
