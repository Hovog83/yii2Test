<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string first_name
 * @property string last_name
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE = 10;

	/**
     * {@inheritdoc}
     */
    public static function tableName(): string
	{
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
	{
        return [
            TimestampBehavior::className(),
        ];
    }

	public function attributeLabels(): array
	{
		return [
			'password_hash' => 'Password',
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules(): array
	{
        return [
			[['first_name', 'last_name', 'username', 'email'], 'required'],
			['password_hash', 'required', 'on' => 'insert'],
			['password_hash', 'string'],
			[['username', 'email'], 'trim'],
			['email', 'email'],
			[[ 'email', 'username'], 'unique'],
			['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

	/**
	 * {@inheritdoc}
	 * @throws \yii\base\NotSupportedException
	 */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername(string $username): ?User
	{
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token): ?User
	{
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken(string $token): ?User
	{
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid(string $token): bool
	{
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): string
	{
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): bool
	{
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
	{
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 *
	 * @throws \yii\base\Exception
	 */
    public function setPassword(string $password): void
	{
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

	/**
	 * Generates "remember me" authentication key
	 *
	 * @throws \yii\base\Exception
	 */
    public function generateAuthKey(): void
	{
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

	/**
	 * Generates new password reset token
	 *
	 * @throws \yii\base\Exception
	 */
    public function generatePasswordResetToken(): void
	{
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

	/**
	 * Generates new token for email verification
	 *
	 * @throws \yii\base\Exception
	 */
    public function generateEmailVerificationToken(): void
	{
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
	{
        $this->password_reset_token = null;
    }

	/**
	 * @param  $insert
	 *
	 * @return bool
	 * @throws \yii\base\Exception
	 */
	public function beforeSave($insert): bool
	{
		if (parent::beforeSave($insert))
		{
			if ($this->isNewRecord || (!$this->isNewRecord && $this->password_hash))
			{
				$this->setPassword($this->password_hash);
				$this->generateAuthKey();
			}
			return true;
		}
		return false;
	}

}
