<?php
/**
 * Created by PhpStorm.
 * User: KNI
 * Date: 10/29/2017
 * Time: 4:14 PM
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Policy */

/* $this->title = Yii::t('app', 'Add');*/
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Policy'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Add');
?>

<div class="wrapper">
    <div class="panel">

        <div
            class="page-create">
            <?=  \app\components\PageHeader::widget(); ?>
        </div>

    </div>

    <div class="content-section clearfix panel">

        <?= $this->render ( '_form', [ 'model' => $model ] )?></div>
</div>
