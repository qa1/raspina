<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%status}}".
 *
 * @property integer $id
 * @property integer $today_visitors
 * @property integer $yesterday_visitors
 * @property integer $this_month_visitors
 * @property integer $last_month_visitors
 * @property integer $total_visitors
 * @property integer $current_month
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%status}}';
    }

    public function visit()
    {
        $model = $this->find()->one();
        $current_time = (int)Yii::$app->date->pdate(time(),'YMMdd');
        $model_time = (int)Yii::$app->date->pdate($model->current_time,'YMMdd');
        $current_month = (int)Yii::$app->date->pdate(time(),'YMM');
        $model_month = (int)Yii::$app->date->pdate($model->current_time,'YMM');
        $today = (int)Yii::$app->date->pdate(strtotime('-1 day',time()),'YMMdd');
        $this_month = (int)Yii::$app->date->pdate(strtotime('-1 month',time()),'YMM');
        $next_days = (int)Yii::$app->date->pdate(strtotime('-2 day',time()),'YMMdd');
        $next_month = (int)Yii::$app->date->pdate(strtotime('-2 month',time()),'YMM');

        if($current_time == $model_time) // today
        {
            $model->today_visitors++;
        }

        if($model_time == $today) // yesterday
        {
            $model->yesterday_visitors = $model->today_visitors;
            $model->today_visitors = 1;
            $model->current_time = time();
        }

        if($model_time <= $next_days) // next days
        {
            $model->yesterday_visitors = 0;
            $model->today_visitors = 1;
            $model->current_time = time();
        }

        if($current_month == $model_month) // this month
        {
            $model->this_month_visitors++;
        }

        if($model_month == $this_month) // last month
        {
            $model->last_month_visitors = $model->this_month_visitors;
            $model->this_month_visitors = 1;
            $model->current_time = time();
        }

        if($model_month <= $next_month) // next month
        {
            $model->last_month_visitors = 0;
            $model->this_month_visitors = 1;
            $model->current_time = time();
        }

        if($current_time < $model_time)
        {
            $model->today_visitors = 1;
            $model->yesterday_visitors = 0;
            $model->last_month_visitors = 0;
            $model->this_month_visitors = 1;
            $model->current_time = time();
        }

        $model->total_visitors++;
        $model->save();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['today_visitors', 'yesterday_visitors', 'this_month_visitors', 'last_month_visitors', 'total_visitors', 'current_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'today_visitors' => Yii::t('app', 'Today Visitors'),
            'yesterday_visitors' => Yii::t('app', 'Yesterday Visitors'),
            'this_month_visitors' => Yii::t('app', 'This Month Visitors'),
            'last_month_visitors' => Yii::t('app', 'Last Month Visitors'),
            'total_visitors' => Yii::t('app', 'Total Visitors'),
            'current_time' => Yii::t('app', 'Current Time'),
        ];
    }
}
