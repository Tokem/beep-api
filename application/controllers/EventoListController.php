<?php

class EventoListController extends Zend_Controller_Action
{
    
    private $_user = null;
    private $_evento = null;
    private $_atracao = null;
    private $_ingresso = null;
    
    public function init() {
        $this->_user = new Application_Model_Usuario();
        $this->_evento = new Application_Model_Evento();
    }
 
     public function indexAction(){
		exit();
	}

    public function especialAction(){
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        
        $tokem = $dataRequest["tokem"];

        $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
        $userId = $usuario->usr_id;

        $listEspecial = $this->_evento->listEspecial($userId);
        $eventos = array();

        if(empty($listEspecial)){
        	$allMensages["msg"] = "empty";
            $allMensages["data"] = array("msg"=>"Não foram encontrados nenhum evento!");
			echo json_encode($allMensages);
            exit;
        }
        

        foreach ($listEspecial as $key => $value) {
            $eventos[] = array(
                "id"=>$value["eve_id"],
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=>$value["check"],
				"type"=>"especial"
            );
        }
		
        echo json_encode($eventos);
        exit;
    }

    public function listAction(){        
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $tokem = $dataRequest["tokem"];
        $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
        $userId = $usuario->usr_id;
		
        $listEspecial = $this->_evento->listEspecial($userId);
        $lista = $this->_evento->listDefault($userId);
		
        if(empty($lista) && empty($listEspecial)){
        	$allMensages["msg"] = "empty";
            $allMensages["data"] = array("msg"=>"Não foram encontrados nenhum evento!");
			echo json_encode($allMensages);
            exit;
        }

        $default = Zend_Paginator::factory($lista);
        $default->setCurrentPageNumber($this->_getParam('page'));
        $default->setItemCountPerPage(9);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        
		$eventos = array();
        foreach ($default as $key => $value) {
            $eventos[] = array(
                "id"=>$value["eve_id"],
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=>$value["check"]
            );
        }
		
		$eventosEspecial = array();
        foreach ($listEspecial as $key => $value) {
            $eventosEspecial[] = array(
                "id"=>$value["eve_id"],
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=>$value["check"]
            );
        }
		
    	$allMensages["msg"] = "success";
        $allMensages["data"] = array("normal" => $eventos, "especial" => $eventosEspecial);
        echo json_encode($allMensages);
        exit;
    }

     public function categoryAction(){        
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $tokem = $dataRequest["tokem"];
		$categoryId = $dataRequest['value'];

        $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
        $userId = $usuario->usr_id;


        $lista = $this->_evento->listCategory($userId,$categoryId);

        if(empty($lista)){
        	$allMensages["msg"] = "empty";
            $allMensages["data"] = array("msg"=>"Não foram encontrados nenhum evento!");
			echo json_encode($allMensages);
            exit;
        }

        $default = Zend_Paginator::factory($lista);
        $default->setCurrentPageNumber($this->_getParam('page'));
        $default->setItemCountPerPage(9);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        
        foreach ($default as $key => $value) {
            $eventos[] = array(
                "id"=>$value["eve_id"],
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=>$value["check"]
            );
        }
		
		$allMensages["msg"] = "success";
		$allMensages["data"] = $eventos;
        echo json_encode($allMensages);
        exit;
	}

    public function dateAction(){        
        $request = $this->getRequest();
        $dataRequest = $request->getPost();
		
		$dateValue = json_decode($dataRequest['value']);

		foreach($dateValue as $data){
	        $aux = explode('/', $data);
			if($date)
				$date .= ',';
	        $date .= "'".$aux[2] . "-".$aux[1]."-".$aux[0]."'";
		}
    
        $tokem = $dataRequest["tokem"];

        $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
        $userId = $usuario->usr_id;

        $lista = $this->_evento->listDate($userId,$date );

        if(empty($lista)){
        	$allMensages["msg"] = "empty";
            $allMensages["data"] = array("msg"=>"Não foram encontrados nenhum evento!");
			echo json_encode($allMensages);
            exit;
        }

        $default = Zend_Paginator::factory($lista);
        $default->setCurrentPageNumber($this->_getParam('page'));
        $default->setItemCountPerPage(9);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        

        foreach ($default as $key => $value) {
            $eventos[] = array(
                "id"=>$value["eve_id"],
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=>$value["check"]
            );
        }
		
		$allMensages["msg"] = "success";
		$allMensages["data"] = $eventos;
        echo json_encode($allMensages);
        exit;
    }

    public function nowAction(){        
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $tokem = $dataRequest["tokem"];

        $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
        $userId = $usuario->usr_id;

        $lista = $this->_evento->listCategory($userId,$categoryId);

        if(empty($lista)){
        	$allMensages["msg"] = "empty";
            $allMensages["data"] = array("msg"=>"Não foram encontrados nenhum evento!");
			echo json_encode($allMensages);
            exit;
        }

        $default = Zend_Paginator::factory($lista);
        $default->setCurrentPageNumber($this->_getParam('page'));
        $default->setItemCountPerPage(9);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        
        foreach ($default as $key => $value) {
            $eventos[] = array(
                "id"=>$value["eve_id"],
                "titulo"=>$value["eve_nome"],
                "imagem"=>$value["eve_image"],
                "count"=>$value["count"],"check"=>$value["check"]
            );
        }

        echo json_encode($eventos);
        exit;


    }


}    