<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Link */

$this->title = Yii::t('app', 'Change Password');
$this->params['breadcrumbs'][] = Yii::t('app', 'Change Password');
?>
<div class="link-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
