<?php

class EventoController extends Zend_Controller_Action
{
    
    private $_user = null;
    private $_evento = null;
    private $_atracao = null;
    private $_ingresso = null;
    
    public function init()
    {
        $this->_user = new Application_Model_Usuario();
        $this->_evento = new Application_Model_Evento();
        $this->_atracao = new Application_Model_Atracao();
        $this->_ingresso = new Application_Model_Ingresso();
        
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
                "eve_local"=>$dataRequest["local_festa"],
                "eve_localizacao"=>$dataRequest["localizacao_festa"],
                "eve_image"=>$fname,
                "cat_id_fk"=>$dataRequest["cat_festa"],
             );
            
            
           $idEvent = $this->_evento->insert($evento);
             
           for ($i =0; $i < count($dataRequest["atracao"]);$i++) {
               $estilo = $dataRequest["estilo"][$i];
                   $atracao = array("atr_nome"=>$dataRequest["atracao"][$i],"eve_id_fk"=>$idEvent,"est_id_fk"=>$estilo);
                   $this->_atracao->insert($atracao);
           } 
           
           for ($i =0; $i < count($dataRequest["valor"]);$i++) {
               $ingresso = array("ing_valor"=>$dataRequest["valor"][$i],"ing_descricao"=>$dataRequest["descricao"][$i],"eve_id_fk"=>$idEvent);
               $this->_ingresso->insert($ingresso);
           }
           
           
            
                exit;
            
            
        }


        exit;
        
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function moderateAction()
    {
        // action body
    }

    public function listAction()
    {
        // action body
    }

    public function favoriteAction()
    {
        // action body
    }

    public function uploadAction()
    {
        // action body
    }


}















