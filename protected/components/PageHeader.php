<?php

namespace app\components;

use Yii;
use \yii\helpers\Inflector;

class PageHeader extends TBaseWidget {
	public $title;
	public $subtitle;
	public $model;
	public $showActions = true;
	public $showAdd = true;
	public function run() {
		if ($this->title === null) {
			if ($this->model != null) {
				$this->title = ( string ) $this->model;
			} else
				$this->title = Inflector::pluralize ( Inflector::camel2words ( Yii::$app->controller->id ) );
		}
		if ($this->subtitle === null) {
			
			$this->subtitle = Inflector::camel2words ( Yii::$app->controller->action->id );
		}
		$this->renderHtml ();
	}
	public function renderHtml() {
		?>
		
		
<div class="panel-header">
	<div class="page-head">
		<h1><?php echo \yii\helpers\Html::encode($this->title);?></h1>
           <?php if ($this->model != null) echo $this->model->getStateBadge()?>
			<div class="state-information">

			<div class="text-right">
			<?php if($this->showActions):?>

		<?=  \app\components\TToolButtons::widget(); ?>
			<?php endif;?>
			</div>
		</div>

	</div>
	<!-- panel-menu -->
</div>

<?php
	}
}