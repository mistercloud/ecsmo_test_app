<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hall_places".
 *
 * @property string $id
 * @property string $cinema_id
 * @property string $hall_id
 *
 * @property Halls $cinema
 * @property TicketPlaces $ticketPlaces
 */
class HallPlace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hall_places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cinema_id', 'hall_id'], 'required'],
            [['id', 'cinema_id', 'hall_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер места',
            'cinema_id' => 'id кинотеатра',
            'hall_id' => 'id зала',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCinema()
    {
        return $this->hasOne(Halls::className(), ['id' => 'cinema_id', 'cinema_id' => 'hall_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketPlaces()
    {
        return $this->hasOne(TicketPlaces::className(), ['hall_place_id' => 'id', 'cinema_id' => 'cinema_id', 'hall_id' => 'hall_id']);
    }
}
