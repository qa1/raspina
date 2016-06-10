<?php

namespace frontend\controllers;

use Yii;
use backend\models\File;
use backend\models\FileSearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
{
    public function actionDownload($id)
    {
        $sult = Yii::$app->setting->getSult();
        $hashids = new \common\components\hashids\Hashids($sult,5);
        $id = $hashids->decode($id);
        if(!empty($id[0]))
        {
            $model = File::findOne($id[0]);

            if(!empty($model)) {
                $file_path = Yii::getAlias('@file_upload') . '/' . $model->real_name . '.' . $model->extension;
                if (file_exists($file_path))
                {
                    header('Content-Description: File Transfer');
                    header("Content-Type: " . $model->content_type);
                    header('Content-Disposition: attachment; filename="'.$model->name . '.' . $model->extension);
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . $model->size);
                    readfile($file_path);

                    $connection = new Query;
                    $connection->createCommand()->update(File::tableName(),['download_count' => $model->download_count + 1],'id = ' . $id[0])->execute();
                }
            }
        }
    }
}
