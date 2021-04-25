<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "workshops".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 *
 * @property UserWorkshops[] $userWorkshops
 */
class Workshops extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
	{
        return 'workshops';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
	{
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
	{
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[UserWorkshops]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserWorkshops()
	{
        return $this->hasMany(UserWorkshops::className(), ['workshops_id' => 'id']);
    }
}
