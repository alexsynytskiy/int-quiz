<?php
/** @var $model \yii\easyii\modules\news\models\QuestionSaver */

$this->title = $model->text;
?>

<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>