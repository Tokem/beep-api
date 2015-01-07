<?php

class ModerateController extends Zend_Controller_Action
{
    
    private $_user = null;
    private $_evento = null;
    private $_atracao = null;
    private $_ingresso = null;
    
    public function init()
    {
        $this->_user = new Application_Model_Usuario();
        $this->_evento = new Application_Model_Evento();
        
    }
 

     public function indexAction()
    {
        
	}


    public function moderateAction()
    {
       
       
       require_once APPLICATION_PATH . '/../library/phpqrcode/qrlib.php';




       QRcode::png('PHP QR Code :)', './qrcode/test.png', 'L', 20, 2);

    }

}