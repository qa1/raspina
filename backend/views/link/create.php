<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Link */

$this->title = Yii::t('app', 'Create Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
