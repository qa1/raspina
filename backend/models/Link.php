<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 2000],
            [['url'], 'url']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'url' => Yii::t('app', 'Url'),
        ];
    }

    public static function getLinks()
    {
        $query = new \yii\db\Query();
        $query->select('*')->from(Link::tableName())->orderBy("id DESC");
        $result = $query->all();
        return $result;
    }
}
