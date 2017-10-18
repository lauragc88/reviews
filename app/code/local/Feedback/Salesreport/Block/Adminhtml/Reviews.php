<?php

class Feedback_Salesreport_Block_Adminhtml_Reviews extends Mage_Adminhtml_Block_Widget_Grid_Container {

	public function __construct() {
		parent::__construct();

		$this->_blockGroup = 'salesreport';
		$this->_controller = 'adminhtml_reviews';

		if (Mage::registry('usePendingFilter') === true) {
			$this->_headerText = Mage::helper( 'salesreport' )->__( 'Pending orders reviews' );
		}else{
			$this->_headerText = Mage::helper( 'salesreport' )->__( 'Orders reviews' );
		}
		
		$this->_removeButton( 'add' );
	}

}
?>
