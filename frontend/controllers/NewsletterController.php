<?php

namespace frontend\controllers;

use frontend\controllers\BaseController;
use Yii;
use frontend\models\Newsletter;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * NewsletterController implements the CRUD actions for Newsletter model.
 */
class NewsletterController extends BaseController
{
    public $layout = '';
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = $this->setting['layout'];
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * join user Newsletter.
     * @return mixed
     */
    public function actionJoin()
    {
        $model = new Newsletter();
        $request = Yii::$app->request->post();
        if($model->load($request) && $model->validate())
        {
            $model->save();
            \Yii::$app->getSession()->setFlash('message', [
                'text' => Yii::t('app','Success Join Newsletter'),
                'class' => 'success'
            ]);
        }
        else
        {
            $error = $model->errors;
            if(!empty($error))
            {
                \Yii::$app->getSession()->setFlash('message', [
                    'text' => $error['email'][0],
                    'class' => 'error'
                ]);
            }
        }
        return $this->redirect(['site/index']);
    }
}
