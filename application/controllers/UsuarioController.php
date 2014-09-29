    <?php

class UsuarioController extends Zend_Controller_Action {

    protected $_log = null;
    protected $_usuario = null;
    protected $_strip = null;
    protected $_trim = null;

    public function init() {
        $this->_usuario = new Application_Model_Usuario();
        $this->_helper->layout->disableLayout();
        $this->_strip = new Zend_Filter_StripTags();
        $this->_trim = new Zend_Filter_StringTrim();
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
            http_response_code(203);
            exit();
        }else{
            
            
            $validatorFirist = new Tokem_ValidatorUser();
            $valid = $validatorFirist->firist($dataRequest);
            
            if(is_bool($valid)){
                
                
                $usuario = $this->_trim->filter($this->_strip->filter($dataRequest['usuario']));
                $email = $this->_trim->filter($this->_strip->filter($dataRequest['email']));
                $senha = $this->_trim->filter($this->_strip->filter(md5($dataRequest['senha'])));
                $tokem = $this->_trim->filter($this->_strip->filter($better_token = md5(uniqid(rand(), true))));
                
                $user = array("usr_usuario"=>"$usuario","usr_email"=>"$email","usr_senha"=>"$senha","usr_tokem"=>"$tokem");
                try {
                    
                    $return = $this->_usuario->insert($user);
                    $allMensages["msg-sucess"]["create"] = array("state"=>"200",
                        "tokem"=>"$tokem",
                        "usuario"=>"$usuario",
                        "email"=>"$email",
                        "msg"=>"Cadastro realizado com sucesso");                    
                    echo json_encode($allMensages);
                    exit;
                    
                } catch (Exception $exc) {
                    
                    $allMensages["msg-erro"]["create"] = array("state"=>"500","msg"=>"Estamos enfrentando problemas... tente novamente mais tarde!");
                    echo json_encode($allMensages);
                    exit;
                }

                var_dump($return);
                exit;
                
            }else{
                echo $valid;
                exit();
            }
            
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
