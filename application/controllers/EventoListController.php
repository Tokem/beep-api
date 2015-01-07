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
            $mensagem = array('empty' => 'Não foram encontrados eventos');
            echo json_encode($mensagem);
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

        $lista = $this->_evento->listDefault($userId);

        if(empty($lista)){
            $mensagem = array('empty' => 'Não foram encontrados eventos');
            echo json_encode($mensagem);
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

     public function categoryAction(){

        
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $tokem = $dataRequest["tokem"];

        $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
        $userId = $usuario->usr_id;


        $lista = $this->_evento->listCategory($userId,$categoryId);

        if(empty($lista)){
            $mensagem = array('empty' => 'Não foram encontrados eventos');
            echo json_encode($mensagem);
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

    public function dateAction(){
        
        $request = $this->getRequest();
        $dataRequest = $request->getPost();
    
        $aux = explode('/', $dataRequest['date_ini']);
        $dateIni = $aux[2] . "-".$aux[1]."-".$aux[0];

        $aux = explode('/', $dataRequest['date_end']);
        $dateEnd = $aux[2] . "-".$aux[1]."-".$aux[0];
        
        $tokem = $dataRequest["tokem"];

        $usuario = $this->_user->fetchRow("usr_tokem='$tokem'");
        $userId = $usuario->usr_id;

        $lista = $this->_evento->listDate($userId,$dateIni, $dateEnd );

        if(empty($lista)){
            $mensagem = array('empty' => 'Não foram encontrados eventos');
            echo json_encode($mensagem);
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