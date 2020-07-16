<?php

namespace backend\controllers;

use DateTime;
use Yii;
use common\models\KpiManager;
use common\models\KpiManagerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KpiController implements the CRUD actions for KpiManager model.
 */
class KpiController extends Controller
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
     * Lists all KpiManager models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KpiManagerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KpiManager model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KpiManager model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KpiManager();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $date = DateTime::createFromFormat('m.Y', $model->date);
            $date = $date->format('Y-m').'-'.date('d');
            $model->date = $date;

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreateKpi($id)
    {
        $model = new KpiManager();
        $model->manager_id = $id;

        $date = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
        $date = $date->format('m.Y');
        $model->date = $date;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $date = DateTime::createFromFormat('m.Y', $model->date);
            $date = $date->format('Y-m').'-'.date('d');
            $model->date = $date;

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KpiManager model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $date = DateTime::createFromFormat('Y-m-d', $model->date);
        $date = $date->format('m.Y');
        $model->date = $date;


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $date = DateTime::createFromFormat('m.Y', $model->date);
            $date = $date->format('Y-m').'-'.date('d');
            $model->date = $date;


            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing KpiManager model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KpiManager model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KpiManager the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KpiManager::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
