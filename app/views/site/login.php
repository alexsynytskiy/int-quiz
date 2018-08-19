<?php
/** @var \app\models\forms\LoginForm $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$asset = \app\assets\AppAsset::register($this);

$this->title = 'Вход на сайт';
?>

<div class="steps-block login clearfix">
    <div class="logo logo-big"></div>
    <div class="step-title"><?= 'Святкування Дня компанії' ?></div>
    <div class="step-subtitle"><?= 'Вхід' ?></div>
    <div class="social-items">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                'id'          => 'user-login',
                'options'     => [
                    'class'   => 'link-form',
                    'enctype' => 'multipart/form-data',
                ],
                'fieldConfig' => [
                    'template' => "{input}\n{error}",
                ],
            ]);
            ?>

            <div class="col-md-12 form-z-index">
                <?= $form->field($model, 'nickname')->textInput(['maxlength' => true, 'placeholder' => 'Логін/Нік']) ?>

                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'Пароль']) ?>

                <?= $form->field($model, 'captchaUser')->widget(\yii\captcha\Captcha::className(), [
                    'captchaAction' => 'site/captcha',
                    'options'       => [
                        'placeholder'  => 'Код перевірки',
                        'autocomplete' => 'off',
                    ],
                    'imageOptions'  => [
                        'data-toggle'    => "tooltip",
                        'data-placement' => "top",
                        'title'          => 'Оновити картинку',
                    ],
                    'template'      => '<div class="media-body"><div class="pl-0" style="padding-right: 10px;">{input}</div></div><div class="media-right">{image}</div>',
                ]) ?>

                <?= Html::submitButton('Далі', ['class' => 'link-button']) ?>
                <div class="already">
                    <?= 'Потрібен аккаунт? ' . Html::a('Реєстрація', '/register', ['class' => 'link-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="profile-header clearfix">
        <div class="profile-navigation">
            <a href='<?= \yii\helpers\Url::to(['/site/help']) ?>' class="link-additional">
                <div class="link-icon">
                    <div class="help"></div>
                </div>
                Техпідтримка
            </a>
        </div>
    </div>
</div>