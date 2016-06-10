<?php
namespace backend\controllers;

use backend\models\Site;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use common\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','login','logout','changePassword'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index','logout','login','changePassword'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    private $_user;
    public function actionChangepassword()
    {
        $model = new Site();

        if ($model->load(Yii::$app->request->post()))
        {
            if($model->new_password != $model->repeat_password)
            {
                Yii::$app->session->setFlash('error', Yii::t('app','Paswword No Mach'));
                return $this->render('changePassword',[
                    'model' => $model
                ]);
            }

            $user = User::findIdentity(Yii::$app->user->id);
            if(!$user->validatePassword($model->old_password))
            {
                Yii::$app->session->setFlash('error', Yii::t('app','Old Paswword No Mach'));
                return $this->render('changePassword',[
                    'model' => $model
                ]);
            }

            $user->setPassword($model->new_password);
            if($user->save())
            {
                Yii::$app->session->setFlash('success', Yii::t('app','New Password Was Saved'));
            }
        }

        return $this->render('changePassword',[
            'model' => $model
        ]);
    }

    public function actionIndex()
    {
        $statusQuery = $visitorsQuery = $filesQuery = new \yii\db\Query();
        $status = $statusQuery->select('*')->from(\backend\models\Status::tableName())->one();
        $visitors = new \backend\models\Visitors;
        $visitorsDataProvider = new ActiveDataProvider([
            'query' => $visitors->find()->orderBy('id DESC')->limit(20),
            'sort' => false,
            'pagination' => false
        ]);

        $posts = new \backend\models\Post;
        $postsDataProvider = new ActiveDataProvider([
            'query' => $posts->find()->where("view > 0")->orderBy('view DESC')->limit(10),
            'sort' => false,
            'pagination' => false
        ]);

        $files = new \backend\models\File;
        $filePostsDataProvider = new ActiveDataProvider([
            'query' => $files->find()->where("download_count > 0")->orderBy('download_count DESC')->limit(10),
            'sort' => false,
            'pagination' => false
        ]);

        return $this->render('index',[
            'status' => $status,
            'visitors' => $visitorsDataProvider,
            'chart' => $visitors->chart(),
            'posts' => $postsDataProvider,
            'postModel' => $posts,
            'files' =>$filePostsDataProvider,
            'fileModel' => $files
        ]);
    }

    public function actionLogin()
    {
        $this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {

            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $visitor = new\backend\models\Visitors;
            $visitor->delete();

            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app','Check Your Email'));

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app','Unable To Reset Password'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        $this->layout = 'login';
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app','New Password Was Saved'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
