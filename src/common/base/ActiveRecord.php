<?php

namespace ccheng\eav\common\base;

use ccheng\eav\common\enums\StatusEnum;
use ccheng\eav\common\traits\ModelTrait;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\OptimisticLockBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * ActiveRecord是用对象表示关系数据的类的基类
 *
 * @property int $id ID
 * @property string $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 *
 * Class ActiveRecord
 * @package common\db
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord
{
    use ModelTrait;


    /**
     * @return array|array[]
     */
    public function behaviors()
    {
        return [
            // 自动用当前时间戳填充指定的属性
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => date('Y-m-d H:i:s'),
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'status',
                ],
                'value' => StatusEnum::ENABLED,
            ],
        ];
    }

    /**
     * 软删除
     * @return bool
     */
    public function softDelete()
    {
        $this->status = StatusEnum::DELETED;
        return $this->save();
    }

    /**
     * 禁用
     * @return bool
     */
    public function disable()
    {
        $this->status = StatusEnum::DISABLED;
        return $this->save();
    }

    /**
     * 启用
     * @return bool
     */
    public function enable()
    {
        $this->status = StatusEnum::ENABLED;
        return $this->save();
    }

    /**
     * 通过ID查询数据
     * @param $id
     * @param int $status
     * @return static|null
     */
    public static function findById($id, $status = null)
    {
        $where = ['id' => $id];
        if ($status !== null) {
            $where['status'] = $status;
        }
        return static::findOne($where);
    }

    public function updateModel($data, $is_update_lock_version = false)
    {
        if ($is_update_lock_version) {
            if (isset($this->behaviors['lock']) && $this->hasAttribute('version_value')) {
                /** @var OptimisticLockBehavior $lockBehavior */
                $lockBehavior = $this->behaviors['lock'];
                $lockBehavior->value = $this->version_value;
            }
        }
        $result = false;
        if ($data) {
            $this->setAttributes($data, false);
            $result = $this->update();

        }
        return $result;
    }
}