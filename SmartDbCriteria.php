<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 *
 * @package YiiSmart
 * @version 1.0
 */

/**
 * Class SmartDbCriteria
 */
class SmartDbCriteria extends CDbCriteria
{
    /**
     * Add datetime comparison using localized date
     *
     * @param string $column
     * @param string $value
     */
    public function compareDate($column, $value)
    {
        $this->compare($column,
            strlen($value) ?
                date('Y-m-d', CDateTimeParser::parse($value, Yii::app()->locale->dateFormat))
                : ''
        );
    }
}
