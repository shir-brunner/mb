<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */

$visibleColumns = array_filter($this->tableSchema->columns, function($column) {
	$deleted = substr($column->name, strlen($column->name) - strlen('deleted'), strlen('deleted')) == 'deleted';
	$creationTime = substr($column->name, strlen($column->name) - strlen('creation_time'), strlen('creation_time')) == 'creation_time';
	$updateTime = substr($column->name, strlen($column->name) - strlen('update_time'), strlen('update_time')) == 'update_time';
	$creatorUserId = substr($column->name, strlen($column->name) - strlen('creator_user_id'), strlen('creator_user_id')) == 'creator_user_id';
	$updatorUserId = substr($column->name, strlen($column->name) - strlen('updator_user_id'), strlen('updator_user_id')) == 'updator_user_id';

	return !$deleted && !$creationTime && !$updateTime && !$creatorUserId && !$updatorUserId;
});

?>
<div class="ibox-content">
	<?php $ajax = ($this->enable_ajax_validation) ? 'true' : 'false'; ?>

	<?php echo '<?php '; ?><?php echo "\n"; ?>
		$form = $this->beginWidget('LeafActiveForm', array(
			'id' => '<?php echo $this->class2id($this->modelClass); ?>-form',
			'enableAjaxValidation' => <?php echo $ajax; ?>,
			'htmlOptions' => array(
				'novalidate' => 'novalidate',
				'enctype' => 'multipart/form-data',
				'class' => 'form-horizontal',
			),
		));
	<?php echo '?>'; ?>


	<p id="fields-required-title" class="note">
		<?php echo "<?php echo Yii::t('app', 'Fields with'); ?> <span class=\"required\">*</span> <?php echo Yii::t('app', 'are required'); ?>"; ?>.
	</p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

	<?php foreach ($visibleColumns as $column): ?><?php if (!$column->autoIncrement): ?><?php echo "\n"; ?>
		<div class="form-group">
			<?php echo "<?php echo " . $this->generateActiveLabel($this->modelClass, $column, array('class' => 'col-sm-2 control-label')) . "; ?>\n"; ?>
			<div class="col-sm-0 col-md-3"><?php echo "<?php " . $this->generateActiveField($this->modelClass, $column, array('class' => 'form-control')) . "; ?>"; ?></div>
		</div>
			<?php endif; ?>
		<?php endforeach; ?>

		<div id="form-save-container">
			<?php echo "<?php
				echo GxHtml::submitButton(Yii::t('app', 'Save'), array('class' => 'btn btn-success'));
				\$this->endWidget();
			?>\n"; ?>
		</div>
</div>