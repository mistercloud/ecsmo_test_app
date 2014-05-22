<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_places".
 *
 * @property string $ticket_id
 * @property string $hall_place_id
 * @property string $cinema_id
 * @property string $hall_id
 *
 * @property HallPlaces $hallPlace
 */
class TicketPlace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket_places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_id', 'hall_place_id', 'cinema_id', 'hall_id'], 'required'],
            [['ticket_id', 'hall_place_id', 'cinema_id', 'hall_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ticket_id' => 'id билета',
            'hall_place_id' => 'id места',
            'cinema_id' => 'id кинотеатра',
            'hall_id' => 'id зала',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHallPlace()
    {
        return $this->hasOne(HallPlaces::className(), ['id' => 'hall_place_id', 'cinema_id' => 'cinema_id', 'hall_id' => 'hall_id']);
    }
}
