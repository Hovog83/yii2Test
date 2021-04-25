<?php
namespace backend\components;

use common\models\events\AppendUserEvent;
use console\jobs\SendEmail;
use Yii;
use yii\base\Component;

class EmailService extends Component
{

	public function sendSubscribed(AppendUserEvent $event): void
	{
		$mails = ["mailList" => $event->userList];
		Yii::$app->queue->push(new SendEmail($mails));
	}

}