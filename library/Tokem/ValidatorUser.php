<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidatorUser
 *
 * @author Rodolfo Almeida
 */
class Tokem_ValidatorUser {
    
    private $_allMensages = null;
    private $_user = null;
    protected $_strip = null;
    protected $_trim = null;
    
    
    public function verifyUser($user){
        
        $this->_strip = new Zend_Filter_StripTags();
        $this->_trim = new Zend_Filter_StringTrim();
        
        $user= str_replace(' ', '',$this->_trim->filter($this->_strip->filter($user)));
        
        $this->_user = new Application_Model_Usuario();
        $search = $this->_user->fetchRow("usr_usuario = '$user' ");
        
        return $valid = isset($search->usr_id);
    }
    
    public function verifyEmail($email){
        
        $this->_strip = new Zend_Filter_StripTags();
        $this->_trim = new Zend_Filter_StringTrim();
        
        $email= $this->_trim->filter($this->_strip->filter($email));
        
        $this->_user = new Application_Model_Usuario();
        $search = $this->_user->fetchRow("usr_email = '$email' ");
        
        return $valid = isset($search->usr_id);
    } 


    public function verifyFacebookId($facebook_id){
        
        $this->_strip = new Zend_Filter_StripTags();
        $this->_trim = new Zend_Filter_StringTrim();
        
        $email= $this->_trim->filter($this->_strip->filter($facebook_id));
        
        $this->_user = new Application_Model_Usuario();
        $search = $this->_user->fetchRow("usr_id_facebook = '$facebook_id' ");
        
        return $valid = isset($search->usr_id);
    } 
    
    public function firist($dataRequest){
        
        /*Validadores*/
        $validatorNoEmpty = new Zend_Validate_NotEmpty();
        $validatorNoEmpty->setMessage('é obrigatório');
        
        $validatorMinChar = new Zend_Validate_StringLength(5);
        $validatorMinChar->setMessage('não pode ser menor que 5');
        
        $validatorEmail = new Zend_Validate_EmailAddress();
        $validatorEmail->setMessage("Forneça um e-mail válido");
        
        /*
         * Validar Vazios
         */
        
        foreach ($dataRequest as $key => $value) {
            
            $$key = $value;
            
            //echo" id= ".$key." value= ".$value; 
            $valid = $validatorNoEmpty->isValid($value);
            $warning = $validatorNoEmpty->getMessages();
            
            if(!$valid){    
                $mensage = $warning["isEmpty"];
                $this->_allMensages["data"]["$key"] = "O campo '$key' $mensage";
                unset($dataRequest[$key]);
            }
        }
        
        /*/
         * Validar email
         */
        if(isset($dataRequest['email'])){
            $valid = $validatorEmail->isValid($dataRequest['email']);
            $warning = $validatorEmail->getMessages();
        
            if(!$valid){    
                $mensage = $warning["emailAddressInvalidFormat"];
                $this->_allMensages["data"]["email"] = "$mensage";
                unset($dataRequest['email']);
            }
        }
        
        
        /*
         * Validar tamanho
         */
        
        foreach ($dataRequest as $key => $value) {
            
            $$key = $value;
            
            $valid = $validatorMinChar->isValid($value);
            $warning = $validatorMinChar->getMessages();
            
            if(!$valid){
                $mensage = $warning["stringLengthTooShort"];
                $this->_allMensages["data"]["$key"] = "O campo '$key' $mensage";
                unset($dataRequest[$key]);
            }
            
        }
        
        /*
         * Validar Senhas Iguais
         */
        if(isset($dataRequest['senha'])){
          if(!($dataRequest['senha']==$dataRequest['repetir'])){
              $this->_allMensages["data"]["senha"] = "Senhas não conferem";
          }  
        }
        
        
        
        /*
         * Validar nome de usuário
         */
        if(isset($dataRequest['usuario'])){
          
           $valid = $this->verifyUser($dataRequest['usuario']);
           
           if($valid){
              $this->_allMensages["data"]["usuario"] = "Usuário indispónivel"; 
           }
           
        }
        
        /*
         * Validar disponibilidade de email
         */
        if(isset($dataRequest['email'])){
          
           $valid = $this->verifyEmail($dataRequest['email']);
           
           if($valid){
              $this->_allMensages["data"]["email"] = "Email já esta sendo utilizado"; 
           }
           
        }
        
            
        if(isset($this->_allMensages)){
            $this->_allMensages["msg"] = "error";          
            return json_encode($this->_allMensages);
            exit;
        }else{
            return TRUE;
        }
        
        
    }
    
}
