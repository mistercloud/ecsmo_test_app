<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sessions".
 *
 * @property string $id
 * @property string $cinema_id
 * @property string $hall_id
 * @property string $film_id
 * @property string $start_at
 *
 * @property Halls $cinema
 * @property Films $film
 * @property Tickets[] $tickets
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sessions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'cinema_id', 'hall_id', 'film_id'], 'integer'],
            [['start_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id сеанса',
            'cinema_id' => 'id кинотеатра',
            'hall_id' => 'id зала',
            'film_id' => 'id фильма',
            'start_at' => 'дата начала',
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
    public function getFilm()
    {
        return $this->hasOne(Films::className(), ['id' => 'film_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Tickets::className(), ['session_id' => 'id']);
    }
}
