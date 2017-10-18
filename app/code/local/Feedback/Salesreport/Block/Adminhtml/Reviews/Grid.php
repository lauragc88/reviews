<?php

class Feedback_Salesreport_Block_Adminhtml_Reviews_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('reviewGrid');
      $this->setDefaultSort('review_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  // Obtenemos el collection
  protected function _prepareCollection()
  {
    $model = Mage::getModel('salesreport/review');
    $collection = $model->getCollection();

    if ($this->getOrderId() || $this->getRequest()->getParam('orderId', false)) {
        $orderId = $this->getOrderId();
        if (!$orderId) {
            $orderId = $this->getRequest()->getParam('orderId');
        }
        $this->setOrderId($orderId);
        $collection->addEntityFilter($this->getOrderId());
    }
    //Si estamos en la pagina de pendientes filtraremos las reviews con estado pendiente
    if (Mage::registry('usePendingFilter') === true) {
        $collection->addStatusFilter(Mage::helper('salesreport')->getPendingStatus());
    }

    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
    $this->addColumn('review_id', array(
        'header'        => Mage::helper('salesreport')->__('Review ID'),
        'align'         => 'right',
        'width'         => '50px',
        'filter_index'  => 'review_id',
        'index'         => 'review_id',
    ));

    $this->addColumn('created_at', array(
        'header'        => Mage::helper('salesreport')->__('Created On'),
        'align'         => 'left',
        'type'          => 'datetime',
        'width'         => '100px',
        'filter_index'  => 'created_at',
        'index'         => 'created_at',
    ));

    $this->addColumn('order_id', array(
        'header'        => Mage::helper('salesreport')->__('Order #'),
        'align'         => 'left',
        'width'         => '100px',
        'filter_index'  => 'order_id',
        'index'         => 'order_id',
        'type'          => 'int',
        'truncate'      => 50,
    ));

    $this->addColumn('atencion_cliente', array(
        'header'        => Mage::helper('salesreport')->__('Customer Support'),
        'align'         => 'left',
        'width'         => '100px',
        'filter_index'  => 'atencion_cliente',
        'index'         => 'atencion_cliente',
        'type'          => 'int',
        'truncate'      => 50,
    ));

    $this->addColumn('entrega', array(
        'header'        => Mage::helper('salesreport')->__('Delivery'),
        'align'         => 'left',
        'width'         => '100px',
        'filter_index'  => 'entrega',
        'index'         => 'entrega',
        'type'          => 'int',
        'truncate'      => 50,
    ));

    $this->addColumn('producto', array(
        'header'        => Mage::helper('salesreport')->__('Product'),
        'align'         => 'left',
        'width'         => '100px',
        'filter_index'  => 'producto',
        'index'         => 'producto',
        'type'          => 'int',
        'truncate'      => 50,
    ));

    $this->addColumn('comment', array(
        'header'        => Mage::helper('salesreport')->__('Comment'),
        'align'         => 'left',
        'index'         => 'comment',
        'filter_index'  => 'comment',
        'type'          => 'text',
        'truncate'      => 50,
        'nl2br'         => true,
        'escape'        => true,
    ));

    if( !Mage::registry('usePendingFilter') ) {
        $this->addColumn('status', array(
            'header'        => Mage::helper('salesreport')->__('Status'),
            'align'         => 'left',
            'type'          => 'options',
            'options'       => Mage::helper('review')->getReviewStatuses(),
            'width'         => '100px',
            'filter_index'  => 'status_id',
            'index'         => 'status_id',
        ));
    }

    $this->addExportType('*/*/exportCsv', Mage::helper('salesreport')->__('CSV'));
    $this->addExportType('*/*/exportXml', Mage::helper('salesreport')->__('XML'));

    return parent::_prepareColumns();

  }

  // Preparamos las accciones masivas
  protected function _prepareMassaction() {
    $this->setMassactionIdField('review_id');
    $this->setMassactionIdFilter('review_id');
    $this->setMassactionIdFieldOnlyIndexValue(true);
    $this->getMassactionBlock()->setFormFieldName('salesreport');
    $this->getMassactionBlock()->setUseSelectAll( false );

    $this->getMassactionBlock()->addItem('delete', array(
         'label'    => Mage::helper('salesreport')->__('Delete'),
         'url'      => $this->getUrl('*/*/massDelete'),
         'confirm'  => Mage::helper('salesreport')->__('Are you sure?')
    ));

    $statuses = Mage::helper('review')->getReviewStatuses();

    array_unshift($statuses, array('label'=>'', 'value'=>''));
    $this->getMassactionBlock()->addItem('status', array(
         'label'=> Mage::helper('salesreport')->__('Change status'),
         'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
         'additional' => array(
                'visibility' => array(
                     'name' => 'status',
                     'type' => 'select',
                     'class' => 'required-entry',
                     'label' => Mage::helper('salesreport')->__('Status'),
                     'values' => $statuses
                 )
         )
    ));

    return $this;
  }

}
