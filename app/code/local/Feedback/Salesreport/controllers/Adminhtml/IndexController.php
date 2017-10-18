<?php

class Feedback_Salesreport_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
	//Muestra todos los reviews
	public function indexAction()
	{
    $this->_title($this->__('All Reviews'));
		if ($this->getRequest()->getParam('ajax')) {
				return $this->_forward('reviewGrid');
		}
		$this->loadLayout();
		$this->_setActiveMenu( 'sales/salesreport' );
  	$this->_addContent($this->getLayout()->createBlock('salesreport/adminhtml_reviews'));
		$this->renderLayout();
	}

	//Muestra todos los reviews en estado pending
	public function pendingAction()
	{
			$this->_title($this->__('Pending Reviews'));

			if ($this->getRequest()->getParam('ajax')) {
					Mage::register('usePendingFilter', true);
					return $this->_forward('reviewGrid');
			}

			$this->loadLayout();
			$this->_setActiveMenu('sales/salesreport');

			Mage::register('usePendingFilter', true);
	  	$this->_addContent($this->getLayout()->createBlock('salesreport/adminhtml_reviews'));

			$this->renderLayout();
	}

	//Permite eliminar los reviews de forma masiva
  public function massDeleteAction()
	{
    $reviewsIds = $this->getRequest()->getParam('salesreport');
    if(!is_array($reviewsIds)) {
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
      } else {
          try {
              foreach ($reviewsIds as $reviewId) {
                  $review = Mage::getModel('salesreport/review')->load($reviewId);
                  $review->delete();
              }
              Mage::getSingleton('adminhtml/session')->addSuccess(
                  Mage::helper('adminhtml')->__(
                      'Total of %d record(s) were successfully deleted', count($reviewsIds)
                  )
              );
          } catch (Exception $e) {
              Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
          }
      }
      $this->_redirect('*/*/index');
  }

	//Permite camviar el estado de los reviews de forma masiva
	public function massStatusAction()
  {
      $reviewsIds = $this->getRequest()->getParam('salesreport');
      if(!is_array($reviewsIds)) {
          Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
      } else {
          try {
              foreach ($reviewsIds as $reviewId) {
                  $review = Mage::getSingleton('salesreport/review')
                      ->load($reviewId)
                      ->setStatusId($this->getRequest()->getParam('status'))
                      ->setIsMassupdate(true)
                      ->save();
              }
              $this->_getSession()->addSuccess(
                  $this->__('Total of %d record(s) were successfully updated', count($reviewsIds))
              );
          } catch (Exception $e) {
              $this->_getSession()->addError($e->getMessage());
          }
      }
      $this->_redirect('*/*/index');
  }

	//Crea un fichero CSV de los reviews
	public function exportCsvAction()
  {
      $fileName   = 'reviews_orders.csv';
      $content    = $this->getLayout()->createBlock('salesreport/adminhtml_reviews_grid')->getCsv();
      $this->_sendUploadResponse($fileName, $content);
  }

	//Crea un fichero XML de los reviews
  public function exportXmlAction()
  {
      $fileName   = 'reviews_orders.xml';
      $content    = $this->getLayout()->createBlock('salesreport/adminhtml_reviews_grid')->getXml();
      $this->_sendUploadResponse($fileName, $content);
  }

  protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
  {
      $response = $this->getResponse();
      $response->setHeader('HTTP/1.1 200 OK','');
      $response->setHeader('Pragma', 'public', true);
      $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
      $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
      $response->setHeader('Last-Modified', date('r'));
      $response->setHeader('Accept-Ranges', 'bytes');
      $response->setHeader('Content-Length', strlen($content));
      $response->setHeader('Content-type', $contentType);
      $response->setBody($content);
      $response->sendResponse();
      die;
  }

}
?>
