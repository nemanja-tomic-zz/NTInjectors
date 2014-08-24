<?php
namespace NTInjector;

/**
 * Created by PhpStorm.
 * User: Nemus
 * Date: 8/24/14
 * Time: 1:29 PM
 */
class Injector {

	/**
	 * @var array
	 */
	private $mappedTypes;

	public function __construct() {
		$this->mappedTypes = array();
	}

	public function addMapping($sourceType, $destinationType) {
		if (!array_key_exists($sourceType, $this->mappedTypes)) {
			$this->mappedTypes[$sourceType] = $destinationType;
		}
	}

	public function resolve($sourceType) {
		if (array_key_exists($sourceType, $this->mappedTypes)) {
			return $this->createInstance($sourceType);
		} else {
			throw new \InvalidArgumentException("Couldn't resolve ".$sourceType.", no mapping found.");
		}
	}

	private function createInstance($sourceType) {
		$newInstance = null;

		$reflectionClass = new \ReflectionClass($this->mappedTypes[$sourceType]);
		$reflectionConstructor = $reflectionClass->getConstructor();
		$constructorParameters = ($reflectionConstructor != null) ? $reflectionConstructor->getParameters() : array();

		if (count($constructorParameters) == 0) {
			$newInstance = $reflectionClass->newInstance();
		} else {
			//creating list of resolved constructor parameters and resolving them recursively.
			$parameters = array();
			/** @var $parameter \ReflectionParameter */
			foreach ($constructorParameters as $parameter) {
				$parameterType = $parameter->getClass();

				if ($parameterType != null)
					$parameters[] = $this->resolve($parameterType->getName());
				else {
					$parameters[] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : "";
				}
			}
			$newInstance = $reflectionClass->newInstanceArgs($parameters);
		}
		return $newInstance;
	}
}