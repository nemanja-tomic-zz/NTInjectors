<?php


/**
 * Created by PhpStorm.
 * User: Nemus
 * Date: 8/24/14
 * Time: 1:11 PM
 */
namespace penisspace {

	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);
	error_reporting(-1);

	require_once "Injector.class.php";
	use NTInjector\Injector;

	interface IPenis {
		/**
		 * @return string
		 */
		function printme();
	}

	class penis implements IPenis {
		public function printme() {
			return "iz penisa izlazi ovo \n";
		}
	}

	class djoka implements IPenis {

		/**
		 * @return string
		 */
		function printme() {
			return "iz djoke izlazi ovooo \n";
		}
	}

	class kurac implements IKurac {

		/**
		 * @var IPenis
		 */
		private $joj;

		public function __construct(IPenis $tuga, $ocaj = "a", $cemer = "b", $jad = "c") {
			$this->joj = $tuga;
			echo "magicni konstruktor\n";
		}

		public function metoda() {
			echo $this->joj->printme();
		}
	}

	interface IKurac {
		function metoda();
	}

	$injector = new Injector();
	$injector->addMapping("penisspace\\IKurac", "penisspace\\kurac");
	$injector->addMapping("penisspace\\IPenis", "penisspace\\djoka");
	/** @var $instanca IKurac */
	$instanca = $injector->resolve("penisspace\\IKurac");
	echo $instanca->metoda();
	die();

	$reflectionClass = new \ReflectionClass("penisspace\\kurac");
	$newInstance = $reflectionClass->newInstance(new penis());
	$newInstance->metoda();
	$reflectionParameters = $reflectionClass->getConstructor()->getParameters();
	foreach ($reflectionParameters as $param) {
		if ($param->isDefaultValueAvailable())
			var_dump($param->getDefaultValue());
	}
}