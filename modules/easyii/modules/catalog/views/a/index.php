<?php

/* @var $this yii\web\View */
/* @var $data yii\data\ActiveDataProvider */

use yii\easyii\modules\news\models\News;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Users';

$module = $this->context->module->id;
?>

<?= $this->render('_menu') ?>

<?php if ($data->count > 0) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <?php if (IS_ROOT) : ?>
                <th width="50">#</th>
            <?php endif; ?>
            <th><?= Yii::t('easyii', 'Имя') ?></th>
            <th><?= Yii::t('easyii', 'Фамилия') ?></th>
            <th><?= Yii::t('easyii', 'Ник') ?></th>
            <th><?= Yii::t('easyii', 'Пароль') ?></th>
            <th><?= Yii::t('easyii', 'Смартов') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data->models as $item) : ?>
            <tr data-id="<?= $item->primaryKey ?>">
                <?php if (IS_ROOT) : ?>
                    <td><?= $item->primaryKey ?></td>
                <?php endif; ?>
                <td><?= $item->name ?></td>
                <td><?= $item->surname ?></td>
                <td><?= $item->nickname ?></td>
                <td><?= $item->password_admins ?></td>
                <td><?= $item->total_smart ?></td>
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