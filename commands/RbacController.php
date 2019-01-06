<?php

namespace app\commands;

use yii;
use yii\console\Controller;

class RbacController extends Controller{
    public function actionInit(){
        $auth = Yii::$app->authManager;

        $accessDemoOne = $auth->createPermission('accessDemoOne');
        $accessDemoOne->description = 'Access Demo One';
        $auth->add($accessDemoOne);

        $accessDemoTwo = $auth->createPermission('accessDemoTwo');
        $accessDemoTwo->description = 'Access Demo Two';
        $auth->add($accessDemoTwo);

        $classone = $auth->createRole('classone');
        $auth->add($classone);
        $auth->addChild($classone, $accessDemoOne);

        $classtwo = $auth->createRole('classtwo');
        $auth->add($classtwo);
        $auth->addChild($classtwo, $accessDemoTwo);

        $auth->assign($classone, 1);
        $auth->assign($classtwo, 2);
    }
}