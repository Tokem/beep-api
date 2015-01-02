<?php

class Application_Model_EventoCheck extends Zend_Db_Table
{
    protected $_name = "beep_evento_check";
    protected $_primary = "evc_id";
    

    public function checkDischeck($usr_id,$eve_id){

        $db = $this->getDefaultAdapter();
        
        $query  = $db->select()->distinct()
              ->from(array('che' => 'beep_evento_check'))
              ->where("che.eve_id = $eve_id AND che.usr_id= $usr_id");   

        $_check =  $db->fetchRow($query);

        return $_check;  

    }

}
