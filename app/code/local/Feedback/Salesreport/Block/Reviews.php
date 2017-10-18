<?php

class Feedback_Salesreport_Block_Reviews extends Mage_Core_Block_Template {

	public function __construct()
	{
		parent::__construct();
	}

	//Añadimos el paginador al listado de reviews
	public function _prepareLayout()
  {
		parent::_prepareLayout();
		$pager = $this->getLayout()->createBlock('page/html_pager', 'salesreport.allreviews.pager')
            ->setCollection($this->getAllSReviews());
        $this->setChild('pager', $pager);
        $this->getAllSReviews()->load();
        return $this;
	}

	//Obtenemos todos los reviews aprobados
	public function getAllSReviews()
	{
		if(!$this->hasData('allreviews')) {
			//Mostraremos solo las reviews que esten aprobadas desde el admin
			$collection = Mage::getModel('salesreport/review')->getCollection()
							->addFieldToFilter('status_id',Mage::helper('salesreport')->getApprovedStatus())
							->setOrder('review_id','ASC');
			$this->setData('allreviews', $collection);
		}
		return $this->getData('allreviews');
	}

	public function getPagerHtml()
  {
      return $this->getChildHtml('pager');
  }

}
?>
