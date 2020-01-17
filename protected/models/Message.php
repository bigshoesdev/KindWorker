<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
* This is the model class for table "tbl_message".
*
    * @property integer $id
    * @property string $message
    * @property string $session_id
    * @property integer $state_id
    * @property integer $booking_id
    * @property integer $type_id
    * @property integer $to_id
    * @property string $send_time
    * @property integer $from_id
    * @property string $created_on
    * @property string $updated_on
    * @property integer $created_by_id

* === Related data ===
    * @property Booking $booking
    * @property User $createdBy
    * @property User $from
    * @property User $to
    */

namespace app\models;

use Yii;
use yii\components;
use app\models\Booking;
use app\models\User;


class Message extends \app\components\TActiveRecord
{
	public  function __toString()
	{
		return (string)$this->message;
	}
	const STATE_INACTIVE 	= 0;
	const STATE_ACTIVE	 	= 1;
	const STATE_DELETED 	= 2;

	public static function getStateOptions()
	{
		return [
				self::STATE_INACTIVE		=> "New",
				self::STATE_ACTIVE 			=> "Active" ,
				self::STATE_DELETED 		=> "Archieved",
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
				if ( !isset( $this->updated_on )) $this->updated_on = date( 'Y-m-d H:i:s');
				if ( !isset( $this->created_by_id )) $this->created_by_id = Yii::$app->user->id;
			}else{
						$this->updated_on = date( 'Y-m-d H:i:s');
			}
		return parent::beforeValidate();
	}


	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%message}}';
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
            [['message', 'booking_id'], 'required'],
            [['message'], 'string'],
            [['state_id', 'booking_id', 'type_id', 'to_id', 'from_id', 'created_by_id'], 'integer'],
            [['send_time', 'created_on', 'updated_on'], 'safe'],
            [['session_id'], 'string', 'max' => 255],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking_id' => 'id']],
            [['created_by_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by_id' => 'id']],
            [['from_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['from_id' => 'id']],
            [['to_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['to_id' => 'id']],
            [['session_id'], 'trim'],
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
				    'message' => Yii::t('app', 'Message'),
				    'session_id' => Yii::t('app', 'Session'),
				    'state_id' => Yii::t('app', 'State'),
				    'booking_id' => Yii::t('app', 'Booking'),
				    'type_id' => Yii::t('app', 'Type'),
				    'to_id' => Yii::t('app', 'To'),
				    'send_time' => Yii::t('app', 'Send Time'),
				    'from_id' => Yii::t('app', 'From'),
				    'created_on' => Yii::t('app', 'Created On'),
				    'updated_on' => Yii::t('app', 'Updated On'),
				    'created_by_id' => Yii::t('app', 'Created By'),
				];
	}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getBooking()
    {
    	return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCreatedBy()
    {
    	return $this->hasOne(User::className(), ['id' => 'created_by_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getFrom()
    {
    	return $this->hasOne(User::className(), ['id' => 'from_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getTo()
    {
    	return $this->hasOne(User::className(), ['id' => 'to_id']);
    }
    public static function getHasManyRelations()
    {
    	$relations = [];
		return $relations;
	}
    public static function getHasOneRelations()
    {
    	$relations = [];
		$relations['booking_id'] = ['booking','Booking','id'];
		$relations['created_by_id'] = ['createdBy','User','id'];
		$relations['from_id'] = ['from','User','id'];
		$relations['to_id'] = ['to','User','id'];
		return $relations;
	}

	public function beforeDelete() {
		return parent::beforeDelete ();
	}

    public function asJson($with_relations=false)
	{
		$json = [];
			$json['id'] 	= $this->id;
			$json['message'] 	= $this->message;
			$json['session_id'] 	= $this->session_id;
			$json['state_id'] 	= $this->state_id;
			$json['booking_id'] 	= $this->booking_id;
			$json['type_id'] 	= $this->type_id;
			$json['to_id'] 	= $this->to_id;
			$json['send_time'] 	= $this->send_time;
			$json['from_id'] 	= $this->from_id;
			$json['created_on'] 	= $this->created_on;
			$json['created_by_id'] 	= $this->created_by_id;
			if ($with_relations)
		    {
				// Booking				$list = $this->getBooking()->all();

				if ( is_array($list))
				{
					$relationData = [];
					foreach( $list as $item)
					{
						$relationData [] 	= $item->asJson();
					}
					$json['Booking'] 	= $relationData;
				}
				else
				{
					$json['Booking'] 	= $list;
				}
				// CreatedBy				$list = $this->getCreatedBy()->all();

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
				// From				$list = $this->getFrom()->all();

				if ( is_array($list))
				{
					$relationData = [];
					foreach( $list as $item)
					{
						$relationData [] 	= $item->asJson();
					}
					$json['From'] 	= $relationData;
				}
				else
				{
					$json['From'] 	= $list;
				}
				// To				$list = $this->getTo()->all();

				if ( is_array($list))
				{
					$relationData = [];
					foreach( $list as $item)
					{
						$relationData [] 	= $item->asJson();
					}
					$json['To'] 	= $relationData;
				}
				else
				{
					$json['To'] 	= $list;
				}
			}
		return $json;
	}

}
