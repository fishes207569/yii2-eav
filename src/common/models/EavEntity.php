<?php

namespace ccheng\eav\common\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "eav_entity".
 *
 * @property int $id ID
 * @property string $status 状态
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 * @property string $entity_model_class 实体模型类
 * @property string $entity_code 实体编码
 * @property EavEntityAttribute[] $eavEntityAttributes
 * @property EavAttribute[] $eavAttributes
 */
class EavEntity extends \common\base\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eav_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'entity_model_class', 'entity_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'entity_model_class'], 'string', 'max' => 255],
            [['entity_code'], 'string', 'max' => 64],
            [['entity_code'], 'unique'],
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
            'entity_model_class' => '实体模型类',
            'entity_code' => '实体编码',
        ];
    }

    public function getEavEntityAttributes()
    {
        return $this->hasMany(EavEntityAttribute::class, ['entity_attribute_entity_id' => 'id']);
    }

    public function getEavEntityAttribute($attribute_code)
    {
        return $this->hasOne(EavEntityAttribute::class, ['entity_attribute_entity_id' => 'id'])->andOnCondition(['entity_attribute_attribute_code' => $attribute_code]);
    }

    public function getEavAttributes()
    {
        return $this->hasMany(EavAttribute::class, ['id' => 'entity_attribute_attribute_id'])->via('eavEntityAttributes');
    }

    public function getEavAttribute($attribute_code)
    {
        $this->hasMany(EavAttribute::class, ['id' => 'entity_attribute_attribute_id'])->viaTable(EavEntityAttribute::tableName(), ['entity_attribute_entity_id' => 'id'], function (ActiveQuery $q) use ($attribute_code) {
            return $q->andOnCondition(['entity_attribute_attribute_code' => $attribute_code]);
        });
    }

}
