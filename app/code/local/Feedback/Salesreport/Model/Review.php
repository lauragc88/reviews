<?php

// Creamos el objeto review
class Feedback_Salesreport_Model_Review extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('salesreport/review');
    }

    public function validate()
    {
        $errors = array();

        if (!Zend_Validate::is($this->getAtencionCliente(), 'NotEmpty')) {
            $errors[] = Mage::helper('salesreport')->__('Customer Support can\'t be empty');
        }

        if (!Zend_Validate::is($this->getEntrega(), 'NotEmpty')) {
            $errors[] = Mage::helper('salesreport')->__('Delivery can\'t be empty');
        }

        if (!Zend_Validate::is($this->getProducto(), 'NotEmpty')) {
            $errors[] = Mage::helper('salesreport')->__('Product can\'t be empty');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

}
