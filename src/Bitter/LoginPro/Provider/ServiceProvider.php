<?php

namespace Bitter\LoginPro\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Html\Service\Html;
use Concrete\Core\Page\Theme\ThemeRouteCollection;
use Concrete\Core\Routing\RouterInterface;
use Bitter\LoginPro\Routing\RouteList;
use Concrete\Core\View\View;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ServiceProvider extends Provider
{
    protected RouterInterface $router;
    protected ThemeRouteCollection $themeRouteCollection;
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        Application              $app,
        RouterInterface          $router,
        ThemeRouteCollection     $themeRouteCollection,
        EventDispatcherInterface $eventDispatcher
    )
    {
        parent::__construct($app);

        $this->router = $router;
        $this->themeRouteCollection = $themeRouteCollection;
        $this->eventDispatcher = $eventDispatcher;
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

        // Workaround for 9.4
        if (defined('APP_VERSION')) {
            if (version_compare(APP_VERSION, '9.4.0', '>=')) {
                $this->eventDispatcher->addListener("on_before_render", function () {
                    $v = View::getInstance();
                    /** @var Html $htmlService */
                    /** @noinspection PhpMultipleClassDeclarationsInspection */
                    $htmlService = $this->app->make(Html::class);
                    $v->addHeaderAsset($htmlService->css("css/skins/default.css", "login_pro"));
                });
            }
        }
    }

    private function registerRoutes()
    {
        $this->router->loadRouteList(new RouteList());
    }
}