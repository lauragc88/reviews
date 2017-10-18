<?php

class Feedback_Salesreport_Model_Mysql4_Review extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('salesreport/review', 'review_id');
    }

    // Guardamos la fecha de creación
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->getId()) {
            $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
        return $this;
    }

}
