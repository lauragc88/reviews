<?php

class Feedback_Salesreport_Block_Form extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('salesreport/form.phtml');
    }

    public function getAction()
    {
        $orderId = Mage::app()->getRequest()->getParam('order_id', false);
        return Mage::getUrl('reviews/index/post', array('orderId' => $orderId));
    }

    //Creamos los ratings, poniendo como codigo el nombre del campo en la table sql
    public function getRatings()
    {
			$ratings = array(
				'atencion_cliente' => $this->__('Customer Support'),
				'entrega' => $this->__('Delivery'),
				'producto' => $this->__('Product'),
			);

      return $ratings;
    }
}
