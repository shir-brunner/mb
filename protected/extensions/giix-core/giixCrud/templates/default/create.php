<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n
\$this->breadcrumbs = array(
	\$model->label(2) => array('index'),
	Yii::t('app', 'Create'),
);\n";
echo '?>';
?>

<div>
	<div>
		<span><?php echo '<?php'; ?> echo Yii::t('app', 'Create') . ' ' . GxHtml::encode($model->label()) . ' ' . GxHtml::encode(GxHtml::valueEx($model)); ?></span>
		<?php echo '<?php'; ?> $this->widget('application.components.BackToIndexPageWidget'); ?>
	</div>
	<div>
		<?php echo "<?php\n"; ?>
		$this->renderPartial('_form', array(
		'model' => $model,
		'buttons' => 'create'));
		?>
	</div>
</div>
