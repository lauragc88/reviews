<?php

class Feedback_Salesreport_Model_Observer
{

	/* Cron que se ejecuta cada noche para enviar un email para valorar la atención al cliente, entrega y producto
	   del pedido realizado (el estado de pedido se define en el admin). */
	public function sendEmailFeedback()
	{
		//variables
		$storeId = Mage::app()->getStore()->getId();
		$current_day = Mage::getModel('core/date')->date('Y-m-d');
		$autoSend = Mage::helper('salesreport')->getEnableAutoSend();
		$status_orders = Mage::helper('salesreport')->getStatusOrders();
		$number_days = Mage::helper('salesreport')->getNumberDays();
		$sender = Mage::helper('salesreport')->getSender();
		$template = Mage::helper('salesreport')->getTemplateEmail();

		try {
			// Verificamos que esta activada la opcion de envio automatico
			if($autoSend==FALSE){
				return;
			}
			// A la fecha actual le restamos los dias indicados en el admin
			$fecha_a_procesar = strtotime('-'.$number_days.' day', strtotime($current_day));
			$date_limit = date('Y-m-d H:i:s', $fecha_a_procesar);
			$date_limit_to = date('Y-m-d' . ' 23:59:59', $fecha_a_procesar);

			$orders = Mage::getModel('sales/order')->getCollection();
			//Solo obtenemos los que tienen los estados selecionados en el admin
			$orders->addAttributeToFilter('status', array('in' => $status_orders));
			// Solo procesaremos los que coincidan la fecha procesada anteriormente
			$orders->addAttributeToFilter('created_at', array('from' =>$date_limit,'to' => $date_limit_to));
			
			//Procesamos los pedidos y enviamos mail
			foreach($orders as $order){
				//Comprobamos que si ya se han hecho las valoraciones de este pedido
				$existe = Mage::helper('salesreport')->existReview($order->getData('increment_id'));
				if(!$existe){
					$customerName = $order->getData('customer_firstname').' '.$order->getData('customer_lastname');
					$customerMail = $order->getData('customer_email');

					$reviewUrl  = Mage::getUrl('reviews/index/view/', array('oid'=>base64_encode($order->getData('increment_id'))));

					$translate = Mage::getSingleton('core/translate');
					$translate->setTranslateInline(false);

					$email = Mage::getModel('core/email_template');

					$emailVariables = array();
					$emailVariables['customername'] = $customerName;
					$emailVariables['reviewUrl'] = $reviewUrl;
					$emailVariables['increment_id'] = $order->getData('increment_id');

					$email->setDesignConfig(array('area'=>'frontend'))
						->sendTransactional(
							$template,
							$sender,
							$customerMail,
							$customerName,
							$emailVariables,
							$storeId
						);
						$translate->setTranslateInline(true);

						//Actualizamos el estado del pedido
						$order->addStatusHistoryComment('Email Reviews sent.', $order->getData('status'))
									 ->setIsVisibleOnFront(true)
									 ->setIsCustomerNotified(true);
						$order->save();
					}
			} //endforeach
		} catch (Exception $e) {
		   if (empty($e)){
					Mage::log("An undefined error occurred sending email feedback sales.");
			 }else {
		   		Mage::log($e->getMessage());
			 }
		}
	}

}

?>
