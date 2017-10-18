<?php

class Feedback_Salesreport_IndexController extends Mage_Core_Controller_Front_Action
{

	//Listado de reviews
	public function indexAction()
	{
			$this->loadLayout();
			$this->getLayout()
					->getBlock('head')
					->setTitle(Mage::helper('core')->__('All Reviews orders'));
			$this->renderLayout();
	}

	//Muestra el formulario en caso de que el pedido exista en magento
	public function viewAction()
	{
			$orderId = base64_decode($this->getRequest()->getParam('oid'));
			$session = Mage::getSingleton('core/session');
			try{
				//Comprobamos que el número de pedido no esta vacio y que exista en Magento
				if($orderId!= ''){
					$order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
					if ($order->getId()) {
					  $this->loadLayout();
            $this->renderLayout();
					}
				}
			}catch(Exception $e){
				$session->addException($e, $this->__('There was a problem with this order.'));
				$this->_redirect("/");
			}
	}

	//Guarda la review si todo es correcto y no habia una review para ese pedido
	public function postAction()
	{
			$data   = $this->getRequest()->getPost();
			$session = Mage::getSingleton('core/session');
			//Validamos si hay alguna opinion guardada
			$existe = Mage::helper('salesreport')->existReview($data['order_id']);
			if($existe){
					$this->_redirectReferer();
			}else{
				if (!empty($data)) {
						$review = Mage::getModel('salesreport/review')->setData($data);
						$validate = $review->validate();
						if ($validate === true) {
								try {
									//Creamos el review con estado pendiente
									$review->setStatusId(Mage_Review_Model_Review::STATUS_PENDING)
												->save();
									$session->addSuccess($this->__('Your review has been saved.'));
								}catch (Exception $e) {
										$session->setFormData($data);
										$session->addError($this->__('Unable to post the review.'));
								}
						}else {
								$session->setFormData($data);
								if (is_array($validate)) {
										foreach ($validate as $errorMessage) {
												$session->addError($errorMessage);
										}
								}
								else {
										$session->addError($this->__('Unable to post the review.'));
								}
						}
				}
			}

			$this->_redirectReferer();
	}

}
?>
