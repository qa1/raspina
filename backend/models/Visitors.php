<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%visitors}}".
 *
 * @property integer $id
 * @property string $ip
 * @property integer $visit_date
 * @property string $location
 * @property string $browser
 * @property string $os
 */
class Visitors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%visitors}}';
    }

    public function add()
    {
        $time = time();
        $visitor = new Visitors();
        $visitor->visit_date = $time;
        $visitor->group_date = (int)Yii::$app->date->pdate($time,'YMMdd');
        $visitor->ip = $_SERVER['REMOTE_ADDR'];
        $visitor->location = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $visitor->browser = Yii::$app->browser->getBrowser() . ' ' . Yii::$app->browser->getVersion();
        $visitor->os = Yii::$app->browser->getPlatform();
        $visitor->save();
    }

    public function delete()
    {
        $date = strtotime('-30 day');
        Visitors::deleteAll("visit_date < {$date}");
    }

    public function chart()
    {
        $date = strtotime('-30 day');
        $visitor = new Yii\db\Query();
        $result = $visitor->select('visit_date,group_date,COUNT(id) as `visit`,COUNT(DISTINCT ip) AS `visitor`')->from($this->tableName())->where('visit_date >= ' . $date)->groupBy('group_date')->all();

        $labels = $visit_data = $visitor_data = [];
        $max_visit = 0;
        foreach ((array)$result as $r)
        {
            $labels[] = "'" . Yii::$app->date->pdate($r['visit_date'],'Y/MM/dd') . "'";
            $visit_data[] = "'" . $r['visit'] . "'";
            $visitor_data[] = "'" . $r['visitor'] . "'";

            if( $r['visit'] > $max_visit)
            {
                $max_visit = $r['visit'];
            }
        }

        $labels = '[' . implode(',',$labels) . ']';
        $visit_data = '[' . implode(',',$visit_data) . ']';
        $visitor_data = '[' . implode(',',$visitor_data) . ']';

        $chart = [
            'labels' => $labels,
            'max_visit' => $max_visit,
            'visit' => ['title' => Yii::t('app','Visit'), 'data' => $visit_data],
            'visitor' => ['title' => Yii::t('app','Visitor'), 'data' => $visitor_data],
        ];
        return $chart;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visit_date','group_date'], 'integer'],
            [['ip'], 'string', 'max' => 15],
            [['browser'], 'string', 'max' => 60],
            [['location'], 'string', 'max' => 1000],
            [['os'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ip' => Yii::t('app', 'Ip'),
            'visit_date' => Yii::t('app', 'Visit Date'),
            'location' => Yii::t('app', 'Location'),
            'browser' => Yii::t('app', 'Browser'),
            'os' => Yii::t('app', 'OS')
        ];
    }
}
