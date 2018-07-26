<?php

/* @var $this yii\web\View */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>
<section  style="padding-top: 100px;"></section>

<section class="page-block padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= \app\modules\comment\widgets\CommentWidget::widget([
                    'channelName' => 'main-page',
                    'template'    => 'vidkruvai',
                ]); ?>
            </div>
        </div>
    </div>
</section>