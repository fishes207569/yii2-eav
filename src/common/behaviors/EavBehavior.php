<?php

namespace ccheng\eav\common\behaviors;

use common\base\ActiveRecord;
use common\models\eav\EavAttribute;
use common\models\eav\EavEntity;
use common\models\eav\EavEntityModel;
use common\models\eav\EavModel;
use common\models\video\VideoData;
use Yii;
use yii\base\BaseObject;
use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class EavBehavior
 * @package common\behaviors
 */
class EavBehavior extends Behavior
{
    public function events()
    {
        return [
            \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave'
        ];

    }

    /** @var array */
    public $eavData = [];

    /** @var EavEntity[] */
    protected $eavEntitys = [];

    /** @var \ccheng\eav\common\base\ActiveRecord */
    protected $model;

    public function init()
    {
        if (empty($this->eavData)) {
            if (!\Yii::$app->request->getIsConsoleRequest()) {
                $this->eavData = \Yii::$app->request->getBodyParam('eav_attribute');
            }
        }
        parent::init(); // TODO: Change the autogenerated stub
    }

    public function canGetProperty($name, $checkVars = true)
    {
        return EavAttribute::find()->where(['attribute_code' => $name])->exists();
    }

//    protected function getEavData()
//    {
//        if (empty($this->eavData)) {
//            if (!\Yii::$app->request->getIsConsoleRequest()) {
//                $this->eavData = \Yii::$app->request->getBodyParam('eav_attribute');
//            }
//        }
//        return $this->eavData;
//    }

    public function beforeSave(ModelEvent $event)
    {

    }

    public function afterSave(AfterSaveEvent $event)
    {
        if (!empty($this->eavData)) {
            $this->model = $event->sender;
            foreach ($this->eavData as $entityCode => $entityAttrData) {
                $eavEntity = $this->model->getEavEntity($entityCode);
                if (!$eavEntity) {
                    throw new UnprocessableEntityHttpException('eav 实体不存在');
                }
                $eavModel = new EavModel([], [
                    'eavEntity' => $this->model->getEavEntity($entityCode),
                    'model' => $this->model,
                    'eavEntityModel' => $this->model->getEavEntityModel($entityCode)->one()
                ]);
                $eavModel->load($entityAttrData, '');
                $eavModel->save();
            }
        }

    }
}