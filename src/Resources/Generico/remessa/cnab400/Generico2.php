<?php
namespace CnabPHP\Resources\Generico\remessa\cnab400;
use CnabPHP\RegistroRemessaAbstract;
use CnabPHP\RemessaAbstract;
use Exception;

class Generico2 extends RegistroRemessaAbstract
{
    protected function set_numero_registro($value)
    {
        $lote  = RemessaAbstract::getLot(0);
        $this->data['numero_registro'] = $lote->get_counter();
    }


}

?>
