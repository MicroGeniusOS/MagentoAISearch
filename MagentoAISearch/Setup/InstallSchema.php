<?php
/**
 * AISearch Magento Extension
 *
 * @category  Magento
 * @package   Magento_AISearch
 * @author    Magento
 * @copyright Copyright (c) Magento (https://www.magento.com)
 */

namespace Magento\AISearch\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install schema
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Create table for search history
        $tableName = $installer->getTable('magento_aisearch_history');
        
        if (!$installer->tableExists($tableName)) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'history_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'nullable' => false,
                        'primary' => true,
                        'unsigned' => true,
                    ],
                    'History ID'
                )
                ->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => true,
                    ],
                    'Customer ID'
                )
                ->addColumn(
                    'search_term',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false,
                    ],
                    'Search Term'
                )
                ->addColumn(
                    'search_type',
                    Table::TYPE_TEXT,
                    50,
                    [
                        'nullable' => false,
                    ],
                    'Search Type (image, voice)'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT,
                    ],
                    'Created At'
                )
                ->addIndex(
                    $installer->getIdxName(
                        $tableName,
                        ['customer_id']
                    ),
                    ['customer_id']
                )
                ->addForeignKey(
                    $installer->getFkName(
                        $tableName,
                        'customer_id',
                        'customer_entity',
                        'entity_id'
                    ),
                    'customer_id',
                    $installer->getTable('customer_entity'),
                    'entity_id',
                    Table::ACTION_SET_NULL
                )
                ->setComment('AISearch History Table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
