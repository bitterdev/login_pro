<?php

namespace Concrete\Package\LoginPro;

use Bitter\LoginPro\Provider\ServiceProvider;
use Concrete\Core\Entity\Package as PackageEntity;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    protected string $pkgHandle = 'login_pro';
    protected string $pkgVersion = '0.0.2';
    protected $appVersionRequired = '9.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/Bitter/LoginPro' => 'Bitter\LoginPro',
    ];

    public function getPackageDescription(): string
    {
        return t("Login Pro for Concrete CMS is a customizable mini-theme that matches your login screens to your site's corporate identity.");
    }

    public function getPackageName(): string
    {
        return t('Login Pro');
    }

    public function on_start()
    {
        /** @var ServiceProvider $serviceProvider */
        /** @noinspection PhpUnhandledExceptionInspection */
        $serviceProvider = $this->app->make(ServiceProvider::class);
        $serviceProvider->register();
    }

    public function install(): PackageEntity
    {
        $pkg = parent::install();
        $this->installContentFile("data.xml");
        return $pkg;
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->installContentFile("data.xml");
    }
}