<?php

namespace ccheng\eav\common\models;

use common\enums\FlagEnum;
use common\traits\ModelTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "eav_attribute".
 *
 * @property int $id ID
 * @property string $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property string $attribute_name 实体属性名称
 * @property string $attribute_code 实体属性编码
 * @property string $attribute_is_required 属性是否必须
 * @property string $attribute_default_value 属性默认值
 * @property string $attribute_multiple_value 属性是否为多个值
 * @property array|string $defaultValue
 */
class EavAttribute extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eav_attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_name', 'attribute_code', 'attribute_is_required'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            ['attribute_default_value', 'filter', 'filter' => function ($value) {
                if ($this->attribute_multiple_value == FlagEnum::YES) {
                    return json_encode($value);
                } else {
                    return $value;
                }
            }],
            [['status', 'attribute_default_value'], 'string', 'max' => 255],
            [['attribute_name'], 'string', 'max' => 64],
            [['attribute_code'], 'string', 'max' => 32],
            [['attribute_is_required', 'attribute_multiple_value'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'attribute_name' => '实体属性名称',
            'attribute_code' => '实体属性编码',
            'attribute_is_required' => '属性是否必须',
            'attribute_default_value' => '属性默认值',
            'attribute_multiple_value' => '属性是否为多个值',
        ];
    }

    public function getDefaultValue()
    {
        if ($this->attribute_multiple_value == FlagEnum::YES) {
            return json_decode($this->attribute_default_value, true);
        } else {
            return $this->attribute_default_value;
        }
    }
}
