<?php

/**
 * Company: ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 * Author : Shiv Charan Panjeta < shiv@toxsl.com >
 */
 
/**
* This is the model class for table "tbl_email_queue".
*
    * @property integer $id
    * @property string $from_email
    * @property string $to_email
    * @property string $message
    * @property string $subject
    * @property string $date_published
    * @property string $last_attempt
    * @property string $date_sent
    * @property integer $attempts
    * @property integer $state_id
*/

namespace app\models;

use Yii;
use yii\components;


class EmailQueue extends \app\components\TActiveRecord
{
	public  function __toString()
	{
		return (string)$this->from_email;
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


	

	/**
	* @inheritdoc
	*/
	public static function tableName()
	{
		return '{{%email_queue}}';
	}

	/**
	* @inheritdoc
	*/
	public function rules()
	{
		return [
            [['message'], 'string'],
            [['date_published', 'last_attempt', 'date_sent'], 'safe'],
            [['attempts', 'state_id'], 'integer'],
            [['from_email', 'to_email'], 'string', 'max' => 128],
            [['subject'], 'string', 'max' => 255]
        ];
	}

	/**
	* @inheritdoc
	*/


	public function attributeLabels()
	{
		return [
				    'id' => Yii::t('app', 'ID'),
				    'from_email' => Yii::t('app', 'From Email'),
				    'to_email' => Yii::t('app', 'To Email'),
				    'message' => Yii::t('app', 'Message'),
				    'subject' => Yii::t('app', 'Subject'),
				    'date_published' => Yii::t('app', 'Date Published'),
				    'last_attempt' => Yii::t('app', 'Last Attempt'),
				    'date_sent' => Yii::t('app', 'Date Sent'),
				    'attempts' => Yii::t('app', 'Attempts'),
				    'state_id' => Yii::t('app', 'State'),
				];
	}
    public static function getHasManyRelations()
    {
    	$relations = [];
		return $relations;
	}
    public static function getHasOneRelations()
    {
    	$relations = [];
		return $relations;
	}

	

    public function asJson($with_relations=false)
	{
		$json = [];
			$json['id'] 	= $this->id; 		
			$json['from_email'] 	= $this->from_email; 		
			$json['to_email'] 	= $this->to_email; 		
			$json['message'] 	= $this->message; 		
			$json['subject'] 	= $this->subject; 		
			$json['date_published'] 	= $this->date_published; 		
			$json['last_attempt'] 	= $this->last_attempt; 		
			$json['date_sent'] 	= $this->date_sent; 		
			$json['attempts'] 	= $this->attempts; 		
			$json['state_id'] 	= $this->state_id; 		
			if ($with_relations)
		    {
			}
		return $json;
	}
	public function beforeSave($insert) {
		return parent::beforeSave ( $insert );
	}
	public function beforeDelete() {
		return parent::beforeDelete ();
	}
	public function afterSave($insert = true, $changedAttributes = NULL) {
		return parent::afterSave($insert,$changedAttributes);
	}
	public function sendNow($to = null, $message, $from = null, $sub = null, $attachmentsPath = []) {
		$to = (isset ( $to )) ? $to : \Yii::$app->params ['adminEmail'];
		$from = (isset ( $from )) ? $from : \Yii::$app->params ['adminEmail'];
		$sub = (isset ( $sub )) ? $sub : null;
		$mail = \Yii::$app->mailer->compose ()->setHtmlBody ( $message )->setTo ( $to )->setFrom ( $from )->setSubject ( $sub );
		if ($attachmentsPath) {
			if (is_array ( $attachmentsPath )) {
				foreach ( $attachmentsPath as $file ) {
					if (file_exists ( $file ) && ! is_dir ( $file ))
						$mail->attach ( $fileName );
				}
			} else {
				if (file_exists ( $attachmentsPath ) && ! is_dir ( $attachmentsPath ))
					$mail->attach ( $attachmentsPath );
			}
		}
		$mail_sent = 0;
		try {
			$mail_sent = $mail->send ();
		} catch ( \Exception $e ) {
			\Yii::error($e->getMessage(),'EmailQueue-ERROR');
			\Yii::error($e->getTraceAsString(),'EmailQueue-ERROR-TRACE');
		}
		if (! $mail_sent) {
			return $this->saveEmail ( $to, $message, $from, $sub );
		} else {
			if (! $this->isNewRecord) {
				$this->delete ();
			}
			return true;
		}
	}
	public function saveEmail($to, $message, $from, $sub) {
		$this->to_email = $to;
		$this->message = $message;
		$this->from_email = $from;
		$this->subject = $sub;
		return $this->save ();
	}
	public function add($args = []) {
		/*
		 * arguments :
		 *
		 * to (required, mail send to)
		 * view or message (required only one of the two,view is the mail html file in mail folder)
		 * viewArgs ( it should be an associative array with arguments you want to pass in the mail html file )
		 * from (sender's email, by default it is \Yii::$app->params ['adminEmail'])
		 * subject (optional)
		 */
		if (! $args)
			return false;
			
			if (isset ( $args ['view'] )) {
				$this->message = (isset ( $args ['viewArgs'] )) ? Html::encode ( \Yii::$app->mailer->render ( '@app/mail/' . $args ['view'], $args ['viewArgs'] ) ) : Html::encode ( \Yii::$app->mailer->render ( '@app/mail/' . $args ['view'] ) );
			} else {
				$this->message = $args ['message'];
			}
			$this->from_email = (isset ( $args ['from'] )) ? $args ['from'] : \Yii::$app->params ['adminEmail'];
			$this->to_email = $args ['to'];
			$this->subject = (isset ( $args ['subject'] )) ? $args ['subject'] : null;
			if ($this->save ()) {
				return true;
			} else {
				return false;
			}
	}
}