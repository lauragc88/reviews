<?php

class Feedback_Salesreport_Helper_Data extends Mage_Core_Helper_Abstract
{

  const STATUS_APPROVED       = 1;
  const STATUS_PENDING        = 2;
  const STATUS_NOT_APPROVED   = 3;

  //Devuelve codigo de estado aprobado
  public function getApprovedStatus()
  {
      return self::STATUS_APPROVED;
  }
  //Devuelve codigo de estado pendiente
  public function getPendingStatus()
  {
      return self::STATUS_PENDING;
  }

  // Devuelve SI o No dependiendo si esta activada la opción o no.
  public function getEnableAutoSend()
  {
      $enabled = Mage::getStoreConfig('salesreport/general/enabled');
      return $enabled;
  }

  // Devuelve los estados de pedido
  public function getStatusOrders()
  {
      $statuses = Mage::getStoreConfig('salesreport/general/status_order');
      $statuses = explode(",", $statuses);

      return $statuses ? $statuses : Mage_Sales_Model_Order::STATE_COMPLETE;
  }

  // Devuelve el numero de días
  public function getNumberDays()
  {
      $number_days = Mage::getStoreConfig('salesreport/general/number_days');
      return $number_days;
  }

  // Devuelve el sender del email
  public function getSender()
  {
      $sender = Mage::getStoreConfig('salesreport/general/sender_email');
      return $sender;
  }

  // Devuelve la plantilla
  public function getTemplateEmail()
  {
      $template = Mage::getStoreConfig('salesreport/general/template_email');
      return $template;
  }

  // Comprobamos si hay alguna opinion guardada para este pedido
  public function existReview($orderId){

    $collection = Mage::getModel('salesreport/review')->getCollection()
                ->addFieldToFilter('order_id', $orderId);
    // Si ya hay un review creado develveremos FALSE para no permitir volver a opiniar el mismo pedido
    if($collection->count() > 0){
      Mage::getSingleton('core/session')->addError("You have already submitted review for this order.");
      return true;
    }
    return false;
  }

}
