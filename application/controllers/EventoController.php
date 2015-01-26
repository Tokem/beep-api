<?php

class EventoController extends Zend_Controller_Action
{
    
    private $_user = null;
    private $_evento = null;
    private $_eventoCheck = null;
    private $_atracao = null;
    private $_ingresso = null;
    private $_estilo = null;
    private $_categoria = null;
	private $_feed = null;
	
    
    public function init()
    {
        $this->_user = new Application_Model_Usuario();
        $this->_evento = new Application_Model_Evento();
        $this->_atracao = new Application_Model_Atracao();
        $this->_ingresso = new Application_Model_Ingresso();
        $this->_feed = new Application_Model_Feed();
     }

    public function indexAction()
    {
        // action body
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $functions = new Tokem_Functions();

        if ($request->isPost()) {
            try {				
                $tokem = $dataRequest["usr_tokem"];

                $usuario = $this->_user->fetchRow("usr_tokem = '$tokem' ");
                $diretorio ="./upload/users/user_id_".  $usuario->usr_id."/events";
                

                $adapter = new Zend_File_Transfer_Adapter_Http(); 

                foreach ($adapter->getFileInfo() as $file => $info) {
                    if ($adapter->isUploaded($file)) {

                        @$name = $adapter->getFileName($file);

                        $fileName = $functions->removeAcentos($info['name']);
                        $newFileName = strtolower(str_replace(' ', '',$fileName));
                        
                        $img_nome = md5(microtime()).'_'.$newFileName;
                        $fname = $diretorio ."/". $img_nome;
                        @$caminho  = ltrim($diretorio, ".");
                        
                        /**
                         *  Let's inject the renaming filter
                         */
                        $adapter->addFilter(new Zend_Filter_File_Rename(array('target' => $fname, 'overwrite' => true)), null, $file);
                        /**
                         * And then we call receive manually
                         */
                        $adapter->receive($file);
                    }
                } 

                $aux = explode('/', $dataRequest['data_festa']);
                $data = $aux[2] . "-".$aux[1]."-".$aux[0];
                
                $evento = array(
                    "eve_nome"=>$dataRequest["nome_festa"],
                    "eve_tag"=>$dataRequest["tag_festa"],
                    "eve_data"=>$data,
                    "eve_hora_inicio"=>$dataRequest["hora_festa"],
                    "eve_hora_termino"=>$dataRequest["hora_festa_fim"],
                    "eve_local"=>$dataRequest["local_festa"],
                    "eve_localizacao"=>$dataRequest["localizacao_festa"],
                    "eve_image"=>substr($fname,1,strlen($fname)),
                    "cat_id_fk"=>$dataRequest["cat_festa"],
                    "usr_id_fk"=>$usuario->usr_id,
                    );


                $idEvent = $this->_evento->insert($evento);
				
				$dataRequest["atracao"] = json_decode($dataRequest["atracao"]);
				$dataRequest["valor"] = json_decode($dataRequest["valor"]);
				$dataRequest["descricao"] = json_decode($dataRequest["descricao"]);
				$dataRequest["estilo"] = json_decode($dataRequest["estilo"]);
				$dataRequest["tipo"] = json_decode($dataRequest["tipo"]);
				
                for ($i =0; $i < count($dataRequest["atracao"]);$i++) {
                 	$estilo = $dataRequest["estilo"][$i];
                 	$atracao = array("atr_nome"=>$dataRequest["atracao"][$i],"eve_id_fk"=>$idEvent,"est_id_fk"=>$estilo);
                 	$this->_atracao->insert($atracao);
             	} 

            	for ($i =0; $i < count($dataRequest["valor"]);$i++) {
                	 $ingresso = array("ing_valor"=>$dataRequest["valor"][$i],"
						 				ing_descricao"=>$dataRequest["descricao"][$i],"eve_id_fk"=>$idEvent);
                	 $this->_ingresso->insert($ingresso);
             	}

            	$allMensages["msg"] = "success";
                $allMensages["data"] = array("state"=>"200",
                			"msg"=>"Evento cadastrado com sucesso!","eve_id"=>"$idEvent");
				echo json_encode($allMensages);    
            	exit;

             }catch (Zend_Db_Exception $e) {
        
            }
         }
 	}

    public function estilosAction()
    {
        $this->estilo = new Application_Model_Estilo();
        $lista = $this->estilo->fetchAll();
        $estilos = array();

        foreach ($lista as $key) {
            $estilos[] = array("id"=> $key['est_id'],"nome"=>$key['est_nome']);
        }
		$allMensages["msg"] = "success";
		$allMensages["data"] =  $estilos;
        echo json_encode($allMensages);
		
        exit;
    }  
	
    public function getCategoriasAction()
    {
        $this->categoria = new Application_Model_Categoria();
        $lista = $this->categoria->fetchAll();
        $categorias = array();

        foreach ($lista as $key) {
            $categorias[] = array("id"=> $key['cat_id'],"nome"=>$key['cat_nome']);
        }
		$allMensages["msg"] = "success";
		$allMensages["data"] =  $categorias;
        echo json_encode($allMensages);
		
        exit;
    }  

    public function editAction()
    {
        // action body
    }

    public function viewAction()
    {
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  
        $eventoId = $dataRequest["evento"];

        if ($request->isPost()) {            
            $evento = $this->_evento->find($eventoId)->current()->toArray();
            $atracoes = $this->_atracao->fetchAll("eve_id_fk='$eventoId'")->toArray();
            $ingresso = $this->_ingresso->fetchAll("eve_id_fk='$eventoId'")->toArray();
            $feed = $this->_feed->fetchAll("eve_id_fk='$eventoId'","fee_data DESC")->toArray();
		
			$allMensages["msg"] = "success";
			$allMensages["data"] = array('evento' => $evento, 'atracao' => $atracoes, 'ingresso' => $ingresso, 'feed' => $feed );
			echo json_encode($allMensages);
			
            exit;
        }
    }

    public function checkAction(){
        $request = $this->getRequest();
        $dataRequest = $request->getPost();  

        if ($request->isPost()) {
            $tokem = $dataRequest["tokem"];
            $usuario = $this->_user->fetchRow("usr_tokem = '$tokem' ");
            $usuarioId = $usuario->usr_id;
            @$idEvento = $dataRequest["evento"];
            
            if(isset($usuarioId)){
                $this->_eventoCheck = new Application_Model_EventoCheck();
                $check = $this->_eventoCheck->checkDischeck($usuarioId,$idEvento);
                @$usrCheck = $check["usr_id"];

                if(isset($check["usr_id"])){
                    $check = $this->_eventoCheck->fetchRow("usr_id = '$usrCheck' ");
                    
                    try {
                        $check->delete();
                        $allMensages["msg"] = "success";
                        echo json_encode($allMensages);
                        exit;    
                    } catch (Zend_Db_Exception $e) {
                        $allMensages["msg"] = "error";
                        $allMensages["data"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                        echo json_encode($allMensages);
                    }
                    
                }else{
                    $check=  array("evc_id"=>"","eve_id"=>$idEvento,"usr_id"=>$usuarioId);
                    
                    try {
                        $this->_eventoCheck->insert($check);    
                        $allMensages["msg"] = "success";
                        echo json_encode($allMensages);
                        exit;
                    } catch (Zend_Db_Exception $e) {
                        $allMensages["msg"] = "error";
                        $allMensages["data"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                        echo json_encode($allMensages);   
                    }
                    
                }
                
            }

        }
    }


    public function deleteAction()
    {
        // action body
    }


    public function favoriteAction()
    {
        // action body
    }


}