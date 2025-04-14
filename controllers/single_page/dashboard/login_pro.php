<?php

namespace Concrete\Package\LoginPro\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class LoginPro extends DashboardPageController
{
    public function view()
    {
        return $this->buildRedirectToFirstAccessibleChildPage();
    }
}
