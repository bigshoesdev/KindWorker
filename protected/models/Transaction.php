<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */

/**
 * This is the model class for table "tbl_transaction".
 *
 * @property integer $id
 * @property string $charge_id
 * @property string $transaction_num
 * @property integer $payment_mode
 * @property string $currency
 * @property integer $payer_id
 * @property integer $reciever_id
 * @property string $amount
 * @property integer $model_id
 * @property string $model_type
 * @property integer $state_id
 * @property integer $type_id
 * @property integer $role_id
 * @property string $created_on
 * @property integer $created_by_id === Related data ===
 * @property User $createdBy
 * @property User $payer
 * @property User $reciever
 */
namespace app\models;

use Yii;
use yii\components;
use app\models\User;

class Transaction extends \app\components\TActiveRecord {
	public function __toString() {
		return ( string ) $this->transaction_num;
	}
	const STATE_NORMAL = 0;
	const STATE_CHARGE = 1;
	const STATE_SUCCESS = 2;
	public static function getStateOptions() {
		return [ 
				self::STATE_NORMAL => "Normal",
				self::STATE_CHARGE => "Charge",
				self::STATE_SUCCESS => "Success"
		];
	}
	public function getState() {
		$list = self::getStateOptions ();
		return isset ( $list [$this->state_id] ) ? $list [$this->state_id] : 'Not Defined';
	}

	public static function getTypeOptions() {
		return [ 
				"TYPE1",
				"TYPE2",
				"TYPE3" 
		];
	}
	public function getType() {
		$list = self::getTypeOptions ();
		return isset ( $list [$this->type_id] ) ? $list [$this->type_id] : 'Not Defined';
	}
	public function beforeValidate() {
		if ($this->isNewRecord) {
			if (! isset ( $this->created_on ))
				$this->created_on = date ( 'Y-m-d H:i:s' );
			if (! isset ( $this->created_by_id ))
				$this->created_by_id = Yii::$app->user->id;
		} else {
		}
		return parent::beforeValidate ();
	}
	
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%transaction}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'charge_id',
						        'transaction_num',
								'currency',
								'amount',
								'created_on',
								
						],
						'required' 
				],
				[ 
						[ 
								'payment_mode',
							
								'reciever_id',
								'model_id',
								'state_id',
								'type_id',
								'role_id',
								'created_by_id' 
						],
						'integer' 
				],
				[ 
						[ 
								'created_on',
								'model_type',
						],
						'safe' 
				],
				[ 
						[
                                'charge_id',
                                'transaction_num',
								'currency' 
						],
						'string',
						'max' => 255 
				],
				
				/* [ 
						[ 
								'model_type' 
						],
						'string',
						'max' => 1255 
				], */
				[ 
						[ 
								'created_by_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => User::className (),
						'targetAttribute' => [ 
								'created_by_id' => 'id' 
						] 
				],
				
				[ 
						[ 
								'reciever_id' 
						],
						'exist',
						'skipOnError' => true,
						'targetClass' => User::className (),
						'targetAttribute' => [ 
								'reciever_id' => 'id' 
						] 
				],
				[ 
						[ 
								'transaction_num',
								'currency',
								'amount',
								//'model_type' 
						],
						'trim' 
				],
				[ 
						[ 
								'state_id' 
						],
						'in',
						'range' => array_keys ( self::getStateOptions () ) 
				],
				[ 
						[ 
								'type_id' 
						],
						'in',
						'range' => array_keys ( self::getTypeOptions () ) 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'app', 'ID' ),
				'charge_id' => Yii::t ( 'app', 'Charge ID' ),
				'transaction_num' => Yii::t ( 'app', 'Transaction Num' ),
				'payment_mode' => Yii::t ( 'app', 'Payment Mode' ),
				'currency' => Yii::t ( 'app', 'Currency' ),
				'payer_id' => Yii::t ( 'app', 'Payer' ),
				'reciever_id' => Yii::t ( 'app', 'Reciever' ),
				'amount' => Yii::t ( 'app', 'Amount' ),
				'model_id' => Yii::t ( 'app', 'Model' ),
				'model_type' => Yii::t ( 'app', 'Model Type' ),
				'state_id' => Yii::t ( 'app', 'State' ),
				'type_id' => Yii::t ( 'app', 'Type' ),
				'role_id' => Yii::t ( 'app', 'Role' ),
				'created_on' => Yii::t ( 'app', 'Created On' ),
				'created_by_id' => Yii::t ( 'app', 'Created By' ) 
		];
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'created_by_id' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getPayer() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'payer_id' 
		] );
	}
	
	/**
	 *
	 * @return \yii\db\ActiveQuery
	 */
	public function getReciever() {
		return $this->hasOne ( User::className (), [ 
				'id' => 'reciever_id' 
		] );
	}
	public static function getHasManyRelations() {
		$relations = [ ];
		return $relations;
	}
	public static function getHasOneRelations() {
		$relations = [ ];
		$relations ['created_by_id'] = [ 
				'createdBy',
				'User',
				'id' 
		];
		$relations ['payer_id'] = [ 
				'payer',
				'User',
				'id' 
		];
		$relations ['reciever_id'] = [ 
				'reciever',
				'User',
				'id' 
		];
		return $relations;
	}
	public function beforeDelete() {
		return parent::beforeDelete ();
	}
	public function asJson($with_relations = false) {
		$json = [ ];
		$json ['id'] = $this->id;
		$json ['transaction_num'] = $this->transaction_num;
		$json ['payment_mode'] = $this->payment_mode;
		$json ['currency'] = $this->currency;
		$json ['payer_id'] = $this->payer_id;
		$json ['reciever_id'] = $this->reciever_id;
		$json ['amount'] = $this->amount;
		$json ['model_id'] = $this->model_id;
		$json ['model_type'] = $this->model_type;
		$json ['state_id'] = $this->state_id;
		$json ['type_id'] = $this->type_id;
		$json ['role_id'] = $this->role_id;
		$json ['created_on'] = $this->created_on;
		$json ['created_by_id'] = $this->created_by_id;
		if ($with_relations) {
			// CreatedBy
			$list = $this->getCreatedBy ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['CreatedBy'] = $relationData;
			} else {
				$json ['CreatedBy'] = $list;
			}
			// Payer
			$list = $this->getPayer ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['Payer'] = $relationData;
			} else {
				$json ['Payer'] = $list;
			}
			// Reciever
			$list = $this->getReciever ()->all ();
			
			if (is_array ( $list )) {
				$relationData = [ ];
				foreach ( $list as $item ) {
					$relationData [] = $item->asJson ();
				}
				$json ['Reciever'] = $relationData;
			} else {
				$json ['Reciever'] = $list;
			}
		}
		return $json;
	}
}
