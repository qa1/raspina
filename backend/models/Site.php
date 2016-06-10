<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "file".
 *
 * @property string $id
 * @property string $name
 * @property string $size
 * @property string $upload_date
 */
class Site extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%user}}';
    }
    /**
     * @inheritdoc
     */
    public $old_password;
    public $new_password;
    public $repeat_password;
    public function rules()
    {
        return [
            [['old_password','new_password','repeat_password'], 'required'],
            [['old_password','new_password','repeat_password'], 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'old_password' => Yii::t('app', 'Old Password'),
            'new_password' => Yii::t('app', 'New Password'),
            'repeat_password' => Yii::t('app', 'Repeat Password')
        ];
    }
}
