<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Routing\Router;

/**
 * @var Router $router
 * Base path: /ccm/system/dialogs/login_pro
 * Namespace: Concrete\Package\LoginPro\Controller\Dialog
 */

$router->all('/create_ticket', 'CreateTicket::view');
$router->all('/create_ticket/submit', 'CreateTicket::submit');