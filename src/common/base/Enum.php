<?php


namespace ccheng\eav\common\base;

use common\helpers\ArrayHelper;

abstract class Enum
{
    /**
     * @return array
     */
    abstract public static function getMap(): array;

    /**
     * @param $key
     * @return string
     */
    public static function getValue($key): string
    {
        return static::getMap()[$key] ?? '';
    }

    /**
     * @param array $keys
     * @return array
     */
    public static function getValues(array $keys): array
    {
        return ArrayHelper::filter(static::getMap(), $keys);
    }

    /**
     * @return array
     */
    public static function getKeys(): array
    {
        return array_keys(static::getMap());
    }

    /**
     * @param string $val
     * @return string
     */
    public static function getKeyByVal($val)
    {
        return array_search($val, static::getMap());
    }
}