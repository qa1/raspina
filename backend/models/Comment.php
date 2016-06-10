<?php

namespace backend\models;

use Yii;
use yii\captcha\Captcha;

/**
 * This is the model class for table "comment".
 *
 * @property string $id
 * @property string $post_id
 * @property string $email
 * @property string $text
 * @property integer $status
 *
 * @property Post $post
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public $post_title;
    public $captcha;
    public function rules()
    {
        return [
            [['post_id','text','name'], 'required'],
            [['post_id', 'status','create_time'], 'integer'],
            [['text','reply_text'], 'string'],
            [['email'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 60],
            [['email'], 'email'],
            ['captcha', 'captcha','on' => 'post-view']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'post_title' => Yii::t('app', 'Post Title'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'text' => Yii::t('app', 'Text'),
            'status' => Yii::t('app', 'Status'),
            'reply_text' => Yii::t('app', 'Reply'),
            'create_time' => Yii::t('app', 'Create Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function getCommentStatus()
    {
        return [
            0 => Yii::t('app','Not Accepted'),
            1 => Yii::t('app','Accepted')
        ];
    }

    public function getCountNotAccepted()
    {
        $query = new \yii\db\Query();
        return $query->select('id')->from($this->tableName())->where(['status' => 0])->count();
    }
}
