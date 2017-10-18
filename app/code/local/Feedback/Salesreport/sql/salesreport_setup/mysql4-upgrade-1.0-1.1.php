<?php

$installer = $this;
$installer->startSetup();

/**
* Create table 'feedback_review'
*/
$table = $installer->getConnection()
->newTable($installer->getTable('feedback_review'))
->addColumn('review_id', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
  'identity'  => true,
  'unsigned'  => true,
  'nullable'  => false,
  'primary'   => true,
), 'Review id')
->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
  'nullable'  => false,
), 'Review create date')
->addColumn('status_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
  'unsigned'  => true,
  'nullable'  => false,
  'default'   => '0',
), 'Status Id')
->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
'unsigned'  => true,
'nullable'  => false,
'default'   => '0',
), 'Order id')
->addColumn('atencion_cliente', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
'nullable'  => true,
'default'   => '0',
), 'Atencion al cliente')
->addColumn('producto', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
'nullable'  => true,
'default'   => '0',
), 'Producto')
->addColumn('entrega', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
'nullable'  => true,
'default'   => '0',
), 'Entrega')
->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
'nullable'  => true,
), 'Comment')
->addIndex($installer->getIdxName('feedback_review', array('status_id')),array('status_id'))
->addIndex($installer->getIdxName('feedback_review', array('order_id')),array('order_id'))
->addForeignKey($installer->getFkName('feedback_review', 'status_id', 'review/review_status', 'status_id'),
'status_id', $installer->getTable('review/review_status'), 'status_id',
Varien_Db_Ddl_Table::ACTION_NO_ACTION, Varien_Db_Ddl_Table::ACTION_NO_ACTION)
->addForeignKey($installer->getFkName('feedback_review','order_id','sales/order', 'increment_id'),
 'order_id', $installer->getTable('sales/order'), 'increment_id',
 Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
->setComment('Review Feedback Orders');
$installer->getConnection()->createTable($table);


$installer->endSetup();
