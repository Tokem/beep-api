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
class Tokem_ValidatorEvent {
    
    private $_allMensages = null;
    protected $_strip = null;
    protected $_trim = null;
    
    
    public function verifyTag($tag){
        
        $this->_strip = new Zend_Filter_StripTags();
        $this->_trim = new Zend_Filter_StringTrim();
        
        $user= $this->_trim->filter($this->_strip->filter($user));
        
        $this->_user = new Application_Model_Usuario();
        $search = $this->_user->fetchRow("usr_usuario = '$user' ");
        
        return $valid = isset($search->usr_id);
    }
    
    public function create($dataRequest){
        
        /*Validadores*/
        $validatorNoEmpty = new Zend_Validate_NotEmpty();
        $validatorNoEmpty->setMessage('é obrigatório');
        
        $validatorMinChar = new Zend_Validate_StringLength(5,100);
        $validatorMinChar->setMessage('não pode ser menor que 5 ou maior que 100');
        
        $validarHora =  new Zend_Validate_Date('hh:ii');
        $validarHora->setMessage('informe uma hora válida');


        /*
         * Validar Vazios
         */
        
        foreach ($dataRequest as $key => $value) {
            

            if($key=="atracao"){
              foreach ($value as $i){
                echo $i;
              }    
            }

            // $$key = $value;
            // echo" id= ".$key." value= ".$value."<br/>";


            $valid = $validatorNoEmpty->isValid($value);
            $warning = $validatorNoEmpty->getMessages();
            
            if(!$valid){    
                $mensage = $warning["isEmpty"];
                $this->_allMensages["msg-validation"]["$key"] = array("msg"=>"O campo '$key' $mensage");
                unset($dataRequest[$key]);
            }
        }

        exit;

        if(isset($this->_allMensages)){
            return json_encode($this->_allMensages);
            exit;
        }else{
            return TRUE;
        }
        
        
    }
    
}
