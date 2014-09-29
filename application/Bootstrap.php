<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{   
    
    private $_auth =null;
    private $_acl =null;
    
    protected function _initPlugins() {
      $frontController = Zend_Controller_Front::getInstance();
      $frontController->registerPlugin( new Tokem_Ssl());        
    }
    
    
//     protected function _initAcl()
//    {   
//      $Acesso = new Tokem_Access();
//      $Acesso->permissions();          
//    }
    
}

