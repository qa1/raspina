<?php

namespace backend\controllers;

use Yii;
use backend\models\Newsletter;
use backend\models\NewsletterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
/**
 * NewsletterController implements the CRUD actions for Newsletter model.
 */
class NewsletterController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','update','delete','view'],
                'rules' => [
                    [
                        'actions' => ['index','create','update','delete','view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Newsletter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsletterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Newsletter model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Newsletter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Newsletter();
        $request = Yii::$app->request->post();
        if ($model->load($request))
        {
            $setting = Yii::$app->setting->get();
            $user = User::findOne(Yii::$app->user->getId());
            $request['Newsletter']['text'] = '<div style="direction: rtl; text-align: right; font-size: 12px; font-family: Tahoma">' .  $request['Newsletter']['text'] . '</div><br><br><div style="text-align: center">'.$setting['url'].'</div>';
            $compose = Yii::$app->mailer->compose()
                ->setFrom($user->email)
                ->setTo($model->getAllMails())
                ->setSubject($request['Newsletter']['title'] . ' - ' . $setting['title'])
                ->setHtmlBody($request['Newsletter']['text'])->send();
            if($compose)
            {
                Yii::$app->getSession()->setFlash('success',Yii::t('app','Success Sending Newsletter'));
            }
            else
            {
                Yii::$app->getSession()->setFlash('error',Yii::t('app','Error Sending Newsletter'));
            }

            return $this->redirect(['index', 'id' => $model->id]);
        }
        else
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Newsletter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Newsletter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Newsletter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Newsletter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Newsletter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBulk()
    {
        $action = Yii::$app->request->post('action');
        $selection=(array)Yii::$app->request->post('selection');

        if($action == 'delete')
        {
            Newsletter::deleteAll(['id'=>$selection]);
            Yii::$app->session->setFlash('success', Yii::t('app','Delete Successfully Applied'));
        }

        return $this->redirect(['index']);
    }
}
