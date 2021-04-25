<?php

namespace backend\controllers;

use common\models\events\AppendUserEvent;
use common\models\User;
use common\models\Workshops;
use Yii;
use common\models\UserWorkshops;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserWorkshopsController implements the CRUD actions for UserWorkshops model.
 */
class UserWorkshopsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
	{
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Displays a single UserWorkshops model.
     *
     * @param integer $id
     *
     * @return string
	 * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
	{
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	/**
	 * Creates a new UserWorkshops model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return string|\yii\web\Response
	 * @throws \yii\db\Exception
	 */
    public function actionCreate()
    {
        $model = new UserWorkshops();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$request = Yii::$app->request->post('UserWorkshops');

			$rows = [];
			foreach ($request['user_ids'] as $value) {
				$rows[] = [
					'workshops_id' => $request["workshops_id"],
					'user_id' => $value,
				];
			}
			Yii::$app->db->createCommand()->batchInsert(UserWorkshops::tableName(), ['workshops_id', 'user_id'], $rows)->execute();

			$event = new AppendUserEvent();
			$event->userList = $request;
			$model->trigger(UserWorkshops::APPEND_USER, $event);

			return $this->redirect(['workshops/view', 'id' => $request["workshops_id"]]);
		}

        return $this->render('create', [
            'model' => $model,
        ]);
    }

	/**
	 * Deletes an existing UserWorkshops model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return \yii\web\Response
	 * @throws \yii\web\NotFoundHttpException
	 */
    public function actionDelete(int $id): Response
	{
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserWorkshops model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return UserWorkshops the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): UserWorkshops
	{
        if (($model = UserWorkshops::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	/**
	 * @param string|null $q
	 * @param int|null $id
	 *
	 * @return array
	 * @throws \yii\db\Exception
	 */
	public function actionGetWorkshops(?string $q = null, int $id = null): array
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($q)) {
			$query = new Query;
			$query->select('id, name AS text')
				->from('workshops')
				->where(['like', 'name', $q])
				->limit(20);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => Workshops::findOne($id)->name];
		}
		return $out;
	}

	/**
	 * @param string|null $q
	 * @param int|null $id
	 *
	 * @return array
	 * @throws \yii\db\Exception
	 */
	public function actionGetUser(?string $q = null, $workshops_id = null,int $id = null): array
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$out = ['results' => ['id' => '', 'text' => '']];
		if (!is_null($q)) {
			$query = new Query;
			$query->select('user.id, email AS text')
				->from('user')
				->leftJoin('user_workshops', '`user_workshops`.`user_id` = `user`.`id`')
				->where(['like', 'email', $q])
				->andWhere(['user_workshops.user_id' => null])
				->limit(5);
			$command = $query->createCommand();
			$data = $command->queryAll();
			$out['results'] = array_values($data);
		}
		elseif ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => User::findOne($id)->email];
		}
		return $out;
	}
}
