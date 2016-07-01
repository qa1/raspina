<?php

namespace frontend\controllers;

use backend\models\Category;
use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    public $setting = '';

    public function init()
    {

        // filter out garbage requests
        $uri = Yii::$app->request->url;
        if (strpos($uri, 'favicon.ico') || strpos($uri, 'robots') || strpos($uri, 'bootstrap.min.css.map'))
            Yii::$app->end();
        // do the other initialization stuff


        $this->setting = \Yii::$app->setting->get();
        if(!defined('DATE_FROMAT'))
        {
            define('DATE_FROMAT',$this->setting['date_format']);
        }
        Yii::$app->view->params['templateUrl'] = $this->setting['templateUrl'];

        Yii::$app->view->params['templateDir'] = $this->setting['templateDir'];

        if(!empty(Yii::$app->view->title))
        {
            Yii::$app->view->title = ' - ' . Yii::$app->view->title;
        }
        Yii::$app->view->title = $this->setting['title'] . Yii::$app->view->title;
        Yii::$app->view->params['siteTitle'] = $this->setting['title'];

        Yii::$app->view->params['keywords'] = $this->setting['keyword'];

        Yii::$app->view->params['description'] = $this->setting['description'];
        Yii::$app->view->params['url'] = $this->setting['url'];

        Yii::$app->view->params['categories'] = \backend\models\Category::getAllCategories();
        Yii::$app->view->params['links'] = \backend\models\Link::getLinks();
        $about = new \yii\db\Query();
        Yii::$app->view->params['about'] = $about->select('avatar,name,short_text,facebook,twitter,googleplus,instagram,linkedin')->from(\backend\models\About::tableName())->one();

        Yii::$app->view->params['model'] = new \backend\models\Newsletter;
        Yii::$app->view->params['site'] = new \frontend\models\Site;

        parent::init();

    }

    public function getViewPath()
    {
        return Yii::getAlias('@frontend/views/template/') . $this->setting['template'] . '/';
    }

    public function render($view, $params = [])
    {
        if(Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'rss')
        {
            return parent::render($view,$params);
        }

        // visit +
        $exception = Yii::$app->errorHandler->exception;
        if(empty($exception))
        {
            $visitors = new \backend\models\Visitors;
            $visitors->add();
        }
        return parent::render($view,$params);
    }
}
