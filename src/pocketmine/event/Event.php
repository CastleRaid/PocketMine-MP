<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

/**
 * Event related classes
 */
namespace pocketmine\event;

abstract class Event{

	/**
	 * Any callable event must declare the static variable
	 *
	 * public static $handlerList = null;
	 *
	 * Not doing so will deny the proper event initialization
	 */

	/** @var string|null */
	protected $eventName = null;
	/** @var bool */
	private $isCancelled = false;
	/** @var bool */
	private $isFinallyCancelled = false;

	/**
	 * @return string
	 */
	final public function getEventName() : string{
		return $this->eventName ?? get_class($this);
	}

	public function isCancellable() : bool{
		return false;
	}

	/**
	 * @return bool
	 *
	 * @throws \BadMethodCallException
	 */
	public function isCancelled() : bool{
		if(!$this->isCancellable()){
			throw new \BadMethodCallException("Event is not Cancellable");
		}

		return $this->isCancelled === true;
	}

	/**
	 * @param bool $value
	 *
	 * @throws \BadMethodCallException
	 */
	public function setCancelled(bool $value = true){
		if(!$this->isCancellable()){
			throw new \BadMethodCallException("Event is not Cancellable");
		}

		$this->isCancelled = $value;
	}

	/**
	 * If an event is set to finally-cancelled, the event will not be cancelled immediately, but will be cancelled just
	 * before the flow returns to {@link PluginManager::callEvent()}'s caller.
	 *
	 * In other words, passing true to this function makes sure the event ends up cancelled, but this is not visible to other plugins from {@link Event::isCancelled()}.
	 *
	 * @param bool $value
	 */
	public function setFinallyCancelled(bool $value = true) : void{
		if(!$this->isCancellable()){
			throw new \BadMethodCallException("Event is not Cancellable");
		}

		$this->isFinallyCancelled = $value;
	}

	/**
	 * @return bool
	 */
	public function isFinallyCancelled() : bool{
		if(!$this->isCancellable()){
			throw new \BadMethodCallException("Event is not Cancellable");
		}

		return $this->isFinallyCancelled;
	}

	/**
	 * @return HandlerList
	 */
	public function getHandlers() : HandlerList{
		if(static::$handlerList === null){
			static::$handlerList = new HandlerList();
		}

		return static::$handlerList;
	}

}
