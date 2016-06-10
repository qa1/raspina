<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "newsletter".
 *
 * @property string $id
 * @property string $email
 */
class Newsletter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%newsletter}}';
    }

    /**
     * @inheritdoc
     */
    public $title;
    public $text;
    public $mail;
    public function rules()
    {
        return [
            [['email','title','text'], 'required'],
            [['email','title'], 'string', 'max' => 255],
            [['text'], 'string'],
            [['email'], 'email']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
        ];
    }

    public function getAllMails()
    {
        $model = Newsletter::find()->all();

        $mails = \yii\helpers\ArrayHelper::getColumn($model,function($element){
            return $element['email'];
        });

        return $mails;
    }

    public function getCountMails()
    {
        $query = new \yii\db\Query();
        return $query->select('id')->from($this->tableName())->count();
    }
}
