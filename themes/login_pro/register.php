<?php /** @noinspection PhpDeprecationInspection */

defined('C5_EXECUTE') or die('Access denied.');

use Concrete\Core\Attribute\Context\FrontendFormContext;
use Concrete\Core\Attribute\Form\Renderer;
use Concrete\Core\Entity\Attribute\Key\UserKey;
use Concrete\Core\Captcha\CaptchaInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Stack\Stack;
use Concrete\Core\Site\Service;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Support\Facade\Url;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Core\View\View;
use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\File\File;
use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Entity\File\Version;

/** @var Renderer $renderer */
/** @var View $view */
/** @var View $this */
/** @var string $registerSuccess */
/** @var array $successMsg */
/** @var bool $displayUserName */
/** @var int $rcID */
/** @var array $attributeSets */
/** @var UserKey[] $unassignedAttributes */
/** @var array|ErrorList $error */
/** @var string $success */
/** @var string $message */

$app = Application::getFacadeApplication();
/** @var Form $form */
/** @noinspection PhpUnhandledExceptionInspection */
$form = $app->make(Form::class);
/** @var Repository $config */
/** @noinspection PhpUnhandledExceptionInspection */
$config = $app->make(Repository::class);
/** @var Service $siteService */
/** @noinspection PhpUnhandledExceptionInspection */
$siteService = $app->make(Service::class);
$site = $siteService->getSite();
$siteConfig = $site->getConfigRepository();
/** @var CaptchaInterface $captcha */
/** @noinspection PhpUnhandledExceptionInspection */
$captcha = $app->make(CaptchaInterface::class);
/** @var Request $request */
/** @noinspection PhpUnhandledExceptionInspection */
$request = $app->make(Request::class);
/** @var Token $token */
/** @noinspection PhpUnhandledExceptionInspection */
$token = $app->make(Token::class);

/** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/header.php');

$renderer->setContext(new FrontendFormContext());
?>

<main class="centered">
    <div>
        <?php
        $logoUrl = null;

        $logoFileId = (int)$siteConfig->get("login_pro.regular_logo_file_id", 0);
        $logoFile = File::getByID($logoFileId);

        if ($logoFile instanceof FileEntity) {
            $logoVersion = $logoFile->getApprovedVersion();
            if ($logoVersion instanceof Version) {
                $logoUrl = $logoVersion->getRelativePath();
            }
        }
        ?>

        <?php if ($logoUrl !== null) { ?>
            <div class="col-sm-12">
                <img src="<?php echo h($logoUrl); ?>" alt="<?php echo h(t("Home")); ?>"/>
            </div>
        <?php } ?>

        <div class="col-sm-12">
            <?php
            /** @noinspection PhpUnhandledExceptionInspection */
            View::element('system_errors', [
                'format' => 'block',
                'error' => $error ?? null,
                'success' => $success ?? null,
                'message' => $message ?? null,
            ]); ?>
        </div>

        <?php if (!empty($registerSuccess)) { ?>
            <div class="col-sm-12">
                <?php switch ($registerSuccess) { ?>
<?php case 'registered': ?>
                        <?php
                        $stack = Stack::getByName("Register Complete");
                        $stack->display();
                        ?>

                        <?php break; ?>

                    <?php case 'validate': ?>
                        <p>
                            <?php echo $successMsg[0]; ?>
                        </p>

                        <p>
                            <?php echo $successMsg[1]; ?>
                        </p>

                        <p>
                            <a href="<?php echo $view->url('/'); ?>">
                                <?php echo t('Return to Home'); ?>
                            </a>
                        </p>

                        <?php break; ?>

                    <?php case 'pending': ?>
                        <p>
                            <?php echo $successMsg; ?>
                        </p>

                        <p>
                            <a href="<?php echo $view->url('/'); ?>">
                                <?php echo t('Return to Home'); ?>
                            </a>
                        </p>

                        <?php break; ?>

                    <?php } ?>
            </div>
        <?php } else { ?>
            <form method="post" action="<?php echo $view->url('/register', 'do_register'); ?>">
                <?php $token->output('register.do_register'); ?>

                <div class="col-sm-12">
                    <?php if ($displayUserName) { ?>
                        <div class="form-group">
                            <?php echo $form->label('uName', t('Username')); ?>
                            <?php echo $form->text('uName'); ?>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <?php echo $form->label('uEmail', t('Email Address')); ?>
                        <?php echo $form->text('uEmail'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->label('uPassword', t('Password')); ?>
                        <?php echo $form->password('uPassword', ['autocomplete' => 'off']); ?>
                    </div>

                    <?php if ($config->get('concrete.user.registration.display_confirm_password_field')) { ?>
                        <div class="form-group">
                            <?php echo $form->label('uPasswordConfirm', t('Confirm Password')); ?>
                            <?php echo $form->password('uPasswordConfirm', ['autocomplete' => 'off']); ?>
                        </div>
                    <?php } ?>
                </div>

                <?php if (!empty($attributeSets)) { ?>
                    <div class="col">
                        <?php foreach ($attributeSets as $setName => $attibutes) { ?>
                            <fieldset>
                                <?php /** @var UserKey[] $attibutes */ ?>

                                <?php foreach ($attibutes as $attributeKey) {
                                    /** @noinspection PhpUndefinedMethodInspection */
                                    $view = $renderer->buildView($attributeKey);
                                    /** @var UserKey $attributeKey */
                                    if (in_array($attributeKey->getAttributeTypeHandle(), ["address", "boolean"])) {
                                        // hide the label for these attribute types
                                        /** @noinspection PhpUndefinedMethodInspection */
                                        $view->setSupportsLabel(false);
                                    }
                                    /** @noinspection PhpUndefinedMethodInspection */
                                    $view->setIsRequired($attributeKey->isAttributeKeyRequiredOnProfile());
                                    $view->render();
                                } ?>
                            </fieldset>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if (!empty($unassignedAttributes)) { ?>
                    <div class="col-sm-12">
                        <?php foreach ($unassignedAttributes as $attributeKey) {
                            /** @noinspection PhpUndefinedMethodInspection */
                            $view = $renderer->buildView($attributeKey);
                            /** @var UserKey $attributeKey */
                            if (in_array($attributeKey->getAttributeTypeHandle(), ["address", "boolean"])) {
                                // hide the label for these attribute types
                                /** @noinspection PhpUndefinedMethodInspection */
                                $view->setSupportsLabel(false);
                            }
                            /** @noinspection PhpUndefinedMethodInspection */
                            $view->setIsRequired($attributeKey->isAttributeKeyRequiredOnProfile());
                            $view->render();
                        } ?>
                    </div>
                <?php } ?>

                <?php if ($config->get('concrete.user.registration.captcha')) { ?>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php echo $captcha->label(); ?>

                            <?php
                            $captcha->showInput();
                            $captcha->display();
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-sm-12">
                    <div class="float-right">
                        <?php echo $form->hidden('rcID', $rcID ?? ''); ?>

                        <a href="<?php echo Url::to("/"); ?>" class="btn btn-secondary">
                            <?php echo t("Cancel"); ?>
                        </a>

                        <button type="submit" name="register" class="btn btn-primary pull-right">
                            <?php echo t("Create Account"); ?>
                        </button>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-sm-12">
                    <br>

                    <hr/>

                    <a href="<?php echo Url::to('/login'); ?>" class="btn btn-block btn-secondary">
                        <?php echo t("Already have an account?"); ?>
                    </a>
                </div>
            </form>
        <?php } ?>
    </div>
</main>

<?php
/** @noinspection PhpUnhandledExceptionInspection */
$this->inc('elements/footer.php');
?>
