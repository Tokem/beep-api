<?php

class Application_Model_Beeps extends Zend_Db_Table
{
    protected $_name = "beep_beeps";
    protected $_primary = "bee_id";


    // public function getBeeps($usrId){

    // 	$db = $this->getDefaultAdapter();
    //     $query  = $db->select()->distinct()
    //               ->from(array('bp'=>'beep_beeps'),array('bee_beeps'))
    //               ->join(array('cla'=>'beep_classificacao'),"bp.usr_id_fk = cla.cla_id AND bp.usr_id_fk=$usrId",array('cla_nome'));        

    //     return $beeps =  $db->fetchRow($query);
    // }



    public function getBase($usrId){

    	$db = $this->getDefaultAdapter();
        $query  = $db->select()->distinct()
                  ->from(array('bu'=>'beep_usuario'),array('usr_usuario','usr_primeiro_nome','usr_ultimo_nome','usr_foto_perfil'))
                  ->join(array('cla'=>'beep_classificacao'),"cla.cla_id = bu.cla_id_fk AND bu.usr_id=$usrId",array('cla_nome'))
                  ->join(array('be'=>'beep_beeps'),"bu.usr_id = be.usr_id_fk",array('bee_beeps'));        

        return $beeps =  $db->fetchRow($query);
    }


}

