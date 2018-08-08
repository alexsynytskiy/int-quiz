<?php
/** @var \app\models\forms\RegisterForm $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$asset = \app\assets\AppAsset::register($this);

$this->title = 'Реєстрація';
?>

<div class="steps-block register clearfix">
    <div class="logo"></div>
    <div class="step-title"><?= 'День народження компанії' ?></div>
    <div class="step-subtitle"><?= 'Реєстрація' ?></div>
    <div class="social-items">
        <div class="row">
            <?php
            $form = ActiveForm::begin([
                'id' => 'user-register',
                'options' => [
                    'class' => 'link-form',
                    'enctype' => 'multipart/form-data',
                ],
                'fieldConfig' => [
                    'template' => "{input}\n{error}",
                ],
            ]);
            ?>

            <div class="col-md-12 form-z-index clearfix">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => "Ім'я"]) ?>

                <?= $form->field($model, 'surname')->textInput(['maxlength' => true, 'placeholder' => 'Прізвище']) ?>

                <?= $form->field($model, 'nickname')->textInput(['maxlength' => true, 'placeholder' => 'Логін/Нік']) ?>

                <?= $form->field($model, 'userPassword')->passwordInput(['maxlength' => true, 'placeholder' => 'Пароль']) ?>

                <?= $form->field($model, 'passwordRepeat')->passwordInput(['maxlength' => true, 'placeholder' => 'Повторіть пароль']) ?>

                <?= $form->field($model, 'captchaUser')->widget(\yii\captcha\Captcha::className(), [
                    'captchaAction' => 'site/captcha',
                    'options' => [
                        'placeholder' => 'Код перевірки',
                        'autocomplete' => 'off',
                    ],
                    'imageOptions' => [
                        'data-toggle' => "tooltip",
                        'data-placement' => "top",
                        'title' => 'Оновити картинку',
                    ],
                    'template' => '<div class="media-body"><div class="pl-0" style="padding-right: 10px;">{input}</div></div><div class="media-right">{image}</div>',
                ]) ?>

                <?= Html::submitButton('Далі', ['class' => 'link-button']) ?>
                <?= 'Вже зареєстровані? ' . Html::a('Вхід', '/login', ['class' => 'link-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>