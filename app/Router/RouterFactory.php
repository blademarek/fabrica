<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('pet', 'Pet:add');
		$router->addRoute('pet/<petId>', 'Pet:edit');
        $router->addRoute('pet/delete/<petId>', 'Pet:delete');
		$router->addRoute('<presenter>/<action>', 'Pet:shop');

		return $router;
	}
}
