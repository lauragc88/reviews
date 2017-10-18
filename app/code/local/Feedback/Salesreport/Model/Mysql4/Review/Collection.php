<?php

class Feedback_Salesreport_Model_Mysql4_Review_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    protected function _construct()
    {
        $this->_init('salesreport/review','review_id');
    }

    // Filtramos los reviews por estado
    public function addStatusFilter($status)
    {
        if (is_string($status)) {
            $statuses = array_flip(Mage::helper('review')->getReviewStatuses());
            $status = isset($statuses[$status]) ? $statuses[$status] : 0;
        }
        if (is_numeric($status)) {
            $this->addFilter('status',
                $this->getConnection()->quoteInto('main_table.status_id=?', $status),
                'string');
        }
        return $this;
    }


}
