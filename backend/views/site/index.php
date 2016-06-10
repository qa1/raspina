<?php

/* @var $this yii\web\View */
use yii\grid\GridView;
$this->title = Yii::t('app','Statistics and information');

$this->registerJsFile(Yii::$app->homeUrl . 'js/chart.bundle.min.js');
$this->registerJsFile(Yii::$app->homeUrl . 'js/visitor_chart.js');
?>
<style>
    canvas{
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    .grid-view {width: 100%;}
</style>

<div class="row">
    <div class="col-md-9">
        <span class="status-title"><?= Yii::t('app','Last Visited') ?></span>
        <div class="status-box" style="padding-right: 0px">
            <span>
        <div class="last-visitors" style="width: 100%">
            <!-- -->
            <?= GridView::widget([
                'dataProvider' => $visitors,
                'columns' => [
                    'ip',
                    [
                        'attribute' => 'visit_date',
                        'value' => function($visitors){
                            return Yii::$app->date->pdate($visitors->visit_date);
                        }
                    ],
                    [
                        'attribute' => 'location',
                        'format' => 'raw',
                        'value' => function($visitors){
                            return '<a href="'.$visitors->location.'" target="_blank">['.Yii::t('app','View').']</a>';
                        }
                    ],
                    'os',
                    'browser'

                ],
            ]); ?>
            <!-- -->
        </div>
                </span>
            </div>
    </div>
    <div class="col-md-3 web-status">
        <span class="status-title"><?= Yii::t('app','Viewers Statistics') ?></span>
        <div class="status-box">
            <span>
                <div><?= Yii::t('app','Today') ?>: <span><?= Yii::$app->date->pdate(time(),'Y/MM/dd') ?></span></div>
                <div><?= Yii::t('app','Today Visitors') ?>: <span><?= $status['today_visitors'] ?></span></div>
                <div><?= Yii::t('app','Yesterday Visitors') ?>: <span><?= $status['yesterday_visitors'] ?></span></div>
                <div><?= Yii::t('app','This Month Visitors') ?>: <span><?= $status['this_month_visitors'] ?></span></div>
                <div><?= Yii::t('app','Last Month Visitors') ?>: <span><?= $status['last_month_visitors'] ?></span></div>
                <div><?= Yii::t('app','Total Visitors') ?>: <span><?= $status['total_visitors'] ?></span></div>
             </span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <span class="status-title"><?= Yii::t('app','The Views Graph 30 Days') ?></span>
        <div class="status-box">
            <span>
<!-- -->
<div style="width:100%;">
    <canvas id="canvas" height="100"></canvas>
    <script>
        var chart_labels = <?= $chart['labels']; ?>;
        var chart_max_visit = <?= $chart['max_visit']; ?>;
        var visit_labels = '<?= $chart['visit']['title']; ?>';
        var visit_data = <?= $chart['visit']['data']; ?>;
        var visitor_labels = '<?= $chart['visitor']['title']; ?>';
        var visitor_data = <?= $chart['visitor']['data']; ?>;
    </script>
</div>
<!-- -->
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <span class="status-title"><?= Yii::t('app','Most Viewed Posts') ?></span>
        <div class="status-box" style="padding-right: 0px;">
            <span>
<!-- -->
                <?= GridView::widget([
                    'dataProvider' => $posts,
                    'columns' => [
                        [
                            'attribute' => 'title',
                            'value' => 'title',
                        ],
                        [
                            'attribute' => 'view',
                            'value' => 'view',
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function($postModel){
                                $postStatus = $postModel->postStatus();
                                return $postStatus[$postModel->status];
                            },
                            'filter' => $postModel->postStatus()
                        ],
                        [
                            'attribute' => 'create_time',
                            'value' => function($postModel){
                                return Yii::$app->date->pdate($postModel->create_time);
                            },
                            'filter' => ''
                        ],
                    ],
                ]); ?>
<!-- -->
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <span class="status-title"><?= Yii::t('app','Most Viewed Files') ?></span>
        <div class="status-box" style="padding-right: 0px;">
            <span>
<!-- -->
                <?= GridView::widget([
                    'dataProvider' => $files,
                    'columns' => [
                        [
                            'attribute' => 'name'
                        ],
                        'extension',
                        [
                            'attribute' => 'size',
                            'value' => function($fileModel){
                                if ($fileModel->size<1048676)
                                    return number_format($fileModel->size/1024,1) . ' ' . Yii::t('app','KB');
                                else
                                    return number_format($fileModel->size/1048576,1) . ' ' . Yii::t('app','MB');
                            },
                            'filter' => ''
                        ],
                        [
                            'attribute' => 'download_count',
                            'filter' => ''
                        ],
                        [
                            'attribute' => 'upload_date',
                            'value' => function($fileModel){
                                return Yii::$app->date->pdate($fileModel->upload_date);
                            },
                            'filter' => ''
                        ]
                    ],
                ]); ?>
<!-- -->
            </span>
        </div>
    </div>
</div>