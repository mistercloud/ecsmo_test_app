<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cinemas".
 *
 * @property string $id
 * @property string $name
 * @property string $name_eng
 *
 * @property Halls $halls
 */
class Cinema extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cinemas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'name' => 'Название',
            'name_eng' => 'Транслитерация названия',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHalls()
    {
        return $this->hasOne(Halls::className(), ['cinema_id' => 'id']);
    }
}
