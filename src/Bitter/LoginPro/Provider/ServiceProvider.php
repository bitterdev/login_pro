<?php

namespace Bitter\LoginPro\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Page\Theme\ThemeRouteCollection;
use Concrete\Core\Routing\RouterInterface;
use Bitter\LoginPro\Routing\RouteList;

class ServiceProvider extends Provider
{
    protected RouterInterface $router;
    protected ThemeRouteCollection $themeRouteCollection;

    public function __construct(
        Application          $app,
        RouterInterface      $router,
        ThemeRouteCollection $themeRouteCollection
    )
    {
        parent::__construct($app);

        $this->router = $router;
        $this->themeRouteCollection = $themeRouteCollection;
    }

    public function register()
    {
        $this->registerRoutes();
        $this->registerThemePaths();
    }

    private function registerThemePaths()
    {
        /*
        if (!$this->themeRouteCollection->getThemeByRoute('/account')) {
            $this->themeRouteCollection->setThemeByRoute('/account', 'login_pro');
        }

        if (!$this->themeRouteCollection->getThemeByRoute('/account/*')) {
            $this->themeRouteCollection->setThemeByRoute('/account/*', 'login_pro');
        }
        */

        $this->themeRouteCollection->setThemeByRoute('/register', 'login_pro');
        $this->themeRouteCollection->setThemeByRoute('/login', 'login_pro');
    }

    private function registerRoutes()
    {
        $this->router->loadRouteList(new RouteList());
    }
}