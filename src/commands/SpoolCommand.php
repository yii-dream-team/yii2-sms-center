<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace yiidreamteam\sms\commands;

use yii\console\Controller;
use yiidreamteam\sms\models\Message;
use yiidreamteam\sms\SmsCenter;

class SpoolCommand extends Controller
{
    public $componentName = 'sms';
    /** @var SmsCenter */
    protected $component;

    public function init()
    {
        $this->component = \Yii::$app->get($this->componentName);
    }

    public function actionSpool($loopLimit = 1000, $chunkSize = 50)
    {
        set_time_limit(0);
        for ($i = 1; $i < $loopLimit; $i++) {
            for ($j = 1; $j < $chunkSize; $j++) {
                $r = $this->actionSendOne();
                if (!$r)
                    break 1;
            }
            sleep(1);
        }
    }

    public function actionSendOne()
    {
        $db = \Yii::$app->db;

        /** @var Message $model */
        $model = Message::find()
            ->where(['status' => Message::STATUS_NEW])
            ->orderBy(['id' => SORT_ASC, 'priority' => SORT_DESC])
            ->one();

        if (!$model)
            return false;

        $transaction = $db->beginTransaction();
        try {
            $model->status = $this->component->send($model->to, $model->text)
                ? Message::STATUS_SENT : Message::STATUS_ERROR;
            $model->updateAttributes(['status']);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }

        return true;
    }
}