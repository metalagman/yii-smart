<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 *
 * @package YiiSmart
 * @version 1.0
 */

/**
 * Class SmartController
 */
class SmartController extends CController
{
    /** @var array Controller breadcrumbs */
    public $breadcrumbs = [];

    public function init()
    {
        parent::init();

        $acceptType = Yii::app()->request->preferredAcceptType;
        if ($acceptType['subType'] === 'json')
            $this->disableWebLogs();
    }

    public function disableWebLogs()
    {
        foreach (Yii::app()->log->routes as $route)
            if ($route instanceof CWebLogRoute || $route instanceof YiiDebugToolbarRoute)
                $route->enabled = false;
    }
}