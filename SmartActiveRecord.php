<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 *
 * @package YiiSmart
 * @version 1.0
 */

/**
 * Class SmartActiveRecord
 *
 * @property boolean isPosted
 */
abstract class SmartActiveRecord extends CActiveRecord
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
     * @inheritdoc
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Loads model by id.
     * If not found, throws not found exception.
     *
     * @param $id
     * @return CActiveRecord
     * @throws CHttpException
     */
    public static function loadModel($id)
    {
        $class = static::className();
        $model = $class::model()->findByPk((int)$id);
        if (!$model instanceof CActiveRecord)
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        return $model;
    }

    /**
     * Tries to save model, throwing an exception on any error (including validation).
     *
     * @param bool $runValidation
     * @param array|null $attributes
     * @throws CHttpException
     */
    public function trySave($runValidation = true, $attributes = null)
    {
        $result = $this->save($runValidation, $attributes);
        if ($result === false) {
            $message = 'Object saving error. save() returned ' . CVarDumper::dumpAsString($result) .
                ' Last DB error: ' . CVarDumper::dumpAsString($this->getDbConnection()->pdoInstance->errorInfo());

            if (count($errors = $this->getErrors()) > 0)
                $message = 'Object saving error: ' . CVarDumper::dumpAsString($errors);

            throw new CHttpException(500, $message);
        }
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
     * Set only safe attributes.
     *
     * @param $attributes
     */
    public function safeSetAttributes($attributes)
    {
        $this->setAttributes(array_intersect_key($attributes, array_flip($this->safeAttributeNames)));
    }

    /**
     * Makes list data for using in CHtml::dropDownList() and so on.
     *
     * @param string $textField
     * @param string $valueField
     * @return array
     */
    public static function listData($textField = 'name', $valueField = 'id')
    {
        return CHtml::listData(static::model()->findAll(['select' => [$valueField, $textField]]), $valueField, $textField);
    }

    /**
     * Returns of class constants optionally filtered by prefix.
     *
     * @param $prefix
     * @return array
     */
    public static function getConstants($prefix)
    {
        $class = static::className();
        $reflection = new ReflectionClass($class);

        $result = [];
        foreach ($reflection->getConstants() as $name => $value) {
            if (preg_match("/^{$prefix}/i", $name)) {
                $result[$name] = $value;
            }
        }
        return $result;
    }

    /**
     * Makes list data using class constants.
     *
     * @param string $prefix
     * @return array
     */
    public static function listDataConstants($prefix = '')
    {
        $class = static::className();
        $constants = static::getConstants($prefix);

        $result = [];
        foreach ($constants as $value) {
            $list[$value] = Yii::t('app', $class . '_' . $prefix . '_' . $value);
        }
        return $result;
    }
}
