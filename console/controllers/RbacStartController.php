<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacStartController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //удаляем старые данные

        //Создадим для примера права для доступа к админке
        $dashboard = $auth->createPermission('adminPanel');
        $dashboard->description = 'Админ панель';
        $auth->add($dashboard);

        //Добавляем роли
        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $auth->add($user);

        $manager = $auth->createRole('manager');
        $manager->description = 'Менеджер';
        $auth->add($manager);

        //Добавляем потомков
        $auth->addChild($manager, $user);
        //$auth->addChild($manager, $dashboard);

        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);
        //$auth->addChild($admin, $manager);
    }

}