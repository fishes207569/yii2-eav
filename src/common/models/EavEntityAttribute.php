<?php

namespace ccheng\eav\common\models;

use Yii;

/**
 * This is the model class for table "eav_entity_attribute".
 *
 * @property int $id ID
 * @property string $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property int $entity_attribute_attribute_id 属性ID
 * @property string $entity_attribute_entity_id 实体ID
 * @property string $entity_attribute_attribute_code 属性 code
 */
class EavEntityAttribute extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eav_entity_attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'entity_attribute_attribute_id', 'entity_attribute_entity_id', 'entity_attribute_attribute_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['entity_attribute_attribute_id'], 'integer'],
            [['status'], 'string', 'max' => 255],
            [['entity_attribute_entity_id'], 'string', 'max' => 10],
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
            'entity_attribute_attribute_id' => '属性ID',
            'entity_attribute_entity_id' => '实体ID',
            'entity_attribute_attribute_code' => '属性 code',
        ];
    }
}
