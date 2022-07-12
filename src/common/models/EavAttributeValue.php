<?php

namespace ccheng\eav\common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "eav_attribute_value".
 *
 * @property int $id ID
 * @property string $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property string|null $attribute_value 属性值
 * @property int $attribute_value_entity_attribute_id 实体属性ID
 * @property int $attribute_value_entity_id 实体ID
 * @property int $attribute_value_entity_model_id 实体模型ID
 * @property string $attribute_value_attribute_code 实体属性编码
 * @property EavAttribute $eavAttribute
 * @property EavEntityAttribute $eavEntityAttribute
 * @property EavEntity $eavEntity
 * @property EavEntityModel $eavEntityModel
 */
class EavAttributeValue extends \common\base\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eav_attribute_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attribute_value_entity_attribute_id', 'attribute_value_entity_id', 'attribute_value_entity_model_id'], 'required'],
            [['created_at', 'updated_at', 'attribute_value_attribute_code'], 'safe'],
            [['attribute_value_entity_id', 'attribute_value_entity_model_id', 'attribute_value_entity_attribute_id'], 'integer'],
            ['attribute_value', 'filter', 'filter' => function ($value) {
                if (is_array($value)) {
                    return json_encode($value, JSON_UNESCAPED_UNICODE);
                } else {
                    return $value;
                }
            }],
            [['status', 'attribute_value'], 'string', 'max' => 255],

            [['attribute_value_attribute_code'], 'string', 'max' => 64],
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
            'attribute_value' => '属性值',
            'attribute_value_entity_attribute_id' => '实体属性ID',
            'attribute_value_entity_id' => '实体ID',
            'attribute_value_entity_model_id' => '实体模型ID',
            'attribute_value_attribute_code' => '实体属性编码',
        ];
    }

    public function getEavAttribute()
    {
        return $this->hasOne(EavAttribute::class, ['attribute_code' => 'attribute_value_attribute_code']);
    }

    public function getEavEntityAttribute()
    {
        return $this->hasOne(EavEntityAttribute::class, ['id' => 'attribute_value_entity_attribute_id']);
    }

    public function getEavEntity()
    {
        return $this->hasOne(EavEntity::class, ['id' => 'attribute_value_entity_id']);
    }

    public function getEavEntityModel()
    {
        return $this->hasOne(EavEntityModel::class, ['id' => 'attribute_value_entity_model_id']);
    }
}
