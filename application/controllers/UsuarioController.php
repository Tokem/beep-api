    <?php

class UsuarioController extends Zend_Controller_Action {

    protected $_log = null;
    protected $_usuario = null;
    protected $_strip = null;
    protected $_trim = null;

    public function init() {
        $this->_usuario = new Application_Model_Usuario();
        $this->_strip = new Zend_Filter_StripTags();
        $this->_trim = new Zend_Filter_StringTrim();
        $this->_helper->layout->disableLayout();
    }
    
    public function indexAction()
    {
        // action body
    }

    public function createAction()
    {   
        $request = $this->getRequest();    
        $dataRequest = $request->getPost();
        
        if(!$request->isXmlHttpRequest() && !isset($dataRequest['email']) && !isset($dataRequest['senha'])){
            $allMensages["msg"] = "error";
            $allMensages["data"] = array("state"=>"500","msg"=>"Não foi possível efetuar essa operação!");
		   // http_response_code(203);
		   echo json_encode($allMensages);
            exit();
        }else{
            
            
            $validatorFirist = new Tokem_ValidatorUser();
            $valid = $validatorFirist->firist($dataRequest);
            
            if(is_bool($valid)){
                
                
                $usuario = str_replace(' ', '',$this->_trim->filter($this->_strip->filter($dataRequest['usuario'])));
                $email = $this->_trim->filter($this->_strip->filter($dataRequest['email']));
                $senha = $this->_trim->filter($this->_strip->filter(md5($dataRequest['senha'])));
                $tokem = $this->_trim->filter($this->_strip->filter($better_token = md5(uniqid(rand(), true))));
                
                $user = array("usr_usuario"=>"$usuario","usr_email"=>"$email","usr_senha"=>"$senha","usr_tokem"=>"$tokem");
                try {                   
                    $return = $this->_usuario->insert($user);
                    $beep = new Application_Model_Beeps();

                    $beeps = array("usr_id_fk"=>"$return");

                    $idBeep = $beep->insert($beeps);
                    $returnBeep = $beep->find($idBeep)->current();


                    $allMensages["msg"] = "success";
                    $allMensages["data"] = array("state"=>"200",
                        "tokem"=>"$tokem",
                        "username"=>"$usuario",
                        "email"=>"$email",
                        "permissao"=>"usuario",
                        "beeps"=>"$returnBeep->bee_beeps",
                        "msg"=>"Cadastro realizado com sucesso");

                    $folder = new Tokem_ManipuleFolder();
                    $folder->createFolderUser($return);
                    $allMensages["msg"] = "success";
                    echo json_encode($allMensages);
                    exit;
                    
                } catch (Zend_Db_Exception $e) {

                    echo $e->getMenssage();
                    exit;
                    
                    $allMensages["msg"] = "error";
                    $allMensages["data"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                    echo json_encode($allMensages);
                    exit;
                }
                
            }else{
                echo $valid;
                exit();
            }
            
        }
        
    }


    public function createFacebookAction()
    {   
        $request = $this->getRequest();    
        $dataRequest = $request->getPost();
        
        if(!$request->isXmlHttpRequest()){
            http_response_code(203);
            exit();
        }else{
            
            
            $validatorFirist = new Tokem_ValidatorUser();

                $faceId =$dataRequest['face_id'];
                $username = $dataRequest['username'];
                $senha = md5($dataRequest['senha']);
                $email = $dataRequest['email'];
                $firist = $dataRequest['firistName'];
                $lastname = $dataRequest['lastName'];
                $gender = $dataRequest['gender'];
                $imagePerfil = $dataRequest['imagePerfil'];
                $tokem = $better_token = md5( uniqid(rand(), true) );
                
                $validatorFirist = new Tokem_ValidatorUser();
                $validFace = $validatorFirist->verifyFacebookId($faceId);

                if($validFace){
                    $allMensages["msg"] = "error";
                    $allMensages["data"] = array("state"=>"200","msg"=>"Cadastro já existe!");
                    echo json_encode($allMensages);
                    exit;
                }


                $userFacebook = array(
                    "usr_id_facebook"=>"$faceId",
                    "usr_usuario"=>"$username",
                    "usr_email"=>"$email",
                    "usr_senha"=>"$senha",
                    "usr_tokem"=>"$tokem",
                    "usr_primeiro_nome"=>"$firist",
                    "usr_ultimo_nome"=>"$lastname",
                    "usr_genero"=>"$gender",
                    "usr_foto_perfil"=>"$imagePerfil"
                );
                
                try {
                    
                    $return = $this->_usuario->insert($userFacebook);

                    $beep = new Application_Model_Beeps();
                    $beeps = array("usr_id_fk"=>"$return");
                    $idBeep = $beep->insert($beeps);
                    $returnBeep = $beep->find($idBeep)->current();

                    $allMensages["msg"] = "success";
                    $allMensages["data"] = array("state"=>"200",
                        "tokem"=>"$tokem",
                        "usuario"=>"$username",
                        "email"=>"$email",
                        "permissao"=>"usuario",
                        "beeps"=>"$returnBeep->bee_beeps",
                        "msg"=>"Cadastro realizado com sucesso");

                    $folder = new Tokem_ManipuleFolder();
                    $folder->createFolderUser($return);

                    echo json_encode($allMensages);
                    exit;
                    
                } catch (Zend_Db_Exception $e) {
                
                    echo $e->getMessage();
                    exit;
                    
                    $allMensages["msg"] = "error";
                    $allMensages["data"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                    echo json_encode($allMensages);
                    exit;
                }
                
            
        }
        
    }


    public function updatedadosAction(){
     
         $request = $this->getRequest();    
         $dataRequest = $request->getPost();
         $functions = new Tokem_Functions();
        
        if(!isset($dataRequest['tokem'])){
          //  http_response_code(203);
          $allMensages["msg"] = "error";
          $allMensages["data"] = array("state"=>"500","msg"=>"Não foi possível efetuar essa operação!");
	   // http_response_code(203);
	   echo json_encode($allMensages);
            exit();
        }else{

            $tokem = $dataRequest["tokem"];
            $usuario =  $this->_usuario->fetchRow("usr_tokem = '$tokem' ");
			
			if($dataRequest["primeiro_nome"])
            	$usuario->usr_primeiro_nome = $dataRequest["primeiro_nome"];
			
			if($dataRequest["ultimo_nome"])
           		$usuario->usr_ultimo_nome = $dataRequest["ultimo_nome"];

            if($dataRequest["telefone"])
                $usuario->usr_telefone = $dataRequest["telefone"];
            
            $validator = new Tokem_ValidatorUser();
            //$return = $validator->verifyEmail($dataRequest["email"]);

            if(!$return){
				if($dataRequest["email"])
             	   $usuario->usr_email = $dataRequest["email"];      
           
                
                //Obtem a foto do usuário
                $diretorio ="./upload/users/user_id_".  $usuario->usr_id."/profile";
                $adapter = new Zend_File_Transfer_Adapter_Http(); 

                foreach ($adapter->getFileInfo() as $file => $info) {
                    if ($adapter->isUploaded($file)) {

                        @$name = $adapter->getFileName($file);

                        $fileName = $functions->removeAcentos($info['name']);
                        $newFileName = strtolower(str_replace(' ', '',$fileName));
                        
                        $img_nome = md5(microtime()).'_'.$newFileName;
                        $fname = $diretorio ."/". $img_nome;
                        @$caminho  = ltrim($diretorio,"");
                        
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

            $usuario->usr_foto_perfil = substr($fname,1,strlen($fname));
            $usuario->save();

            $allMensages["msg"] = "success";
            $allMensages["data"]=array("msg"=>"Dados atualizados com sucesso!");

            echo json_encode($allMensages);
            exit;

            }else{
                $allMensages["msg"] = "error";
                $allMensages["data"] = array("msg"=>"Email Esta sendo utilizado!");   
                echo json_encode($allMensages);
                exit;
            }

            exit;


        }

    }


    public function createFacebook(){
        
    }    
    
    public function viewAction()
    {
        // action body
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

    public function uploadAction()
    {
        // action body
    }

}
