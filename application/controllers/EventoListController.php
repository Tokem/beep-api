<?php

class EventoListController extends Zend_Controller_Action
{
    
    private $_user = null;
    private $_evento = null;
    private $_atracao = null;
    private $_ingresso = null;
    
    public function init()
    {
        $this->_user = new Application_Model_Usuario();
        $this->_evento = new Application_Model_Evento();
        
    }


     public function indexAction()
    {
        // action body
    	print_r('aqui');
		exit();
	}


    public function especialAction(){

        $listEspecial = $this->_evento->listEspecial(7);
        $eventos = array();

        foreach ($listEspecial as $key => $value) {
            $eventos[] = array(
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=>$value["check"],
				"type"=>"especial"
            );
        }
		
        $listEspecial = $this->_evento->listEvento(7);
        foreach ($listEspecial as $key => $value) {
            $eventos[] = array(
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=> $value["check"],
				"type"=>"normal"
            );
        }

        echo json_encode($eventos);
        exit;

    }    


}    