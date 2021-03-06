<?php

class LoginController extends Zend_Controller_Action {


    protected $_usuario = null;

    public function init() {
        
        /* Desabilitar o layout */
        $this->_helper->layout->disableLayout();
    }

    protected function _getAuthAdapter() {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $adapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $adapter->setTableName('beep_usuario')
                ->setIdentityColumn('usr_email')
                ->setCredentialColumn('usr_senha')
                ->setCredentialTreatment('MD5(?)');
        return $adapter;
    }

    public function indexAction() {
        
        $request = $this->getRequest();
        $dataRequest = $request->getPost();    

        if($request->isXmlHttpRequest() && isset($dataRequest['email']) && isset($dataRequest['senha']) && !empty($dataRequest['email']) && !empty($dataRequest['senha']) ){
			
            // pega o adaptador de autenticação configurado
            $adapter = $this->_getAuthAdapter();
            
            // põe os dados que serão autenticados
            $adapter->setIdentity($dataRequest['email'])
                    ->setCredential($dataRequest['senha']);

            //realiza a autenticação
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter); // Zend_Auth_Result

            // verifica se deu certo
            if ($result->isValid()) {
				try{
                // se der certo, pega o registro da tabela
                $usuario = $adapter->getResultRowObject();
				
			
                // grava o registro autenticado na sessão
                $auth->getStorage()->write($usuario);

                $auth = Zend_Auth::getInstance();
                $identity = $auth->getIdentity();

                $beep = new Application_Model_Beeps();
                $return = $beep->getBase($identity->usr_id);
                $categoria = $return['cla_nome'];
                $moedas = !$return['bee_beeps'] ? 0 : $return['bee_beeps'] ;

                $allMensages["msg"] = "success";
                $allMensages["data"]=array("state"=>"200",
                    "msg"=>"Login realizado com sucesso",
                    "nome"=>$usuario->usr_primeiro_nome . " ".$usuario->usr_ultimo_nome  ,
                    "username"=>$usuario->usr_usuario,
                    "permissao"=>"$identity->usr_permissao",
                    "tokem"=>"$identity->usr_tokem",
                    "classificacao"=>"$categoria",
                    "beeps"=>"$moedas",
                    "imagem"=>$usuario->usr_foto_perfil);
                                 
                echo json_encode($allMensages);
                exit;
				}catch(Exception $e){
					print_r($e);
					exti();
				}
            } else {
                // se não deu certo, ver qual foi o erro
                $code = $result->getCode();
                if ($code == Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND || $code == Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID) {
                    $allMensages["msg"] = "error";
                    $allMensages["data"] = array("state"=>"401","msg"=>"Login ou senha inválidos");             
                    echo json_encode($allMensages);
                    exit;
                } else {
                    $allMensages["msg"] ="error"; 
                    $allMensages["data"] = array("state"=>"500","msg"=>"Erro ao tentar se conectar! tente novamente mais tarde");             
                    echo json_encode($allMensages);
                    exit;
                }
            }
        }else{
            http_response_code(203);
            exit();
        }
        exit;
    }

    

    public function logoutAction() {
        // Apaga da instância do Zend Auth a identificação no sistema.
        Zend_Auth::getInstance()->clearIdentity();

        // Redireciona para a página inicial do site.
        $this->_redirect('login');
    }

}
