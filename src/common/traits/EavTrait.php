<?php

namespace ccheng\eav\common\traits;

use common\enums\FlagEnum;
use common\helpers\ArrayHelper;
use common\models\eav\EavAttributeValue;
use common\models\eav\EavEntity;
use common\models\eav\EavEntityModel;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Trait EavTrait
 * @package common\traits
 * @property EavEntityModel[] $eavEntityModels
 * @property EavEntity[] $existEavEntitys
 * @property EavEntity[] $eavEntitys
 * @property array $eavAttr
 */
trait EavTrait
{
    public function getEavEntityModel($entity_code)
    {
        return $this->hasOne(EavEntityModel::class, ['entity_model_model_id' => 'id'])->andOnCondition(['entity_model_entity_code' => $entity_code, 'entity_model_model_class' => self::class]);
    }

    public function getEavEntityModels()
    {
        return $this->hasMany(EavEntityModel::class, ['entity_model_model_id' => 'id'])->andOnCondition(['entity_model_model_class' => self::class]);
    }

    public function getExistEavEntitys()
    {
        return $this->hasMany(EavEntity::class, ['id' => 'entity_model_entity_id'])->via('eavEntityModels');
    }

    public function getExistEavEntity($entity_code)
    {
        return $this->hasOne(EavEntity::class, ['id' => 'entity_model_entity_id'])->viaTable('eav_entity_model', ['entity_model_model_id' => 'id'], function (ActiveQuery $q) use ($entity_code) {
            return $q->andOnCondition(['entity_model_entity_code' => $entity_code]);
        });
    }

    public function getEavEntitys()
    {
        return EavEntity::find()->where(['entity_model_class' => self::class])->all();
    }

    public function getEavEntitysQuery()
    {
        return EavEntity::find()->where(['entity_model_class' => self::class]);
    }

    public function getEavEntity($entity_code)
    {
        return EavEntity::find()->where(['entity_model_class' => self::class, 'entity_code' => $entity_code])->one();
    }

    public function getEavEntityAttributeValue($entity_code, $attribute_code)
    {
        $class_name = self::class;

        return $this->hasOne(EavAttributeValue::class, ['attribute_value_entity_model_id' => 'id'])->viaTable(EavEntityModel::tableName(), ['entity_model_model_id' => 'id'], function (ActiveQuery $q) use ($class_name, $entity_code) {
            return $q->andOnCondition(['entity_model_model_class' => $class_name, 'entity_model_entity_code' => $entity_code]);
        })->andOnCondition(['attribute_value_attribute_code' => $attribute_code]);
    }

    public function getEavEntityAttributeValuesByEntityCode($entity_code)
    {
        $class_name = self::class;

        return $this->hasMany(EavAttributeValue::class, ['attribute_value_entity_model_id' => 'id'])->viaTable(EavEntityModel::tableName(), ['entity_model_model_id' => 'id'], function (ActiveQuery $q) use ($class_name, $entity_code) {
            return $q->andOnCondition(['entity_model_model_class' => $class_name, 'entity_model_entity_code' => $entity_code]);
        });
    }

    public function getEavEntityAllAttributeValuesQuery()
    {
        $class_name = self::class;

        return $this->hasMany(EavAttributeValue::class, ['attribute_value_entity_model_id' => 'id'])->viaTable(EavEntityModel::tableName(), ['entity_model_model_id' => 'id'], function (ActiveQuery $q) use ($class_name) {
            return $q->andOnCondition(['entity_model_model_class' => $class_name]);
        });
    }

    /**
     * @return array
     */
    public function getEavEntityAllAttributeValues()
    {
        return $this->getEavEntityAllAttributeValuesQuery()->select(['attribute_value', 'eav_attribute_value.id'])->indexBy('id')->column();
    }

    public function getEavAttr()
    {
        $query = $this->getEavEntityModels()->innerJoinWith(['eavEntityAttributeValues', 'eavEntityAttributeValues.eavAttribute'], false)->select([
            'entity_model_entity_code',
            'attribute_multiple_value',
            'attribute_value_attribute_code',
            'attribute_value'
        ]);
        $eavAllAttrData = $query->asArray()->all();
        $result = [];
        if (!empty($eavAllAttrData)) {
            foreach ($eavAllAttrData as $item) {
                $attr_code = $item['attribute_value_attribute_code'];
                if ($item['attribute_multiple_value'] == FlagEnum::YES) {
                    $result[$item['entity_model_entity_code']][$attr_code] = json_decode($item['attribute_value'], true);
                } else {
                    $result[$item['entity_model_entity_code']][$attr_code] = $item['attribute_value'];
                }
            }
        }
        return $result;

    }

}