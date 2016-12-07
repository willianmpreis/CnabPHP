<?php
namespace CnabPHP\Resources\Generico\Remessa\Cnab400;
use CnabPHP\RegistroRemessaAbstract;
use CnabPHP\RemessaAbstract;
use Exception;

class Generico0 extends RegistroRemessaAbstract
{
    protected $counter = 1;
    public function get_counter(){
        $this->counter++;
        return $this->counter;
    }
    
    public function inserirDetalhe($data)
    {
        $class = 'CnabPHP\Resources\\'.RemessaAbstract::$banco.'\Remessa\\'.RemessaAbstract::$leiaute.'\Registro1';
        $this->children[] = new $class($data);
    }
    
    protected function set_data_gravacao($value)
    {
        $this->data['data_gravacao'] = date('Y-m-d');
    }


}

?>
