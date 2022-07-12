<?php

namespace ccheng\eav\common\enums;

use ccheng\eav\common\base\Enum;

/**
 * Class StatusEnum
 * @package common\enums
 */
class StatusEnum extends Enum
{
    const ENABLED = 'enabled';
    const DISABLED = 'disabled';
    const DELETED = 'deleted';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            static::ENABLED => '启用',
            static::DISABLED => '禁用',
            static::DELETED => '删除',
        ];
    }
}