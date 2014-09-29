<?php

class Tokem_Access
{
       
    public function  permissions(){
        
          /*Adiciona os papeis */ 
        $acl = new Zend_Acl();
        $acl->addRole(new Zend_Acl_Role('administrador'));   
        $acl->addRole(new Zend_Acl_Role('operador'));
        $acl->addRole(new Zend_Acl_Role('cliente'));
        $acl->addRole(new Zend_Acl_Role('social'));      
        
        /*Adicionaos recursos ou paginas que podem ser vistas*/
        $acl->addResource('index')
        ->addResource('tarefa')
        ->addResource('aplicativos')
        ->addResource('usuario')
        ->addResource('cliente')
        ->addResource('suporte')
        ->addResource('social')
        ->addResource('notificacao');
        
        //$acl->deny('administrador','cliente','operador');
        
        try {
            //$acl->allow(array('administrador','operador','cliente','social'));
            $acl->allow('administrador',array('tarefa','aplicativos','usuario','cliente','suporte','social','notificacao'));
            $acl->allow('operador',array('tarefa','suporte','notificacao'));
            $acl->allow('social',array('aplicativos','suporte','social','notificacao','tarefa'));
            
        } catch (Exception $exc) {
            echo "<pre>".$exc->getMessage()."</pre>";
        }
        
       Zend_Registry::set('acl', $acl);
        
    }
   
}
