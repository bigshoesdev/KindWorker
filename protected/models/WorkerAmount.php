<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
 
/**
* This is the model class for table "tbl_worker_amount".
*
    * @property integer $id
    * @property string $amount
    * @property integer $state_id
    * @property integer $type_id
    * @property string $created_on
    * @property integer $created_by_id
    
* === Related data ===
    * @property User $createdBy
    */

namespace app\models;

use Yii;
use app\models\User;


class WorkerAmount extends \app\components\TActiveRecord
{
	public  function __toString()
	{
		return (string)$this->amount;
	}
	const STATE_INACTIVE 	= 0;
	const STATE_ACTIVE	 	= 1;
	const STATE_DELETED 	= 2;

	public static function getStateOptions()
	{
		return [
				self::STATE_INACTIVE		=> "New",
				self::STATE_ACTIVE 			=> "Active" ,
				self::STATE_DELETED 		=> "Archived",
		];
	}
	public function getState()
	{
		$list = self::getStateOptions();
		return isset($list [$this->state_id])?$list [$this->state_id]:'Not Defined';

	}
	public function getStateBadge()
	{
		$list = [
				self::STATE_INACTIVE 		=> "primary",
				self::STATE_ACTIVE 			=> "success" ,
				self::STATE_DELETED 		=> "danger",
		];
		return isset($list[$this->state_id])?\yii\helpers\Html::tag('span', $this->getState(), ['class' => 'label label-' . $list[$this->state_id]]):'Not Defined';
	}


	public static function getTypeOptions()
	{
		return ["TYPE1","TYPE2","TYPE3"];

	}
 	public function getType()
	{
		$list = self::getTypeOptions();
		return isset($list [$this->type_id])?$list [$this->type_id]:'Not Defined';

	}

	public function beforeValidate()
	{
		if($this->isNewRecord)
		{
				if ( !isset( $this->created_on )) $this->created_on = date( 'Y-m-d H:i:s');
				if ( !isset( $this->created_by_id )) $this->created_by_id = Yii::$app->user->id;
			}else{
					}
		return parent::beforeValidate();
	}


	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%worker_amount}}';
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
            [['amount', 'created_on'], 'required'],
            [['state_id', 'type_id', 'created_by_id'], 'integer'],
            [['created_on'], 'safe'],
            [['amount'], 'string', 'max' => 255],
            [['created_by_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by_id' => 'id']],
            [['amount'], 'trim'],
            [['state_id'], 'in', 'range' => array_keys(self::getStateOptions())],
            [['type_id'], 'in', 'range' => array_keys (self::getTypeOptions())]
        ];
	}

	/**
	* @inheritdoc
	*/


	public function attributeLabels()
	{
		return [
				    'id' => Yii::t('app', 'ID'),
				    'amount' => Yii::t('app', 'Amount'),
				    'state_id' => Yii::t('app', 'State'),
				    'type_id' => Yii::t('app', 'Type'),
				    'created_on' => Yii::t('app', 'Created On'),
				    'created_by_id' => Yii::t('app', 'Created By'),
				];
	}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreatedBy()
    {
    	return $this->hasOne(User::className(), ['id' => 'created_by_id']);
    }
    public static function getHasManyRelations()
    {
    	$relations = [];
		return $relations;
	}
    public static function getHasOneRelations()
    {
    	$relations = [];
		$relations['created_by_id'] = ['createdBy','User','id'];
		return $relations;
	}

	public function beforeDelete() {
		return parent::beforeDelete ();
	}

    public function asJson($with_relations=false)
	{
		$json = [];
			$json['id'] 	= $this->id; 		
			$json['amount'] 	= $this->amount; 		
			$json['state_id'] 	= $this->state_id; 		
			$json['type_id'] 	= $this->type_id; 		
			$json['created_on'] 	= $this->created_on; 		
			$json['created_by_id'] 	= $this->created_by_id; 		
			if ($with_relations)
		    {
				// CreatedBy	
				$list = $this->getCreatedBy()->all();
		
				if ( is_array($list))
				{
					$relationData = [];
					foreach( $list as $item) 
					{
						$relationData [] 	= $item->asJson();
					}
					$json['CreatedBy'] 	= $relationData;
				}
				else
				{
					$json['CreatedBy'] 	= $list;
				}	
			}
		return $json;
	}

}
