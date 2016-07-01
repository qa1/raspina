<?php

namespace frontend\controllers;

use Yii;
use backend\models\Post;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends BaseController
{
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
     * @inheritdoc
     */
    public function actions()
    {
        $this->layout = $this->setting['layout'];
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id,$title)
    {
        $query = $model = new \yii\db\Query();
        $posTable = \backend\models\Post::tableName();
        $userTable = \common\models\User::tableName();
        $commentTable = \backend\models\Comment::tableName();

        $model->select(["p.*","u.username","COUNT(c.id) AS comment_count","IF(p.more_text IS NOT NULL,'1','0') AS `more`"])->
        from("{$posTable} As p")->leftJoin("{$userTable} AS u","p.author_id = u.id")->
        leftJoin("{$commentTable} AS c","p.id = c.post_id  AND c.status = 1")->
        where("p.id = {$id} AND p.title = '{$title}' AND p.status=1");
        $model = $model->one();

        if(empty($model['title']))
        {
            return $this->redirect(['site/404']);
        }

        if(!\backend\models\Visitors::isBot())
        {
            $query->createCommand()->update($posTable,["view" => $model['view'] + 1],"id=" . $id)->execute();
            $model['view'] = $model['view'] + 1;
        }

        if($model['tags'])
        {
            $model['tags'] = explode(',',$model['tags']);
        }


        if($model['keywords'])
        {
            Yii::$app->view->params['moreKeywords'] = ',' . $model['keywords'];
        }
        if($model['meta_description'])
        {
            Yii::$app->view->params['moreDescription'] = '-' . $model['meta_description'];
        }

        $comment = new \backend\models\Comment;
        $comment->scenario = 'post-view';
        // insert comment
        $comment->post_id = $id;
        $comment->create_time = time();

        $request = Yii::$app->request->post();

        if($comment->load($request) && $comment->save())
        {
            \Yii::$app->getSession()->setFlash('comment-message', [
                'text' => Yii::t('app','Comment Successfully Sent'),
                'class' => 'success'
            ]);
        }

        // show post comments
        $query = new \yii\db\Query();
        $comments = $query->select("*")->from($commentTable)->where("post_id = {$id} AND status = 1")->orderBy("id DESC")->all();
        $postModel = new \backend\models\Post();
        Yii::$app->view->title = $model['title'];
        return $this->render('post.twig', [
            'model' => $model,
            'comment' => $comment,
            'comments' => $comments,
            'postModel' => $postModel
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
