<?php
namespace backend\controllers;

use common\models\AdminPanelSearch;
use common\models\CommentDeal;
use common\models\Contacts;
use common\models\FixedBonuses;
use common\models\HistoryDeal;
use common\models\KpiManager;
use common\models\KpiManagerSearch;
use common\models\ManagerDashboard;
use common\models\ManagerDashboardAdminSearch;
use common\models\ManagerDashboardArchiveSearch;
use common\models\ManagerDashboardSearch;
use common\models\ManagerDashboardTrashSearch;
use common\models\PriceOrder;
use common\models\RatingManager;
use DateTime;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity){
            $searchModel = new AdminPanelSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->redirect('/admin/site/login');
        }
    }

    public function actionDealsManager($id){
        if (Yii::$app->user->identity){
            $searchModel = new ManagerDashboardAdminSearch();
            $searchModel->id_manager = $id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('deals-manager', [
                'id' => $id,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->redirect('/admin/site/login');
        }
    }

    public function actionCreateValidate()
    {
        $model = new ManagerDashboard();
        $request = \Yii::$app->getRequest();
        if (Yii::$app->request->isAjax && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }
    }


    public function actionUpdateDeals($id)
    {
        $model = $this->findModel($id);

        $model->contacts = Contacts::find()->where(['deal_id' => $id])->asArray()->all();
        $model->price_deal = PriceOrder::find()->where(['order_id' => $id])->asArray()->all();
        $model->text = CommentDeal::find()->where(['deal_id'=> $id])->asArray()->all();

        $oldModel = $this->findModel($id);
        $oldModel->contacts = Contacts::find()->where(['deal_id' => $id])->asArray()->all();
        $oldModel->price_deal = PriceOrder::find()->where(['order_id' => $id])->asArray()->all();
        $oldModel->text = CommentDeal::find()->where(['deal_id'=> $id])->asArray()->all();

        $request = \Yii::$app->getRequest();
        if ($model->load($request->post())) {

            if($model->contacts[0]['name'] == ''){
                $model->addError('contacts', 'В сделке должен участвовать минимум один контакт');
            }
            else{
                foreach ($model->contacts as $key=>$contact){
                    if ($contact['name'] == '' && $contact['type_contact_id'] == ''){
                        unset($model->contacts[$key]);
                    }
                    elseif ($contact['name'] == '' && $contact['type_contact_id'] == '' && $contact['email'] == '' && $contact['phone'] == ''){
                        unset($model->contacts[$key]);
                    }
                }
            }
            if($model->text[0]['text'] != ''){
                foreach ($model->text as $key=>$text){
                    if ($text == ''){
                        unset($model->text[$key]);
                    }
                }
            }
            if($model->price_deal[0]['price'] != ''){
                foreach ($model->price_deal as $key=>$price){
                    if ($price['price'] == ''){
                        unset($model->price_deal[$key]);
                    }
                }
            }

            if($model->bool_fixed_or_individ == 0){
                if($model->price_deal[0]['price'] == ''){
                    $model->tax = 0;
                }
                else{
                    $priceAll = 0;
                    foreach ($model->price_deal as $item){
                        if(isset($item['price']) && $item['price'] > 0){
                            $priceAll += $item['price'];
                        }
                        else{
                            $priceAll += 0;
                        }
                    }
                    $saleManager = ManagerDashboard::find()->where(['id_manager' => $model->id_manager, 'status_order_id' => 6])->asArray()->count();
                    if ($saleManager == 0) $saleManager = 1;
                    $fixedBonuses = FixedBonuses::find()->asArray()->all();
                    $currentBonuses = 0;
                    foreach ($fixedBonuses as $key => $bonus) {
                        if ($bonus['max_count_deal'] == null) {
                            if ($saleManager > $bonus['min_count_deal']) {
                                $currentBonuses = $bonus['bonuses'];
                            }
                        } else {
                            if ($saleManager >= $bonus['min_count_deal'] && $saleManager <= $bonus['max_count_deal']) {
                                $currentBonuses = $bonus['bonuses'];
                            }
                        }
                    }
                    if($priceAll > 0){
                        $tax = ($priceAll / 100) * $currentBonuses;
                        if($model->getOldAttribute('tax') != $tax){
                            $model->tax = $tax;
                        }
                    }
                }
            }

            //Contacts
            foreach ($model->contacts as $keyI=>$contactModel){
                foreach ($oldModel->contacts as $keyJ=>$contactOldModel){
                    if(isset($contactModel['id'])){
                        if ($contactModel['id'] == $contactOldModel['id']){
                            $contactModelDB = Contacts::findOne($contactModel['id']);
                            $contactModelDB->manager_id = $contactModel['manager_id'];
                            $contactModelDB->deal_id = $contactModel['deal_id'];
                            $contactModelDB->name = $contactModel['name'];
                            $contactModelDB->phone = $contactModel['phone'];
                            $contactModelDB->email = $contactModel['email'];
                            $contactModelDB->type_contact_id = $contactModel['type_contact_id'];
                            $contactModelDB->lpr_bool = $contactModel['lpr_bool'];
                            $contactModelDB->save();
                            unset($oldModel->contacts[$keyJ]);
                            unset($model->contacts[$keyI]);
                        }
                    }
                }
            }
            foreach ($model->contacts as $key=>$contact){
                if(!isset($contact['lpr_bool'])){
                    $model->contacts[$key]['lpr_bool'] = 0;
                }elseif ($contact['lpr_bool'] == 'on'){
                    $model->contacts[$key]['lpr_bool'] = 1;
                }
            }
            foreach ($model->contacts as $key=>$contact){
                $contactModelDB = new Contacts();
                $contactModelDB->manager_id = $model->id_manager;
                $contactModelDB->deal_id = $model->id;
                $contactModelDB->name = $contact['name'];
                $contactModelDB->phone = $contact['phone'];
                $contactModelDB->email = $contact['email'];
                $contactModelDB->type_contact_id = $contact['type_contact_id'];
                $contactModelDB->lpr_bool = $contact['lpr_bool'];
                $contactModelDB->save();
            }
            foreach ($oldModel->contacts as $key=>$contact){
                $contactModelDB = Contacts::findOne($contact['id']);
                $contactModelDB->delete();
            }

            //Prices
            foreach ($model->price_deal as $keyI=>$priceOrder){
                foreach ($oldModel->price_deal as $keyJ=>$priceOrderOLD){
                    if(isset($priceOrder['id'])){
                        if($priceOrder['id'] == $priceOrderOLD['id']){
                            $priceModelDB = PriceOrder::findOne($priceOrder['id']);
                            $priceModelDB->order_id = $priceOrder['order_id'];
                            $priceModelDB->price = $priceOrder['price'];
                            $priceModelDB->comment = $priceOrder['comment'];
                            $priceModelDB->save();
                            unset($oldModel->price_deal[$keyJ]);
                            unset($model->price_deal[$keyI]);
                        }
                    }
                }
            }
            foreach ($model->price_deal as $key=>$priceOrder){
                $priceModelDB = new PriceOrder();
                $priceModelDB->order_id = $model->id;
                $priceModelDB->price = $priceOrder['price'];
                $priceModelDB->comment = $priceOrder['comment'];
                $priceModelDB->save();
            }
            foreach ($oldModel->price_deal as $key=>$priceOrder){
                $priceModelDB = PriceOrder::findOne($priceOrder['id']);
                $priceModelDB->delete();
            }

            //Comments
            foreach ($model->text as $keyI=>$text){
                foreach ($oldModel->text as $keyJ=>$textOLD){
                    if(isset($text['id'])){
                        if($text['id'] == $textOLD['id']){
                            $commentModelDB = CommentDeal::findOne($text['id']);
                            $commentModelDB->text = $text['text'];
                            $commentModelDB->deal_id = $text['deal_id'];
                            $commentModelDB->status_deal_id = $model->status_order_id;
                            $commentModelDB->date = date('Y-m-d H:i:s');
                            $commentModelDB->save();
                            unset($oldModel->text[$keyJ]);
                            unset($model->text[$keyI]);
                        }
                    }
                }
            }
            foreach ($model->text as $key=>$text){
                $commentModelDB = new CommentDeal();
                $commentModelDB->text = $text['text'];
                $commentModelDB->deal_id = $model->id;
                $commentModelDB->status_deal_id = $model->status_order_id;
                $commentModelDB->date = date('Y-m-d H:i:s');
                $commentModelDB->save();
            }
            foreach ($oldModel->text as $key=>$text){
                $commentModelDB = CommentDeal::findOne($text['id']);
                $commentModelDB->delete();
            }

            if ($model->validate()){
                if($model->getOldAttribute('status_order_id') != $model->status_order_id){
                    $historyDeal = new HistoryDeal();
                    $historyDeal->deal_id = $model->id;
                    $historyDeal->manager_id = $model->id_manager;
                    $historyDeal->old_status_id = $model->getOldAttribute('status_order_id');
                    $historyDeal->new_status_id = $model->status_order_id;
                    $historyDeal->date = date('Y-m-d H:i:s');
                    $historyDeal->save();
                }


                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update-deals', [
            'model' => $model,
        ]);
    }

    public function actionRating()
    {
        if (Yii::$app->user->identity){

            $managers = \common\models\UserPersonalInfo::find()->all();
            $arrIdManager = array();
            foreach ($managers as $key=>$manager){
                if(\Yii::$app->authManager->getAssignment('manager', $manager->user_id)){
                    $arrIdManager[] = array(
                        'id' => $manager->user_id,
                        'name' => $manager->name,
                    );
                }
            }

            $arResult = array();
            foreach ($arrIdManager as $key=>$item){


                $sale_order = ManagerDashboard::find()
                    ->joinWith('historyDeals')
                    ->where(['id_manager'=>$item['id'], 'status_order_id' => 6, 'history_deal.new_status_id' => 6])
                    ->andWhere("MONTH(DATE(`history_deal`.`date`)) = MONTH(DATE(NOW()))")
                    ->orderBy('id')
                    ->count();

                $conversation_order = ManagerDashboard::find()
                    ->where(['id_manager'=>$item['id']])->andWhere('status_order_id < 6')
                    ->asArray()
                    ->count();


                $sum_month_order = ManagerDashboard::find()
                    ->joinWith(['historyDeals','priceOrders'])
                    ->where(['`manager_dashboard`.`id_manager`'=>$item['id']])
                    ->andWhere('`manager_dashboard`.`status_order_id` = 6')
                    ->andWhere("`history_deal`.`new_status_id` = 6 AND MONTH(DATE(`history_deal`.`date`)) = MONTH(DATE(NOW()))")
                    ->asArray()
                    ->all();

                $preSum_month_order = 0;
                foreach ($sum_month_order as $deal){
                    foreach ($deal['priceOrders'] as $priceOrder){
                        $preSum_month_order += $priceOrder['price'];
                    }
                }
                $sum_month_order = $preSum_month_order;


                $sum_prevmonth_order = ManagerDashboard::find()
                    ->joinWith(['historyDeals','priceOrders'])
                    ->where(['id_manager'=>$item['id'], 'status_order_id' => 6])
                    ->andWhere(['history_deal.new_status_id' => 6])
                    ->andWhere("MONTH(DATE(`history_deal`.`date`)) = MONTH(DATE(NOW()))-1")
                    ->asArray()
                    ->all();

                $preSum_prevmonth_order = 0;
                foreach ($sum_prevmonth_order as $deal){
                    foreach ($deal['priceOrders'] as $priceOrder){
                        $preSum_month_order += $priceOrder['price'];
                    }
                }
                $sum_prevmonth_order = $preSum_prevmonth_order;

                $sum_all_order = ManagerDashboard::find()
                    ->joinWith(['priceOrders'])
                    ->where(['id_manager'=>$arrIdManager[$key]['id'], 'status_order_id' => 6])
                    ->asArray()
                    ->all();
                $preSum_all_order = 0;
                foreach ($sum_all_order as $deal){
                    foreach ($deal['priceOrders'] as $priceOrder){
                        $preSum_all_order += $priceOrder['price'];
                    }
                }
                $sum_all_order = $preSum_all_order;

                $ratingModel = new RatingManager();
                $ratingModel->manager_id = $arrIdManager[$key]['id'];
                $ratingModel->manager_name = $arrIdManager[$key]['name'];
                $ratingModel->sale_order = $sale_order;
                $ratingModel->conversation_order = $conversation_order;
                $ratingModel->sum_month_order = $sum_month_order;
                $ratingModel->sum_prevmonth_order = $sum_prevmonth_order;
                $ratingModel->sum_all_order = $sum_all_order;

                $arResult[] = $ratingModel;
            }

            return $this->render('rating', [
                'model' => $arResult
            ]);
        }
        else{
            return $this->redirect('/site/login');
        }
    }

    public function actionArchive(){
        if (Yii::$app->user->identity){
            $searchModel = new ManagerDashboardArchiveSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('archive', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->redirect('/site/login');
        }
    }

    public function actionTrash(){
        if (Yii::$app->user->identity){
            $searchModel = new ManagerDashboardTrashSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('trash', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->redirect('/site/login');
        }
    }

    public function actionKpiManager($id){
        $searchModel = new KpiManagerSearch();
        $searchModel->manager_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('kpi-manager', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewDeals($id)
    {
        if (Yii::$app->user->identity){
            $model = $this->findModel($id);

            $model->contacts = Contacts::find()->where(['deal_id' => $id])->asArray()->all();
            $model->price_deal = PriceOrder::find()->where(['order_id'=>$id])->asArray()->all();
            $model->text = CommentDeal::find()->where(['deal_id' => $id])->asArray()->all();

            return $this->render('view-deals', [
                'model' => $model,
            ]);
        }
        else{
            return $this->redirect('/admin/site/login');
        }

    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->for_trash = 1;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionReturn($id)
    {
        $model = $this->findModel($id);
        $model->for_trash = 0;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionDeleteFinally($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionMassDeleteFinally(){
        if (Yii::$app->request->post('keylist')) {
            $keys = Yii::$app->request->post('keylist');
            foreach ($keys as $key) {
                $model = ManagerDashboard::findOne($key);
                $model->delete();
            }
        }
        return $this->redirect(Url::previous());
    }

    protected function findModel($id)
    {
        if (($model = ManagerDashboard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->identity) {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->loginAdmin()) {
                return $this->goBack();
            } else {
                $model->password = '';

                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
        else{
            return $this->goHome();
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
