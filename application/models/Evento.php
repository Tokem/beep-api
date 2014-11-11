<?php

class Application_Model_Evento extends Zend_Db_Table
{
    protected $_name = "beep_evento";
    protected $_primary = "eve_id";




    public function listEspecial(){

        $db = $this->getDefaultAdapter();
        
        $lista = $this->select()->distinct();
        $lista->setIntegrityCheck(false);
        $lista->from(array('be' => 'beep_evento'))
                ->where("NOW() BETWEEN  eve_data_especial_inicio AND eve_data_especial_fim")
               ->limit(3);

        $listaEventos =  $db->fetchAll($lista);


        $select = $this->select()
             ->from('beep_check', array())
             ->columns(array('TotalRecords' => new Zend_Db_Expr('COUNT(*)')));

        foreach ($listaEventos as $key => $value) {

            //$listaEventos[$key]['count'] = 
        }

        exit;

    }    
        

}

   

