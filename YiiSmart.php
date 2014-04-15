<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 *
 * @package YiiSmart
 * @version 1.0
 */

/**
 * Class YiiSmart
 *
 * Helper for YiiSmart utilities
 */
abstract class YiiSmart
{
    /**
     * @param string $format
     * @param string $value
     * @param DateTimeZone|null $timeZone
     * @return DateTime
     * @throws UnexpectedValueException
     */
    public static function parseDateTime($format, $value, $timeZone = null)
    {
        $result = DateTime::createFromFormat($format, $value);

        if (!$result instanceof DateTime)
            throw new UnexpectedValueException;

        if ($timeZone instanceof DateTimeZone)
            $result->setTimezone($timeZone);

        return $result;
    }
}

