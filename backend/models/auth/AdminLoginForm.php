<?php
namespace backend\models\auth;

use Yii;
use yii\base\Model;
use backend\models\auth\Admin;

/**
 * Admin Login form
 */
class AdminLoginForm extends Model
{

	public ?string $username = null;
	public string $password;
	public bool $rememberMe = true;

	private ?Admin $_user = null;


	/**
	 * {@inheritdoc}
	 */
	public function rules(): array
	{
		return [
			[['username', 'password'], 'required'],
			['rememberMe', 'boolean'],
			['password', 'validatePassword'],
		];
	}

	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string|null $attribute the attribute currently being validated
	 * @param array|null $params the additional name-value pairs given in the rule
	 */
	public function validatePassword(?string $attribute, ?array $params): void
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'Incorrect username or password.');
			}
		}
	}

	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return bool whether the user is logged in successfully
	 */
	public function login(): bool
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
		}

		return false;
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	protected function getUser(): ?Admin
	{
		if ($this->_user === null) {
			$this->_user = Admin::findByUsername($this->username);
		}

		return $this->_user;
	}

}