<?php

use yii\helpers\Html;
use app\components\TGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\User $searchModel
 */

?>
<?php Pjax::begin(["enablePushState"=>false,"enableReplaceState"=>false]); ?>
    <?php echo TGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'tableOptions'=>['class'=>'table table-bordered'],
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],

            'id',
            'full_name',
            'email:email',
            /* 'password',*/
            /* 'date_of_birth:date',*/
            /* 'gender',*/
            /* 'about_me',*/
            /* 'contact_no',*/
            /* 'address',*/
            /* 'latitude',*/
            /* 'longitude',*/
            /* 'city',*/
            /* 'country',*/
            /* 'zipcode',*/
            /* 'language',*/
            /* ['attribute' => 'profile_file','filter'=>$searchModel->getFileOptions(),
			'value' => function ($data) { return $data->getFileOptions($data->profile_file);  },],*/
            /* 'tos',*/
            'role_id',
            [
			'attribute' => 'state_id','format'=>'raw','filter'=>isset($searchModel)?$searchModel->getStateOptions():null,
			'value' => function ($data) { return $data->getStateBadge();  },],
            /* ['attribute' => 'type_id','filter'=>isset($searchModel)?$searchModel->getTypeOptions():null,
			'value' => function ($data) { return $data->getType();  },],*/
            /* 'last_visit_time:datetime',*/
            /* 'last_action_time:datetime',*/
            /* 'last_password_change:datetime',*/
            /* 'login_error_count',*/
            /* 'activation_key',*/
            /* 'timezone',*/
            'created_on:datetime',
            /* 'updated_on:datetime',*/
            /* [
			'attribute' => 'created_by_id', 
			'format'=>'raw',
			'value' => function ($data) { return $data->getRelationValue('created_by_id');  },],*/

            ['class' => 'app\components\TActionColumn','header'=>'<a>Actions</a>'],
        ],
    ]); ?>
<?php Pjax::end(); ?>

