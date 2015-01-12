<?php

class ModerateController extends Zend_Controller_Action
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
        
	}


    public function moderateAction()
    {
       

       require_once APPLICATION_PATH . '/../library/phpqrcode/qrlib.php';

       $request = $this->getRequest();
       $dataRequest = $request->getPost();  
       //$eventoId = $dataRequest["eve_id"];
       $eventoId = 1;

       //$userId = $dataRequest["usr_id"];
       $userId = 1;

       //$ativo = $dataRequest["eve_ativo"];
       $ativo = 1;

       $evento = $this->_evento->find($eventoId)->current();
       $evento->eve_ativo = $ativo;
       $localizacao = $evento->eve_local;
       $usuario = $this->_user->find($userId)->current();

       try {
                            $evento->save();
                            $qrcode_name = md5(microtime()).'_qrcode.png';
                            $folder ="./upload/users/user_id_".  $usuario->usr_id."/qrcode/$qrcode_name";
                            $qrcode = "[{\"eve_local\":\"$localizacao\",\"eve_id\":\"$eventoId\"}]";
                            
                            QRcode::png("$qrcode", "$folder", 'L', 20, 2);

                            $allMensages["msg"] = "success";
                            $allMensages["data"] = array("state"=>"200","msg"=>"qrcode ok");
                            echo json_encode($allMensages);
                            exit;
                        } catch (Zend_Db_Exception $e) {
                            $allMensages["msg"] = "error";
                            $allMensages["data"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                            echo json_encode($allMensages);
                            exit;   
                        }

            }



    }