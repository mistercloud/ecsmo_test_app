<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tickets".
 *
 * @property string $id
 * @property string $session_id
 * @property string $code
 * @property string $created_at
 *
 * @property Sessions $session
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id'], 'required'],
            [['session_id'], 'integer'],
            [['created_at'], 'safe'],
            [['code'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id билета',
            'session_id' => 'id сессии',
            'code' => 'код',
            'created_at' => 'время покупки билета',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSession()
    {
        return $this->hasOne(Sessions::className(), ['id' => 'session_id']);
    }
}
