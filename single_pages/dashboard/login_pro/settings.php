<?php

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Application\Service\FileManager;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Form\Service\Widget\Color;
use Concrete\Core\Form\Service\Widget\PageSelector;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Core\View\View;

/** @var int $logoFileId */
/** @var string $accentColor */

$app = Application::getFacadeApplication();
/** @var Form $form */
$form = $app->make(Form::class);
/** @var Token $token */
$token = $app->make(Token::class);
/** @var FileManager $fileManager */
$fileManager = $app->make(FileManager::class);
/** @var PageSelector $pageSelector */
$pageSelector = $app->make(PageSelector::class);
/** @var Color $colorPicker */
$colorPicker = $app->make(Color::class);

?>

<div class="ccm-dashboard-header-buttons">
    <?php View::element("dashboard/help", [], "login_pro"); ?>
</div>

<?php View::element("dashboard/did_you_know", [], "login_pro"); ?>

<form action="#" method="post">´
    <?php echo $token->output("update_settings"); ?>

    <fieldset>
        <legend>
            <?php echo t("General"); ?>
        </legend>

        <div class="form-group">
            <?php echo $form->label("logoFileId", t("Logo")); ?>
            <?php echo $fileManager->image("logoFileId", "logoFileId", t("Please select a file"), $logoFileId); ?>
        </div>

        <div class="form-group">
            <?php echo $form->label("logoFileId", t("Logo")); ?>

            <div>
                <?php $colorPicker->output("accentColor", $accentColor, []); ?>
            </div>
        </div>
    </fieldset>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <?php echo $form->submit('save', t('Save'), ['class' => 'btn btn-primary float-end']); ?>
        </div>
    </div>
</form>