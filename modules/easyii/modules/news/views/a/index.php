<?php

/* @var $this yii\web\View */
/* @var $data yii\data\ActiveDataProvider */

use yii\easyii\modules\news\models\News;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('easyii/questions', 'Questions');

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="100">#</th>
            <th>Вопрос</th>
            <th>Правильный ответ</th>
            <th>Блок вопросов</th>
            <th width="100">Награда за правильный ответ</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->models as $item) : ?>
            <tr data-id="<?= $item->primaryKey ?>">
                <td><?= $item->primaryKey ?></td>
                <td>
                    <a href="<?= Url::to(['/admin/' . $module . '/a/edit/', 'id' => $item->primaryKey]) ?>"><?= $item->text ?></a>
                </td>
                <td><?= $item->correct_answer ?></td>
                <td><?= $item->group->name ?></td>
                <td><?= $item->reward ?></td>
            </tr>
            <tr data-id="<?= $item->primaryKey ?>">
                <?php $answers = $item->answers; ?>
                <td>Ответы</td>
                <?php foreach ($answers as $answer): ?>
                    <td <?= $answer->is_correct ? 'style="font-weight: bold;"' : '' ?>><?= $answer->text ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?= yii\widgets\LinkPager::widget([
        'pagination' => $data->pagination
    ]) ?>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>