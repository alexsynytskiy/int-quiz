<?php
/** \app\assets\AppAsset $asset */
?>

<!-- Meta Tags -->
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=yes">
<?= \yii\bootstrap\Html::csrfMetaTags() ?>
<meta content="IE=edge" http-equiv="X-UA-Compatible">

<!-- Page Title -->
<title><?= $this->title ?></title>

<!-- Favicon and Apple Touch Icon -->
<link rel="shortcut icon" href="<?= $asset->baseUrl ?>/img/favicon.png" type="image/x-icon">