<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 *
 * @package YiiSmart
 * @version 1.0
 */

/**
 * Class SmartFormModel
 *
 * @property boolean isPosted
 */
abstract class SmartFormModel extends CFormModel
{
    /**
     * Returns class name in static calls.
     *
     * @return string
     */
    protected static function className()
    {
        return get_called_class();
    }

    /**
     * Validates model using ajax.
     *
     * @param string|null $form
     * @return bool
     */
    public function performAjaxValidation($form = null)
    {
        if (Yii::app()->request->isAjaxRequest) {
            if ($form != null && isset($_POST['ajax']) && $_POST['ajax'] != $form)
                return false;
            echo CActiveForm::validate($this);
            Yii::app()->end();
        }
        return false;
    }

    /**
     * Checks if the model was posted
     *
     * @return bool
     */
    public function getIsPosted()
    {
        return isset($_POST[static::className()]);
    }

    /**
     * Loads model attributes from POST data
     *
     * @param array|null $attributes
     */
    public function loadPostData($attributes = null)
    {
        $class = static::className();
        if (isset($_POST[$class])) {
            $postData = $_POST[$class];
            if (is_array($attributes))
                $postData = array_intersect_key($postData, array_flip($attributes));
            $this->setAttributes($postData);
        }
    }

    /**
     * @param array $data
     * @param array $attributes
     * @return bool
     */
    public function load($data, $attributes = null)
    {
        $class = static::className();
        if (isset($data[$class])) {
            $attributesData = $data[$class];
            if (is_array($attributes))
                $attributesData = array_intersect_key($attributesData, array_flip($attributes));
            $this->setAttributes($attributesData);
            return true;
        }
        return false;
    }
}