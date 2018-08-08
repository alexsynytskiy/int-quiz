<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $points string */

$asset = \app\assets\AppAsset::register($this);

$baseUrl = $asset->baseUrl;
?>
<section  style="padding-top: 100px;"></section>

<section class="page-block padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                Profile for <?= $name . ' ' . $points ?>
            </div>
        </div>
    </div>
</section>