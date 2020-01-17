<?php

use app\components\TGridView;
use yii\helpers\Html;
use yii\helpers\Url;

use app\models\User;

use yii\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\User $searchModel
 */

?>
<div class='text-right' <?php if(User::isAdmin()) echo ""; else echo "hidden";?>>
    <?=  \app\components\TToolButtons::widget(); ?>
</div>
<?php if (User::isAdmin()) echo Html::a('','#',['class'=>'multiple-delete glyphicon glyphicon-trash','id'=>"bulk_delete_user-grid"])?>

<?php Pjax::begin(['id'=>'user-pjax-grid']); ?>
    <?php echo TGridView::widget([
    	'id' => 'user-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions'=>['class'=>'table table-bordered'],
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn','header'=>'<a>S.No.<a/>'],
           [ 
								'name' => 'check',
								'class' => 'yii\grid\CheckboxColumn',
								'visible' => User::isAdmin () 
						],

        		'id',
        		'first_name',
        		'last_name',
        		'email:email',
        		
        		/* 'password',*/
        		'contact_no',
        		// 'address','city',
        		/* 'country', */
        		/* 'zipcode', */
        		/* 'gender', */
        		/* 'currency_type', */
        		/* 'timezone:datetime', */
        		/* 'date_of_birth:date', */
        		/* 'about_me:html', */
        		/*
        		 * ['attribute' => 'profile_file','filter'=> User::getProfileImage(),
        		 * 'value' => function ($data) { return $data = User::getProfileImage($data->profile_file); },],
        		 */
        		/* 'lat', */
        		/* 'long', */
        		/* 'tos', */
        		/* 'role_id', */
        		[
        				'attribute' => 'state_id',
        				'filter' => $searchModel->getStateOptions (),
        				'format' => 'html',
        				'value' => function ($data) {
        				return $data->getStateBadge ();
        		}
        		],
        		 ['attribute' => 'role_id','filter'=>$searchModel->getRoleOptions(),
        		 		'value' => function ($data) { return $data->getRoleOptions($data->role_id);  },] ,
        		/* 'last_visit_time:datetime',*/
        		/* 'last_action_time:datetime',*/
        		/* 'last_password_change:datetime',*/
        		/* 'activation_key',*/
        		/* 'login_error_count',*/
        		/* 'create_user_id',*/
        		//'created_on:datetime',
        		[
        				'attribute' => 'created_on',
        				'filter' => \yii\jui\DatePicker::widget ( [
        						'model' => $searchModel,
        						'attribute' => 'created_on',
        						'dateFormat' => 'yyyy-MM-dd',
        						'options' => [
        								'class' => 'form-control '
        						],
        						'clientOptions' => [
        								'changeMonth' => true,
        								'changeYear' => true
        						]
        				] ),
        				'value' => function ($data) {
        				return date ( 'Y-m-d', strtotime ( $data->created_on) );
        		},
        		
        		],
        		
        		[
        				'class' => 'app\components\TActionColumn',
        				'header' => '<a>Actions</a>',
        				'template' =>  User::isAdmin ()?'{view} {delete}':'{view}'
                        //'template' =>  '{view} {delete}'
        		]
        ],
    ]); ?>
<?php Pjax::end(); ?>
<script> 
$('#bulk_delete_user-grid').click(function(e) {
	e.preventDefault();
	 var keys = $('#user-grid-view').yiiGridView('getSelectedRows');

	 if ( keys != '' ) {
		var ok = confirm("Do you really want to delete these items?");

		if( ok ) {
			$.ajax({
				url  : '<?php echo Url::toRoute(['user/mass','action'=>'delete','model'=>get_class($searchModel)])?>', 
				type : "POST",
				data : {
					ids : keys,
				},
				success : function( response ) {
					if ( response.status == "OK" ) {
						 $.pjax.reload({container: '#user-pjax-grid'});
					}
				}
		     });
		}
	 } else {
		alert('Please select items to delete');
	 }
});

</script>

