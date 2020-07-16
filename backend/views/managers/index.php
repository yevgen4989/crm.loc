<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Менеджеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать менеджера', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?
    $managers = \common\models\User::find()->all();
    foreach ($managers as $key=>$item){
        if(!\Yii::$app->authManager->getAssignment('manager', $item['id'])){
            unset($managers[$key]);
        }
    }
    foreach ($managers as $key=>$item){
        $managers[$key]['username'] = $item['username'].' ('.$item['id'].')';
    }
    
    $nameManagers = \common\models\UserPersonalInfo::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = user_personal_info.user_id')->where(['auth_assignment.item_name'=>'manager'])->asArray()->all();
    
    $columns = [
        [
            'class' => 'kartik\grid\ActionColumn',
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'hAlign' => GridView::ALIGN_CENTER,
            'noWrap' => false,
            'width' => '7%',
            'viewOptions' => ['title' => 'Детальная страница', 'class' => 'btn btn-success btn-xs'],
            'updateOptions' => ['title' => 'Редактирование', 'class' => 'btn btn-info btn-xs'],
            'deleteOptions' => ['title' => 'Удаление', 'class' => 'btn btn-danger btn-xs'],
            'dropdown' => false,
            'template' => '{kpi} {deals_manager} {view} {update} {delete}',
            'buttons' => [
                'deals_manager' => function ($url, $model, $key) {
                    return Html::a (
                        '<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> ',
                        ['site/deals-manager', 'id' => $model->id],
                        ['target'=>'_blank', 'data-pjax'=>"0", 'class' => 'btn btn-success btn-xs', 'title' => 'Сделки менеджера '.$model->id,]
                    );
                },
                'kpi' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', '/admin/site/kpi-manager/?id='.$model->id,
                        [
                            'title' => 'KPI Менеджера '.$model->id,
                            'data-pjax' => '0',
                            'class' => 'btn btn-success btn-xs',
                            'target' => '_blank'
                        ]
                    );
                },
            ]
        ],
        [
            'attribute'=>'username',
            'label' => 'Логин',
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map($managers, 'id', 'username'),
            'value' => function($model, $key, $index, $widget){
                return $model->username.' ('.$model->id.')';
            },
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'multiple' => false],
            'format' => 'raw'
        ],
        'email:email',
        [
            'label' => 'Активность',
            'attribute' => 'status',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map([
                    ['status'=>0, 'name'=>'Удалён'],
                    ['status'=>9, 'name'=>'Выкл.'],
                    ['status'=>10, 'name'=>'Вкл.'],
            ], 'status', 'name'),
            'value' => function($model)
            {
                if($model->status == 9){
                    return "Выкл.";
                }
                elseif ($model->status == 10){
                    return "Вкл.";
                }
                elseif ($model->status == 0){
                    return "Удалён";
                }
            },
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'multiple' => false],
            'format' => 'raw'
        ],
        [
            'attribute' => 'created_at',
            'label' => 'Создан',
            'pageSummary' => false,
            'width' => '11%',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'format'=>['DateTime','dd.MM.Y HH:mm:ss'],
            'filterWidgetOptions' => ([
                'includeMonthsFilter'=>true,
                'attribute' => 'date',
                'presetDropdown' => true,
                'convertFormat' => false,
                'pickerIcon' => '',
                'pluginOptions' => [
                    'separator' => ' - ',
                    'format' => 'DD.MM.YYYY',
                    'locale' => [
                        'format' => 'DD.MM.YYYY'
                    ]
                ],
            ]),
        ],
        [
            'attribute' => 'updated_at',
            'label' => 'Обновлён',
            'pageSummary' => false,
            'width' => '11%',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'format'=>['DateTime','dd.MM.Y HH:mm:ss'],
            'filterWidgetOptions' => ([
                'includeMonthsFilter'=>true,
                'attribute' => 'date',
                'presetDropdown' => true,
                'convertFormat' => false,
                'pickerIcon' => '',
                'pluginOptions' => [
                    'separator' => ' - ',
                    'format' => 'DD.MM.YYYY',
                    'locale' => [
                        'format' => 'DD.MM.YYYY'
                    ]
                ],
            ]),
        ],
        [
            'attribute' => 'name',
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map($nameManagers, 'name', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'multiple' => false],
            'format' => 'raw',
            'value' => function(\common\models\User $user){
                return $user->userPersonalInfo->name;
            }
        ],
        [
            'attribute' => 'kpi_day_deals',
            'label' => 'KPI Сделок на день',
            'filter' => false,
            'value' => function(\common\models\User $user){
                $count = count($user->kpiManagers) - 1;
                if($count >= 0){
                   return $user->kpiManagers[$count]->kpi_deals_day;
                }

            }
        ],
        [
            'attribute' => 'kpi_day_contacts',
            'label' => 'KPI Звонков на день',
            'filter' => false,
            'value' => function(\common\models\User $user){
                $count = count($user->kpiManagers) - 1;
                if($count >= 0){
                   return $user->kpiManagers[$count]->kpi_contacts_day;
                }
            }
        ],
        [
            'attribute' => 'kpi_kp_day',
            'label' => 'KPI КП на день',
            'filter' => false,
            'value' => function(\common\models\User $user){
                $count = count($user->kpiManagers) - 1;
                if($count >= 0){
                    return $user->kpiManagers[$count]->kpi_kp_day;
                }
            }
        ],
        [
            'attribute' => 'kpi_sale_day',
            'label' => 'KPI Продаж на день',
            'filter' => false,
            'value' => function(\common\models\User $user){
                $count = count($user->kpiManagers) - 1;
                if($count >= 0){
                    return $user->kpiManagers[$count]->kpi_sale_day;
                }

            }
        ],
    ];

    Pjax::begin(['id' => 'user-admin']);
    $dynaGrid = DynaGrid::begin([
        'theme' => 'panel-primary',
        'columns' => $columns,
        'storage' => DynaGrid::TYPE_DB,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => false,
            'pjax' => true,
            'condensed' => true,
            'bordered' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                'heading' => '',
                'before' => '',
                'after' => '',
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 1, 'class' => 'btn btn-primary', 'title' => 'Reset Grid'])
                ],
                ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
                //'{export}',
            ],
        ],
        'options' => ['id' => 'user-table-admin'],

    ]);
    if (substr($dynaGrid->theme, 0, 6) == 'simple') {
        $dynaGrid->gridOptions['panel'] = false;
    }
    DynaGrid::end();
    Pjax::end();
    ?>


</div>
