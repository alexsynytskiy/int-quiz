<?php
$this->title = Yii::t('easyii/questions', 'Create question');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>