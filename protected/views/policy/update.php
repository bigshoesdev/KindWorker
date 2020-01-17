<?php
/**
 * Created by PhpStorm.
 * User: KNI
 * Date: 10/29/2017
 * Time: 4:15 PM
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Policy */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Policy'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="wrapper">
    <div class=" panel ">
        <div
            class="page-update">
            <?=  \app\components\PageHeader::widget(['model' => $model]); ?>
        </div>
    </div>


    <div class="content-section clearfix panel">
        <?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>

