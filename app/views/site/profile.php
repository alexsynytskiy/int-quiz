<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */
/* @var $questionGroups \app\models\QuestionGroup[] */
/* @var $blocksQuestions array */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>

<div class="steps-block profile clearfix">
    <div class="profile-header clearfix">
        <div class="logo"></div>
        <div class="profile-navigation">
            <a href="<?= \yii\helpers\Url::to(['/site/logout']) ?>" class="link-additional">
                <div class="link-icon">
                    <div class="exit"></div>
                </div>
                Вихід
            </a>
            <a href="#" class="link-additional">
                <div class="link-icon">
                    <div class="rules"></div>
                </div>
                Правила
            </a>
            <a href="#" class="link-additional">
                <div class="link-icon">
                    <div class="about"></div>
                </div>
                Про подію
            </a>
            <a href="#" class="link-additional">
                <div class="link-icon">
                    <div class="help"></div>
                </div>
                Help
            </a>
        </div>
    </div>

    <div class="separator-space"></div>

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
            <div class="block <?= !$group->active ? 'disabled' : '' ?> clearfix">
                <div class="left-part">
                    <div class="title"><?= $group->name ?></div>
                    <div class="sub-title"><?= $group->description ?></div>
                </div>
                <div class="right-part">
                    <div class="numbers">
                        <div class="number correct">1</div>
                        <div class="number wrong">2</div>
                    </div>
                    <?php if($group->active): ?>
                        <a href="<?= \yii\helpers\Url::to(['/answer-block/' . $group->id]) ?>" class="start enabled">
                            <div class="text title">Старт</div>
                            <div class="text sub-title"><?= \app\models\Question::TIME_FOR_ANSWER / 60 ?> хв</div>
                        </a>
                    <?php else: ?>
                        <div class="start disabled">
                            <div class="icon"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>