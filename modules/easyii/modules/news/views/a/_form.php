<?php
/** @var $model \yii\easyii\modules\news\models\QuestionSaver */

use yii\easyii\helpers\Image;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$module = $this->context->module->id;

$asset = \yii\easyii\modules\news\assets\NewsAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

Вопрос
    <div class="col-md-12 clearfix">
        <?= $form->field($model, 'text')->textarea() ?>

        <div class="col-md-6">
            <?= $form->field($model, 'group_id')->widget(\kartik\select2\Select2::className(), [
                'data' => \app\models\QuestionGroup::getGroups(),
                'language' => Yii::$app->language,
                'options' => ['placeholder' => Yii::t('easyii', 'No')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'image')->widget(\kartik\file\FileInput::className(), [
                'options' => [
                    'accept' => 'image/*'
                ],
                'pluginOptions' => [
                    'showRemove' => false,
                    'initialPreview' => [
                        isset($model->image) ? Image::thumb($model->image, 240) : null
                    ],
                    'initialPreviewAsData' => true,
                    'initialPreviewConfig' => [
                        [
                            'url' => Url::to(['/admin/' . $module . '/a/clear-image', 'id' => $model->primaryKey]),
                        ],
                    ],
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'reward')->input('text') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'correct_answer')->input('text') ?>
        </div>
    </div>
    <div></div>
Ответы
    <div class="col-md-12 clearfix">
        <div class="col-md-6">
            <?= $form->field($model, 'answerOneText')->input('text') ?>
            <?= $form->field($model, 'answerOneCorrect')->checkbox() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'answerTwoText')->input('text') ?>
            <?= $form->field($model, 'answerTwoCorrect')->checkbox() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'answerThreeText')->input('text') ?>
            <?= $form->field($model, 'answerThreeCorrect')->checkbox() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'answerFourText')->input('text') ?>
            <?= $form->field($model, 'answerFourCorrect')->checkbox() ?>
        </div>
    </div>
    <div></div>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>