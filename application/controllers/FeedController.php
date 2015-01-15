<?php

class FeedController extends Zend_Controller_Action
{
    
    private $_user = null;
    private $_evento = null;
    private $_feed = null;
    
    
    public function init()
    {
        $this->_user = new Application_Model_Usuario();
        $this->_evento = new Application_Model_Evento();
        $this->_feed = new Application_Model_Feed();
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $eventoId = $dataRequest["evento"];

        $allFeeds = $this->_feed->fetchAll("eve_id_fk='$eventoId' ","fee_data DESC")->toArray();

        if ($request->isPost()) {
        	$allMensages["msg"] = "success";
            $allMensages["data"] = array("feeds"=>$allFeeds);
			echo json_encode($allMensages);
			
            exit;
        }
            exit;
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  

        $eventoId = $dataRequest["evento"];
        $feed = $dataRequest["titulo"];
        $texto = $dataRequest["texto"];

        if ($request->isPost()) {
            $feed = array("fee_texto"=> $texto, "fee_titulo"=> $feed ,"eve_id_fk"=>"$eventoId");

            try {
				$this->_feed->insert($feed);
				$allMensages["msg"] = "success";
                $allMensages["data"] = array("state"=>"200","msg"=>"feed");
                echo json_encode($allMensages);
                exit;
			} catch (Zend_Db_Exception $e) {
            	$allMensages["msg"] = "error";
                $allMensages["data"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                echo json_encode($allMensages);   
			}
        }

        exit;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $feedId = $dataRequest["fee_id"];

        if ($request->isPost()) {
            try {
				$this->_feed->delete("fee_id = '$feedId'");
				$allMensages["msg"] = "success";
                $allMensages["data"] = array("state"=>"200","msg"=>"feed ok");
                echo json_encode($allMensages);
                exit;
            } catch (Zend_Db_Exception $e) {
            	$allMensages["msg"] = "error";
                $allMensages["data"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                echo json_encode($allMensages);   
            }

		}
        exit;
    }

}    