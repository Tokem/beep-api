<?php

class Application_Model_Beeps extends Zend_Db_Table
{
    protected $_name = "beep_beeps";
    protected $_primary = "bee_id";


    public function getBeeps($usrId){

    	$db = $this->getDefaultAdapter();
        $query  = $db->select()->distinct()
                  ->from(array('bp'=>'beep_beeps'),array('bee_beeps'))
                  ->join(array('cl'=>'beep_classificacao'),"bp.cla_id_fk = cl.cla_id AND bp.usr_id_fk=$usrId",array('cla_nome'));        

        return $beeps =  $db->fetchRow($query);
    }


}

