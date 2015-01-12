<?php

class BeepController extends Zend_Controller_Action
{
    
    private $_user = null;
    private $_evento = null;
    define('MIN_DISTANCE', '50');
    
    public function init()
    {
        $this->_user = new Application_Model_Usuario();
        $this->_evento = new Application_Model_Evento();
        
    }

    public function beepAction(){


    	// $request = $this->getRequest();
     //    $dataRequest = $request->getPost();  
     //    $tokem = $dataRequest["tokem"];
     //    $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
     //    $userId = $usuario->usr_id;
     //    $eventoId = $dataRequest["eve_id"];
     //    $coordenadas = $dataRequest["eve_local"];


        $origins	= "-7.1736113,-34.8419801";
		$destinations	= "-7.17362,-34.841649";
		$mode	= "CAR";
		$language	= "PT";
		$sensor	= "false";

		$google = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$origins&destinations=$destinations&mode=walking&language=PT&sensor=false",0,null,null);

		$response = json_decode($google);

		$distance = $response->rows[0]->elements[0]->distance->value;
		
		if($distance < MIN_DISTANCE){

		}	

        
        $evento = $this->_evento->find($eventoId)->current();
        $evento->eve_ativo = $ativo;



    }

}    
