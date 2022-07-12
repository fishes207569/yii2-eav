<?php

namespace ccheng\eav\console\migrations;

use yii\db\Migration;

class m220622_134709_cc_eav extends Migration
{
    public function safeUp()
    {
        $sql = <<<EOF
        CREATE TABLE `eav_attribute` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enabled' COMMENT '状态',
          `created_at` datetime NOT NULL COMMENT '创建时间',
          `updated_at` datetime NOT NULL COMMENT '修改时间',
          `attribute_name` varchar(64) NOT NULL COMMENT '实体属性名称',
          `attribute_code` varchar(32) NOT NULL COMMENT '实体属性编码',
          `attribute_is_required` char(3) NOT NULL COMMENT '属性是否必须',
          `attribute_default_value` varchar(255) DEFAULT NULL COMMENT '属性默认值',
          `attribute_multiple_value` char(3) DEFAULT NULL COMMENT '属性是否为多个值',
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
        CREATE TABLE `eav_attribute_value` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enabled' COMMENT '状态',
          `created_at` datetime NOT NULL COMMENT '创建时间',
          `updated_at` datetime NOT NULL COMMENT '修改时间',
          `attribute_value` varchar(255) DEFAULT NULL COMMENT '属性值',
          `attribute_value_entity_attribute_id` int(11) NOT NULL COMMENT '属性ID',
          `attribute_value_entity_id` int(11) NOT NULL COMMENT '实体ID',
          `attribute_value_entity_model_id` int(11) NOT NULL COMMENT '实体模型ID',
          `attribute_value_attribute_code` varchar(32) NOT NULL COMMENT '实体属性编码',
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
        CREATE TABLE `eav_entity` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enabled' COMMENT '状态',
          `created_at` datetime NOT NULL COMMENT '创建时间',
          `updated_at` datetime NOT NULL COMMENT '修改时间',
          `entity_model_class` varchar(255) NOT NULL COMMENT '实体模型类',
          `entity_code` varchar(64) NOT NULL COMMENT '实体编码',
          PRIMARY KEY (`id`) USING BTREE,
          UNIQUE KEY `idx_entity_code` (`entity_code`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
        CREATE TABLE `eav_entity_attribute` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enabled' COMMENT '状态',
          `created_at` datetime NOT NULL COMMENT '创建时间',
          `updated_at` datetime NOT NULL COMMENT '修改时间',
          `entity_attribute_attribute_id` int(10) NOT NULL COMMENT '属性ID',
          `entity_attribute_entity_id` int(10) NOT NULL COMMENT '实体ID',
          `entity_attribute_attribute_code` varchar(64) DEFAULT NULL COMMENT '属性编码',
          PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
        CREATE TABLE `eav_entity_model` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enabled' COMMENT '状态',
          `created_at` datetime NOT NULL COMMENT '创建时间',
          `updated_at` datetime NOT NULL COMMENT '修改时间',
          `entity_model_entity_id` int(10) NOT NULL COMMENT '实体ID',
          `entity_model_entity_code` varchar(64) NOT NULL COMMENT '实体模型编码',
          `entity_model_model_id` int(10) NOT NULL COMMENT '模型ID',
          `entity_model_model_class` varchar(128) NOT NULL COMMENT '模型类',
          PRIMARY KEY (`id`) USING BTREE,
          KEY `idx_entity_type` (`entity_model_entity_id`) USING BTREE
        ) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
EOF;
        return $this->execute($sql);
    }

    public function safeDown()
    {
        $sql = <<<EOF
DROP TABLE IF EXISTS `eav_attribute`;DROP TABLE IF EXISTS `eav_attribute_value`;DROP TABLE IF EXISTS `eav_entity`;DROP TABLE IF EXISTS `eav_entity_attribute`;DROP TABLE IF EXISTS `eav_entity_model`;
EOF;
        return $this->execute($sql);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
