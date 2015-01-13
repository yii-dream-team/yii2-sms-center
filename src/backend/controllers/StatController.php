<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */
namespace yiidreamteam\sms\backend\controllers;

use yiidreamteam\sms\models\Message;

class StatController extends \yii\web\Controller
{
    public $defaultAction = 'dashboard';

    public function actionDashboard()
    {
        $stat = [
            'new' => Message::find()->where(['status' => Message::STATUS_NEW])->count(),
            'sent' => Message::find()->where(['status' => Message::STATUS_SENT])->count(),
            'error' => Message::find()->where(['status' => Message::STATUS_ERROR])->count(),
        ];

        return $this->render('dashboard', ['stat' => $stat]);
    }
}