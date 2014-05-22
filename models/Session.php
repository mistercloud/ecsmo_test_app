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
            [['cinema_id', 'hall_id', 'film_id'], 'integer'],
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
    public function getHall()
    {
        return $this->hasOne(Hall::className(), ['id' => 'hall_id', 'cinema_id' => 'cinema_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilm()
    {
        return $this->hasOne(Film::className(), ['id' => 'film_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['session_id' => 'id']);
    }

    public function getTicketPlaces(){
        return $this->hasMany(TicketPlace::className(),['ticket_id'=>'id'])
            ->viaTable('tickets',['session_id' => 'id']);
    }

    public function getFreePlaces(){
        //получаем занятые места
        $ordered_places = [];
        foreach($this->ticketPlaces as $ticket_place){
            $ordered_places[] = (int)$ticket_place['hall_place_id'];
        }
        $places = [];
        foreach($this->hall->hallPlaces as $hall_place){
            $places[] = (int)$hall_place->id;
        }
        $free_places = array_diff($places,$ordered_places);
        //убираем лишние ключи


        return array_values($free_places);
    }
}
