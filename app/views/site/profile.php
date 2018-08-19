<?php

use \app\models\QuestionGroup;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $questionGroups \app\models\QuestionGroup[] */
/* @var $blocksQuestions array */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <?= $this->render('/_blocks/profile-header') ?>

    <div class="separator-space" style="height: 30px;"></div>

    <div class="statistics">
        <div class="statistics-wrapper clearfix">
            <div class="nick"><?= $name ?></div>
            <div class="smarts">
                <div class="icon"></div>
                <?= $points ?> smarts
            </div>
        </div>
    </div>

    <div class="questions">
        <?php foreach ($questionGroups as $group): ?>
            <div class="block <?= $group->active ?> clearfix">
                <div class="left-part">
                    <?php if(in_array($group->active, [
                            QuestionGroup::MISSED,
                            QuestionGroup::ANSWERED],false) && $group->completed_data): ?>
                        <div class="sub-title info"><?= $group->completed_data ?></div>
                    <?php elseif(in_array($group->active, [QuestionGroup::ACTIVE,QuestionGroup::DISABLED],false) || !$group->completed_data): ?>
                        <div class="title"><?= $group->name ?></div>
                        <div class="sub-title"><?= $group->description ?></div>
                    <?php endif; ?>
                </div>
                <div class="right-part">
                    <div class="numbers <?= $group->active ?>">
                        <?php $i = 0; ?>
                        <?php foreach ($group->userAnswers as $userAnswer): ?>
                            <?php if(in_array($group->active, [QuestionGroup::ACTIVE, QuestionGroup::DISABLED],
                                false)): ?>
                                <div class="number"><?= ++$i ?></div>
                            <?php elseif($group->active === QuestionGroup::MISSED): ?>
                                <div class="number">
                                    <div class="state wrong">
                                        <?= ++$i ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="number">
                                    <div class="state <?= $userAnswer->answer && $userAnswer->answer->is_correct ?
                                        'correct' : 'wrong' ?>">
                                        <?= ++$i ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php if($group->active === QuestionGroup::ACTIVE): ?>
                        <div data-hash="<?= $group->hash ?>" id="block-start" class="start enabled">
                            <div class="text title">Старт</div>
                            <div class="text sub-title"><?= \app\models\Question::TIME_FOR_ANSWER / 60 ?> хв</div>
                        </div>
                    <?php elseif($group->active === QuestionGroup::ANSWERED): ?>
                        <div class="start <?= $group->active ?>">
                            <div class="icon"></div>
                        </div>
                    <?php else: ?>
                        <div class="start <?= $group->active ?>">
                            <div class="icon"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?php
$pageOptions = \yii\helpers\Json::encode([
    'startBlockUrl' => '/quiz/start-block/',
]);

$this->registerJs('ProfilePage(' . $pageOptions . ')');
?>

