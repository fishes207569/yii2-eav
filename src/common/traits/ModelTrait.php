<?php

namespace ccheng\eav\common\traits;

use yii\db\ActiveRecord;

trait ModelTrait
{
    /**
     * 获取模型的第一个错误消息
     * @return string
     */
    public function getFirstErrorMsg()
    {
        // 获取模型的所有属性的全部错误消息
        $errors = $this->getErrors();
        if (!$errors) {
            return '';
        }

        // 获取第一个属性的全部错误消息
        $firstAttributeErrors = reset($errors);

        // 返回第一个属性的第一个错误消息
        return reset($firstAttributeErrors);
    }

    public static function getModelError($model)
    {
        /** @var ActiveRecord $model */
        $errors = $model->getErrors();
        if (!is_array($errors)) {
            return true;
        }
        $firstError = array_shift($errors);
        if (!is_array($firstError)) {
            return true;
        }

        return array_shift($firstError);
    }


}