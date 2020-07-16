<?php
namespace frontend\controllers;

use common\models\CommentDeal;
use common\models\Contacts;
use common\models\DealModel;
use common\models\FixedBonuses;
use common\models\HistoryDeal;
use common\models\ManagerDashboardArchiveSearch;
use common\models\ManagerDashboardTrashSearch;
use common\models\PriceOrder;
use common\models\RatingManager;
use common\models\StatusOrder;
use common\models\UserPersonalInfo;
use Exception;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use kartik\validators\EmailValidator;
use phpDocumentor\Reflection\Types\Null_;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;

use common\models\ManagerDashboard;
use common\models\ManagerDashboardSearch;
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
                'only' => ['logout', 'signup', 'about'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->loginManager()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     * Lists all ManagerDashboard models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity){
            $searchModel = new ManagerDashboardSearch();
            $searchModel->id_manager = \Yii::$app->user->getId();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->redirect('/site/login');
        }
    }

    public function actionArchive(){
        if (Yii::$app->user->identity){
            $searchModel = new ManagerDashboardArchiveSearch();
            $searchModel->id_manager = \Yii::$app->user->getId();
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
            $searchModel->id_manager = \Yii::$app->user->getId();
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


    /**
     * Displays a single ManagerDashboard model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $model->contacts = Contacts::find()->where(['deal_id' => $id])->asArray()->all();
        $model->price_deal = PriceOrder::find()->where(['order_id'=>$id])->asArray()->all();
        $model->text = CommentDeal::find()->where(['deal_id' => $id])->asArray()->all();


        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ManagerDashboard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateValidate()
    {
        $model = new ManagerDashboard();
        $request = \Yii::$app->getRequest();
        if (Yii::$app->request->isAjax && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                
            return ActiveForm::validate($model);
        }
    }

    public function actionCreate()
    {
        $model = new ManagerDashboard();
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
            
            $model->id_manager = \Yii::$app->user->id;
            $model->date = date('Y-m-d H:i:s');
            $model->bool_fixed_or_individ = 0;


            
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
                $saleManager = ManagerDashboard::find()->where(['id_manager' => \Yii::$app->user->id, 'status_order_id' => 6])->asArray()->count();
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
                    $model->tax = ($priceAll / 100) * $currentBonuses;
                }
            }


            
            if ($model->validate()){
                $model->save();
                
                $historyDeal = new HistoryDeal();
                $historyDeal->deal_id = $model->id;
                $historyDeal->manager_id = $model->id_manager;
                $historyDeal->new_status_id = $model->status_order_id;
                $historyDeal->date = $model->date;
                $historyDeal->save();


                if(!empty($model->price_deal)){
                    foreach ($model->price_deal as $item) {
                        $price = new PriceOrder();
                        $price->order_id = $model->id;
                        $price->price = $item['price'];
                        $price->comment = $item['comment'];

                        $price->save();

                    }
                }

                foreach ($model->contacts as $item) {
                    $contact = new Contacts();
                    $contact->manager_id = $model->id_manager;
                    $contact->deal_id = $model->id;
                    $contact->name = $item['name'];
                    $contact->type_contact_id = $item['type_contact_id'];
                    $contact->lpr_bool = $item['lpr_bool'];
                    $contact->email = $item['email'];
                    $contact->phone = $item['phone'];
                    $contact->save();
                }
                
                if ($model->text[0]['text'] != null) {
                    $comment = new CommentDeal();
                    $comment->text = $model->text[0]['text'];
                    $comment->deal_id = $model->id;
                    $comment->date = date('Y-m-d H:i:s');
                    $comment->status_deal_id = $model->status_order_id;
                    $comment->save();
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing ManagerDashboard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ManagerDashboard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->for_trash = 1;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionMassDelete(){
        if (Yii::$app->request->post('keylist')) {
            $keys = Yii::$app->request->post('keylist');
            foreach ($keys as $key) {
                $model = ManagerDashboard::findOne($key);
                $model->for_trash = 1;
                $model->save();
            }
        }
        return $this->redirect(Url::previous());
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


    /**
     * Finds the ManagerDashboard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ManagerDashboard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ManagerDashboard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
