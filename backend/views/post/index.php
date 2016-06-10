<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'title',
                'value' => 'title',
                'contentOptions' => ['width' => '60%']
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    $postStatus = $model->postStatus();
                    return $postStatus[$model->status];
                },
                'contentOptions' => ['width' => '15%'],
                'filter' => $model->postStatus()
            ],
            [
                'attribute' => 'create_time',
                'value' => function($model){
                    return Yii::$app->date->pdate($model->create_time);
                },
                'contentOptions' => ['width' => '15%'],
                'filter' => ''
            ],
            [
                'class' => 'yii\grid\ActionColumn'
            ],
        ],
    ]); ?>

</div>
