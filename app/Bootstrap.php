<?php declare(strict_types = 1);

namespace App;

use Nette\Configurator;
use Tester\Environment;

class Bootstrap
{

	public static function boot(): Configurator
	{
		$configurator = new Configurator();

		$configurator->setDebugMode(true);
		$configurator->enableTracy(__DIR__ . '/../log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator
			->addConfig(__DIR__ . '/config/common.neon')
			->addConfig(__DIR__ . '/config/local.neon');

		return $configurator;
	}


	public static function bootForTests(): Configurator
	{
		$configurator = self::boot();
		Environment::setup();
		return $configurator;
	}

}
