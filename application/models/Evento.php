<?php

class Application_Model_Evento extends Zend_Db_Table
{
    protected $_name = "beep_evento";
    protected $_primary = "eve_id";

<<<<<<< HEAD

=======
>>>>>>> FETCH_HEAD
    public function countCheck($eve_id){

        $db = $this->getDefaultAdapter();
        
        $count = $this->select()->distinct();
        $count->setIntegrityCheck(false);
        $count->from(array('bec' => 'beep_evento_check'),array('total'=>'COUNT(eve_id)'))
                ->where("bec.eve_id = $eve_id");   

        return  $total =  $db->fetchRow($count);
    }

    public function verifyCheck($id_user,$eve_id){
        $db = $this->getDefaultAdapter();
        
        $query = $this->select()->distinct();
        $query->setIntegrityCheck(false);
        $query->from(array('bec' => 'beep_evento_check'),array('eve_id'))
                ->where("bec.usr_id = $id_user AND bec.eve_id = $eve_id ");
                
<<<<<<< HEAD
        return  $return =  $db->fetchRow($query);

=======
        return  $return =  $db->fetchAll($query);
>>>>>>> FETCH_HEAD
    }

    public function listEspecial($usr_id){
        $db = $this->getDefaultAdapter();
        
        $lista = $this->select()->distinct();
        $lista->setIntegrityCheck(false);
        $lista->from(array('be' => 'beep_evento'),array('eve_id','eve_nome','eve_image'))
                ->where("NOW() BETWEEN  eve_data_especial_inicio AND eve_data_especial_fim AND be.eve_ativo=1")
                ->limit(3);

        $listaEventos =  $db->fetchAll($lista);

		foreach ($listaEventos as $key => $value) {
         	$total["total"] = $this->countCheck($value["eve_id"]);
         	$check = $this->verifyCheck($usr_id,$value["eve_id"]);

        	 if(isset($check[$key]["eve_id"])){
           	  	$listaEventos[$key]['check'] = "1";
         	}else{
           	 	$listaEventos[$key]['check'] = "0";
         	}

<<<<<<< HEAD

         if($check){
            $listaEventos[$key]['check'] = "1";
         }else{
            $listaEventos[$key]['check'] = "0";
         }
=======
         	$listaEventos[$key]['count'] = $total["total"]["total"];
>>>>>>> FETCH_HEAD

        }            
        return $listaEventos;
    }
	
    public function listEvento($usr_id){
        $db = $this->getDefaultAdapter();
        
        $lista = $this->select()->distinct();
        $lista->setIntegrityCheck(false);
        $lista->from(array('be' => 'beep_evento'),array('eve_id','eve_nome','eve_image'))
                ->where("(NOW() BETWEEN  eve_data_especial_inicio AND eve_data_especial_fim) AND eve_especial IS NULL")
                ->limit(9);

        $listaEventos =  $db->fetchAll($lista);

		foreach ($listaEventos as $key => $value) {
         	$total["total"] = $this->countCheck($value["eve_id"]);
         	$check = $this->verifyCheck($usr_id,$value["eve_id"]);

        	 if(isset($check[$key]["eve_id"])){
           	  	$listaEventos[$key]['check'] = "1";
         	}else{
           	 	$listaEventos[$key]['check'] = "0";
         	}

<<<<<<< HEAD
    } 


    public function listDefault($usr_id){

        $db = $this->getDefaultAdapter();
=======
         	$listaEventos[$key]['count'] = $total["total"]["total"];

        }            
        return $listaEventos;
    }  
>>>>>>> FETCH_HEAD
        
        $lista = $this->select()->distinct();
        $lista->setIntegrityCheck(false);
        $lista->from(array('be' => 'beep_evento'),array('eve_id','eve_nome','eve_image'))
                ->where("NOW() <= eve_data AND be.eve_ativo=1")
                ->limit(6);


        $listaEventos =  $db->fetchAll($lista);


        foreach ($listaEventos as $key => $value) {
         $total["total"] = $this->countCheck($value["eve_id"]);
         $check = $this->verifyCheck($usr_id,$value["eve_id"]);


         if($check){
            $listaEventos[$key]['check'] = "1";
         }else{
            $listaEventos[$key]['check'] = "0";
         }

         $listaEventos[$key]['count'] = $total["total"]["total"];

        }

            
        return $listaEventos;

    }    
        

}
