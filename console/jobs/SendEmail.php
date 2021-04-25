<?php


namespace console\jobs;


use common\models\User;
use common\models\UserWorkshops;
use yii\base\BaseObject;
use yii\queue\Queue;

class SendEmail extends BaseObject implements \yii\queue\JobInterface
{

	public $mailList;

	public function execute($queue)
	{

		$user =  User::find()->select('email, first_name, last_name')
			->where(['in', 'id', $this->mailList["user_ids"]])
			->all();

		foreach ($user as $value){
			\Yii::$app->mailer->compose('subscribed',  [
				'workshops_id' => $this->mailList['workshops_id'],
				'first_name' => $value->first_name,
				'last_name' => $value->last_name,
			])
				->setFrom('from@domain.com')
				->setTo($value->email)
				->setSubject('Message subject')->send();
		}
		UserWorkshops::updateAll(['is_send_mail' => 1], "workshops_id = {$this->mailList['workshops_id']}");

		echo  "\nemails is sent \n";
	}

}