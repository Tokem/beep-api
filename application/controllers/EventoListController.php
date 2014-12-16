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
    }


    public function listEspecialAction(){

        $listEspecial = $this->_evento->listEspecial(2);

        $eventos = array();

        foreach ($listEspecial as $key => $value) {
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



    public function listAction(){
        
        $lista = $this->_evento->listDefault(2);
        $especial = Zend_Paginator::factory($lista);
        $especial->setCurrentPageNumber($this->_getParam('page'));
        $especial->setItemCountPerPage(6);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination.phtml');
        

        foreach ($especial as $key => $value) {
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