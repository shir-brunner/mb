<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();

	public $layoutParams = array();

	public $metaTags = array();

	protected function afterAction($action)
	{
		$this->logQueries();

		return parent::afterAction($action);
	}

	protected function logQueries()
	{
		$queriesCount = count(array_filter(Yii::getLogger()->getLogs(), function($logEntry) {
			if($logEntry[2] == 'system.db.CDbCommand')
			{
				//Logger::log(Logger::LEVEL_TRACE, $logEntry[0]);
			}

			return $logEntry[2] == 'system.db.CDbCommand';
		}));

		if($queriesCount > 15)
		{
			//Logger::log(Logger::LEVEL_WARNING, 'Too many queries on page (' . $queriesCount . ' queries)');
		}
	}
}