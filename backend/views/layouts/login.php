<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <title>رسپینا - <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript">
      var controller_name = '<?= Yii::$app->controller->id ?>';
    </script>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
  <div class="login-box">
    <div class="login-box-body">
  <?php $this->beginBody() ?>
    <?= Alert::widget() ?>
    <?= $content ?>
  <?php $this->endBody() ?>
    </div>
  </div>
  </body>
</html>
<?php $this->endPage() ?>