<?php

namespace frontend\controllers;
use Yii;

class ContactController extends BaseController
{
    public function actions()
    {
        $this->layout = $this->setting['layout'];
    }

    public function actionIndex()
    {
        $contact = new \backend\models\Contact;

        $contact->create_time = time();

        $request = Yii::$app->request->post();
        if($contact->load($request) && $contact->save())
        {
            \Yii::$app->getSession()->setFlash('contact-message', [
                'text' => Yii::t('app','Contact Successfully Sent'),
                'class' => 'success'
            ]);
        }

        Yii::$app->view->title = Yii::t('app','Contact Me');
        return $this->render('contact.twig',[
            'model' => $contact
        ]);
    }

}
