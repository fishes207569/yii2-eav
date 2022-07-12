<?php

namespace ccheng\eav\common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "eav_entity_model".
 *
 * @property int $id ID
 * @property string $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property int $entity_model_entity_id 实体ID
 * @property string $entity_model_entity_code 实体模型编码
 * @property int $entity_model_model_id 模型ID
 * @property string $entity_model_model_class 模型类
 * @property EavEntity $eavEntity
 * @property EavAttributeValue $eavEntityAttributeValue
 * @property EavAttributeValue[] $eavEntityAttributeValues
 */
class EavEntityModel extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eav_entity_model';
    }

    /** @var EavEntity */
    protected $eavEntity;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'entity_model_entity_id', 'entity_model_entity_code', 'entity_model_model_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            ['entity_model_entity_id', 'checkEntity'],
            ['entity_model_model_class', 'default', 'value' => function () {
                return $this->eavEntity->entity_model_class;
            }],
            [['entity_model_entity_id', 'entity_model_model_id'], 'integer'],
            [['status'], 'string', 'max' => 255],
            [['entity_model_entity_code'], 'string', 'max' => 64],
        ];
    }

    public function checkEntity($attribute, $params)
    {
        $eavEntity = EavEntity::findOne($this->$attribute);
        if ($eavEntity) {
            $this->eavEntity = $eavEntity;
        } else {
            $this->addError($attribute, 'Eav 实体不存在');
        }

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
            'entity_model_entity_id' => '实体ID',
            'entity_model_entity_code' => '实体模型编码',
            'entity_model_model_id' => '模型ID',
            'entity_model_model_class' => '模型类',
        ];
    }

    public function getEavEntity()
    {
        return $this->hasOne(EavEntity::class, ['id' => 'entity_model_entity_id']);
    }


    public function getEavEntityAttributeValue($attribute_code)
    {
        return $this->hasOne(EavAttributeValue::class, ['attribute_value_entity_model_id' => 'id'])->andOnCondition(['attribute_value_attribute_code' => $attribute_code]);
    }

    public function getEavEntityAttributeValues()
    {
        return $this->hasMany(EavAttributeValue::class, ['attribute_value_entity_model_id' => 'id']);
    }

}
