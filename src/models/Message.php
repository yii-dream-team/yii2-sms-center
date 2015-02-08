<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace yiidreamteam\sms\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%sms_message}}".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $priority
 * @property string $to
 * @property string $text
 * @property string $transport
 * @property string $createdAt
 * @property string $updatedAt
 */
class Message extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_SENT = 1;
    const STATUS_ERROR = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms_message}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createdAt', 'updatedAt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updatedAt',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'priority'], 'integer'],
            [['to', 'text', 'transport'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'priority' => 'Priority',
            'transport' => 'Transport',
            'to' => 'To',
            'text' => 'Text',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @param $to
     * @param $text
     * @param int $priority
     * @param null|string $transport
     */
    public static function queue($to, $text, $priority = 0, $transport = null)
    {
        $model = new static([
            'to' => $to,
            'text' => $text,
            'priority' => $priority,
            'transport' => $transport,
            'status' => static::STATUS_NEW
        ]);
        return $model->save(false);
    }
}
