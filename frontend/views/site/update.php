<?php

use frontend\assets\InputMaskPhoneAsset;
use kartik\widgets\SwitchInput;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

InputMaskPhoneAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\ManagerDashboard */

$this->title = 'Редактирование сделки: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Manager Dashboards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="manager-dashboard-update container">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="manager-dashboard-form">


        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnChange' => false,
            'validateOnType' => false,
            'validateOnSubmit'=> false,
            'validationUrl' => Url::toRoute(['create-validate']),
        ]); ?>


        <?=$form->field($model, 'services_id')->dropDownList(
            ArrayHelper::map(\common\models\Services::find()->where(['active'=>1])->all(), 'id', 'name'))
            ->label('Услуга')?>

        <?=$form->field($model, 'bool_fixed_or_individ')->hiddenInput()->label(false); ?>


        <?
        $status = \common\models\StatusOrder::find()->where(['active'=>1])->orderBy('sort')->all();
        $status_tmp = array();

        foreach ($status as $key=>$statusOrder){
            if($model->status_order_id == $statusOrder['id']){
                if(($key-1) >= 0){
                    $status_tmp[] = $status[$key-1];
                }
                $status_tmp[] = $status[$key];

                if(($key+1) <= (count($status)-1)){
                    $status_tmp[] = $status[$key+1];
                }
            }
        }
        $status = $status_tmp;

        ?>

        <?=$form->field($model, 'status_order_id')->dropDownList(
            ArrayHelper::map($status, 'id', 'name'))
            ->label('Стадии сделки')?>

        <?=$form->field($model, 'account_name')->textInput()->label('Профиль Instagram')?>
        <br>
        <?= $form->field($model, 'contacts')->widget(MultipleInput::className(), [
            'id' => 'contact-multiple',
            'max' => 4,
            'showGeneralError' => true,
            'columns' => [
                [
                    'name' => 'id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'id',
                ],
                [
                    'name' => 'manager_id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'manager_id',
                ],
                [
                    'name' => 'deal_id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'deal_id',
                ],
                [
                    'name' => 'name',
                    'enableError' => true,
                    'type' => MultipleInputColumn::TYPE_TEXT_INPUT,
                    'title' => 'Имя',
                ],
//                [
//                    'name' => 'phone',
//                    'title' => 'Телефон',
//                    'enableError' => true,
//                    'type' => \yii\widgets\MaskedInput::className(),
//                    'options' => [
//                        'options' => [
//                            'class' => 'form-control placeholder-style',
//                            'placeholder' => ('Телефон'),
//                            'clientOptions' => [
//                                'greedy' => false,
//                                'clearIncomplete' => true
//                            ]
//                        ],
//                        'clientOptions' => [
//                            'alias' => 'phone',
//                        ]
//
//                    ]
//                ],
//                [
//                    'name' => 'email',
//                    'title' => 'Email',
//                    'enableError' => true,
//                    'type' => MaskedInput::className(),
//                    'options' => [
//                        'options' => [
//                            'class' => 'form-control placeholder-style',
//                            'placeholder' => 'Email',
//                            'clientOptions' => [
//                                'clearIncomplete' => true
//                            ]
//                        ],
//                        'clientOptions' => [
//                            'alias' => 'email',
//                        ]
//                    ],
//                ],
                [
                    'name' => 'phone',
                    'title' => 'Телефон',
                    'enableError' => true,
                    'type' => MultipleInputColumn::TYPE_TEXT_INPUT,
                    'options' => [
                        'class' => 'form-control placeholder-style',
                        'placeholder' => 'Телефон',
                        'clientOptions' => [
                            'clearIncomplete' => true
                        ]
                    ],
                ],
                [
                    'name' => 'email',
                    'title' => 'Email',
                    'enableError' => true,
                    'type' => MultipleInputColumn::TYPE_TEXT_INPUT,
                    'options' => [
                        'class' => 'form-control placeholder-style',
                        'placeholder' => 'Email',
                        'clientOptions' => [
                            'clearIncomplete' => true
                        ]
                    ],
                ],
                [
                    'name' => 'type_contact_id',
                    'title' => 'Тип контакта',
                    'enableError' => true,
                    'type' => MultipleInputColumn::TYPE_DROPDOWN,
                    'items' => ArrayHelper::map(\common\models\TypeContact::find()->all(), 'id', 'name'),
                    'options' => [
                        'prompt' => '',
                    ],
                ],
                [
                    'title' => 'Основной',
                    'name' => 'lpr_bool',
                    'type' => MultipleInputColumn::TYPE_CHECKBOX,

                ],
            ]
        ])->label('Контакты сделки');?>
        <br>

        <?php
        $script2 = <<< JS
        
            $('#contact-multiple').on('afterInit', function(){
                //console.log('calls on after initialization event');
                
                $("input[type=checkbox][name^=ManagerDashboard]").change(function() {
                    $("input[type=checkbox][name^=ManagerDashboard]").prop('checked', false);
                    $(this).prop('checked', true);
                });
                
            }).on('afterAddRow', function(e, row) {
                //console.log('calls on after add row event', $(row));
                
                $("input[type=checkbox][name^=ManagerDashboard]").change(function() {
                    $("input[type=checkbox][name^=ManagerDashboard]").prop('checked', false);
                    $(this).prop('checked', true);
                });
            }).on('afterDeleteRow', function(e, item){       
                //console.log('calls on after remove row event');
                
                $("input[type=checkbox][name^=ManagerDashboard]").change(function() {
                    $("input[type=checkbox][name^=ManagerDashboard]").prop('checked', false);
                    $(this).prop('checked', true);
                });
                
            });
           
JS;
        $this->registerJs($script2, \yii\web\View::POS_END);
        ?>

        <?= $form->field($model, 'price_deal')->widget(MultipleInput::className(), [
            'max' => 4,
            'showGeneralError' => true,
            'columns' => [
                [
                    'name' => 'id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'id',
                ],
                [
                    'name' => 'order_id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'order_id',
                ],
                [
                    'name' => 'price',
                    'title' => 'Сумма',
                    'enableError' => true,
                    'options' => [
                        'type' => 'number',
                    ]
                ],
                [
                    'name' => 'comment',
                    'enableError' => true,
                    'title' => 'Комментарий к сумме',
                    'type' => MultipleInputColumn::TYPE_TEXT_INPUT
                ]
            ]
        ])->label('Сумма сделки');?>


        <?= $form->field($model, 'text')->widget(MultipleInput::className(), [
            'max' => 100,
            'showGeneralError' => true,
            'columns' => [
                [
                    'name' => 'id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'id',
                ],
                [
                    'name' => 'deal_id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'deal_id',
                ],
                [
                    'name' => 'status_deal_id',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'status_deal_id',
                ],
                [
                    'name' => 'date',
                    'enableError' => false,
                    'type' => MultipleInputColumn::TYPE_HIDDEN_INPUT,
                    'title' => 'date',
                ],
                [
                    'name' => 'text',
                    'title' => 'Комментарий',
                    'enableError' => true,
                    'type' => MultipleInputColumn::TYPE_TEXT_INPUT
                ],
            ]
        ])->label('Комментарии к сделке');?>



        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
