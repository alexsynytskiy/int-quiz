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
    Вопросы
    <table class="table table-hover">
        <thead>
        <tr>
            <?php if (IS_ROOT) : ?>
                <th width="50">#</th>
            <?php endif; ?>
            <th><?= Yii::t('easyii', 'Text') ?></th>
            <th><?= Yii::t('easyii', 'Correct Answer') ?></th>
            <th><?= Yii::t('easyii', 'Group') ?></th>
            <th width="100"><?= Yii::t('easyii', 'Reward') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->models as $item) : ?>
            <tr data-id="<?= $item->primaryKey ?>">
                <?php if (IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td>
                    <a href="<?= Url::to(['/admin/' . $module . '/a/edit/', 'id' => $item->primaryKey]) ?>"><?= $item->text ?></a>
                </td>
                <td><?= $item->correct_answer ?></td>
                <td><?= $item->group->name ?></td>
                <td><?= $item->reward ?></td>
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