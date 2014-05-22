<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "halls".
 *
 * @property string $id
 * @property string $cinema_id
 *
 * @property HallPlaces $hallPlaces
 * @property Cinemas $cinema
 * @property Sessions[] $sessions
 */
class Hall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'halls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cinema_id'], 'required'],
            [['id', 'cinema_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер зала',
            'cinema_id' => 'id кинотеатра',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHallPlaces()
    {
        return $this->hasMany(HallPlace::className(), ['cinema_id' => 'cinema_id', 'hall_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCinema()
    {
        return $this->hasOne(Cinema::className(), ['id' => 'cinema_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Session::className(), ['cinema_id' => 'id', 'hall_id' => 'cinema_id']);
    }
}
