<?php

class Application_Model_Evento extends Zend_Db_Table
{
    protected $_name = "beep_evento";
    protected $_primary = "eve_id";

}

public function getEspecial(){
        $db = $this->getDefaultAdapter();
        
        $lista = $this->select();
        $lista->setIntegrityCheck(false);
        $lista->from(array('be' => 'beep_eventos'))
                ->join(array('be' => 'beep_evento_check'), 'dc.cli_codigo = dt.duca_clientes_cli_codigo')
                ->join(array('dtc' => 'duca_tarefas_clientes'), 'dt.tar_codigo = dtc.duca_atividades_tar_codigo_fk')
                ->where(" 
                 dtc.duca_tar_cli_status!=2
                 AND dtc.duca_tar_cli_status=0
                 AND dt.tar_finalizado=1")
                ->order(array('dt.tar_codigo DESC'))
                ->limit(100);

        // echo $lista;
        // exit;        

        return $cliente =  $db->fetchAll($lista);
        
    }

