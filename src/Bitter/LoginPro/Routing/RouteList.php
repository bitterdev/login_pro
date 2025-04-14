<?php

namespace Bitter\LoginPro\Routing;

use Bitter\LoginPro\API\V1\Middleware\FractalNegotiatorMiddleware;
use Bitter\LoginPro\API\V1\Configurator;
use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;

class RouteList implements RouteListInterface
{
    public function loadRoutes(Router $router)
    {
        $router
            ->buildGroup()
            ->setNamespace('Concrete\Package\LoginPro\Controller\Dialog\Support')
            ->setPrefix('/ccm/system/dialogs/login_pro')
            ->routes('dialogs/support.php', 'login_pro');
    }
}