<?php

namespace backend\controllers;

use backend\models\SignupFormManager;
use backend\models\UpdateManager;
use common\models\AdminPanelSearch;
use common\models\ManagerDashboardAdminSearch;
use common\models\ManagerDashboardSearch;
use common\models\UserPersonalInfo;
use Yii;
use common\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class ManagersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->identity){
            $searchModel_deal = new ManagerDashboardAdminSearch();
            $searchModel_deal->id_manager = $id;
            $dataProvider_deal = $searchModel_deal->search(Yii::$app->request->queryParams);



            return $this->render('view', [
                'model' => $this->findModel($id),
                'dataProvider_deal' => $dataProvider_deal,
                'searchModel_deal' => $searchModel_deal
            ]);
        }
        else{
            return $this->redirect('/admin/site/login');
        }
    }

    public function actionCreate(){
        $model = new SignupFormManager();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->redirect(['managers/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new UpdateManager();
        $user = $this->findModel($id);
        $model->username = $user->username;
        $model->email = $user->email;
        $model->status = $user->status;
        $model->id = $user->id;

        $personalInfo = UserPersonalInfo::find()->where(['user_id' => $id])->one();
        $model->name = $personalInfo->name;

        if ($model->load(Yii::$app->request->post()) && $model->update($id)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $auth = Yii::$app->authManager;
        $auth->revokeAll($id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
