    <?php

    use kartik\date\DatePicker;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\KpiManager */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kpi-manager-form">

    <?
    $managers = \common\models\User::find()
        ->joinWith(['userPersonalInfo'])
        ->asArray()
        ->all();
    
    foreach ($managers as $key=>$item){
        if(!\Yii::$app->authManager->getAssignment('manager', $item['id'])){
            unset($managers[$key]);
        }
    }
    
    foreach ($managers as $key=>$item){
        $managers[$key]['username'] = $item['userPersonalInfo']['name'] .' --- '. $item['username'].' ('.$item['id'].')';
    }

    ?>


    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'manager_id')->dropDownList(ArrayHelper::map($managers, 'id', 'username'), ['prompt' => 'Выберите менеджера...']) ?>

    <?= $form->field($model, 'kpi_deals_day')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'kpi_contacts_day')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'kpi_kp_day')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'kpi_sale_day')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => ''],
        'convertFormat' => false,
        'pluginOptions' => [
            'minViewMode' => 1,
            'todayHighlight' => true,
            'separator' => ' - ',
            'format' => 'mm.yyyy',
            'locale' => [
                'format' => 'mm.yyyy'
            ]
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
