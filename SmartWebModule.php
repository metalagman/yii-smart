<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 *
 * @package YiiSmart
 * @version 1.0
 */

/**
 * Class SmartWebModule
 */
class SmartWebModule extends CWebModule
{
    public $modifyBreadcrumbs = true;

    public function init()
    {
        parent::init();

        $this->setImport([
            "{$this->id}.models.*",
            "{$this->id}.controllers.*",
            "{$this->id}.components.*",
        ]);
    }

    /**
     * @param SmartController $controller
     * @param CAction $action
     * @return bool
     */
    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            if ($this->modifyBreadcrumbs == true)
                $controller->breadcrumbs[Yii::t('app', ucfirst($this->id).' Module')] = ["//{$this->id}"];
            return true;
        }
        else
            return false;
    }
}