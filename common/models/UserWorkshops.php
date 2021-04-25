<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_workshops".
 *
 * @property int $id
 * @property int $workshops_id
 * @property int $user_id
 *
 * @property User $user
 * @property Workshops $workshops
 * @property int $is_send_mail
 */
class UserWorkshops extends ActiveRecord
{

	public const APPEND_USER = "append_user";

	public  $user_ids = [];

	public function init()
	{
		$this->on(self::APPEND_USER,[Yii::$app->emailService,'sendSubscribed']);
		parent::init();
	}

	/**
     * {@inheritdoc}
     */
    public static function tableName(): string
	{
        return 'user_workshops';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
	{
        return [
			[['workshops_id'], 'required'],
            [['user_ids'], 'required'],
            [['workshops_id'], 'integer'],
			[['user_ids'], 'checkUniqueness'],
			['user_ids', 'each', 'rule' => [
				'exist', 'targetClass' => User::className(), 'targetAttribute' =>  'id'
				]
			],
            [['workshops_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workshops::className(), 'targetAttribute' => ['workshops_id' => 'id']],
        ];
    }

	/**
	 * {@inheritdoc}
	 */
	public function checkUniqueness($attribute,$params): void
	{
		$model = self::find()->where(['workshops_id'=>$this->workshops_id])->andWhere(['user_id'=>$this->user_ids])
			->all();
		if(!empty($model)) {
			$this->addError('id', 'This id, category and language already exist');
		}
	}

	/**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
	{
        return [
            'id' => 'ID',
            'workshops_id' => 'Workshops',
            'user_id' => 'User',
			'user_ids' => 'Users',
			'is_send_mail' => 'is send mail',
		];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): ActiveQuery
	{
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Workshops]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkshops(): ActiveQuery
	{
        return $this->hasOne(Workshops::className(), ['id' => 'workshops_id']);
    }
}
