<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "films".
 *
 * @property string $id
 * @property string $name
 * @property string $name_eng
 *
 * @property Sessions[] $sessions
 */
class Film extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'films';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_eng'], 'required'],
            [['name', 'name_eng'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название фильма',
            'name_eng' => 'Транслитерация названия фильма',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessions()
    {
        return $this->hasMany(Sessions::className(), ['film_id' => 'id']);
    }
}
