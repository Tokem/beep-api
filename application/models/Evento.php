<?php

class Application_Model_Evento extends Zend_Db_Table
{
    protected $_name = "beep_evento";
    protected $_primary = "eve_id";




    public function listEspecial(){

        $db = $this->getDefaultAdapter();
        
        $lista = $this->select();
        $lista->setIntegrityCheck(false);
        $lista->from(array('be' => 'beep_evento'))
                ->join(array('bec' => 'beep_evento_ckeck'), 'be.eve_id = bec.eve_id')
                ->where("
                 be.eve_ativo=1
                 AND be.eve_especial = 1 
                 AND be.eve_data_especial_prazo = CURDATE()   
                 AND dt.tar_finalizado=1")
                ->order(array('dt.tar_codigo DESC'))    
               ->limit(3);

        echo $lista;
        exit;       

        return $cliente =  $db->fetchAll($lista);

    }    
        

}

   

