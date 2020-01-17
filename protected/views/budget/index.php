<?php
/**
 * Created by PhpStorm.
 * User: KNI
 * Date: 10/29/2017
 * Time: 4:14 PM
 */

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Budget */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* $this->title = Yii::t('app', 'Index');*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Budgets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Index');;
?>
<div class="wrapper">
    <div class="user-index">
        <div class=" panel ">
            <div class=" panel ">
                <div
                    class="page-index">

                    <?=  \app\components\PageHeader::widget(); ?>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                </div>
            </div>
        </div>
        <div class="panel panel-margin">
            <div class="panel-body">
                <div class="content-section clearfix">
                    <header class="panel-heading head-border">   <?php echo strtoupper(Yii::$app->controller->action->id); ?> </header>
                    <?php echo $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]); ?>
                </div>
            </div>
        </div>
    </div>

</div>