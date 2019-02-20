<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n
\$this->breadcrumbs = array(
	{$this->modelClass}::label(2),
	Yii::t('app', 'Index'),
);\n";
echo '?>';
?>
<?php
$tableTitle = trim(str_replace('_', ' ', substr($this->tableSchema->name, 4, strlen($this->tableSchema->name) - 4)));

$visibleColumns = array_filter($this->tableSchema->columns, function($column) {
	$deleted = substr($column->name, strlen($column->name) - strlen('deleted'), strlen('deleted')) == 'deleted';
	return !$deleted;
});

?>

<div>
	<div>
		<span><?php echo '<?php'; ?> echo Yii::t('app', 'Manage') . ' ' . GxHtml::encode($model->label(0)); ?></span>
		<a href="<?php echo '<?php'; ?> echo Yii::app()->createUrl(Yii::app()->controller->id . "/create"); ?>">
		<span class="btn btn-success">New</span>
		</a>
	</div>
	<div>
		<?php echo '<?php'; ?> $this->widget('application.components.LeafGridView', array(
		'id' => '<?php echo $this->class2id($this->modelClass); ?>-grid',
		'dataProvider' => $model->search(),
		'filter' => $model,
		'htmlOptions' => array(
		'role' => 'grid',
		),
		'columns' => array(
		<?php
		$count = 0;
		foreach ($visibleColumns as $column) {
			if ($column->isPrimaryKey) {
				$id_columns_name = str_replace("'","",$this->generateGridViewColumn($this->modelClass, $column));
				echo "\t\tarray(
		            'class' => 'CDataColumn',
		            'header' => Yii::t('app', '#'),
		            'type' => 'raw',
					'name' => '".$id_columns_name."',
		            'value' => 'CHtml::link("."$"."data->".$id_columns_name.", Yii::app()->createUrl(\"".$this->controller."/update\", array(\"id\" => "."$"."data->".$id_columns_name.")))',
					'htmlOptions' => array(
						'title' => Yii::t('app', 'Update'),
					),
		        ),\n";
			}
			else {
				if (++$count == 7)
					echo "\t\t/*\n";
				echo "\t\t\t\t" . $this->generateGridViewColumn($this->modelClass, $column).",\n";
			}
		}
		if ($count >= 7)
			echo "\t\t*/\n";
		?>
		array(
		'class' => 'CButtonColumn',
		'template' => '{delete}',
		'deleteConfirmation' => Yii::t('app', 'Are you sure you want to delete this <?php echo $tableTitle; ?>?'),
		'deleteButtonImageUrl' => Yii::app()->getBaseUrl() . '/images/delete.png',
		'deleteButtonLabel' => Yii::t('app', 'Delete'),
		'deleteButtonOptions' => array(
		'title' => Yii::t('app', 'Delete'),
		'class' => 'grid-view-delete',
		),
		'htmlOptions' => array('class' => 'icon'),
		),
		),
		));
		?>


	</div>
</div>
