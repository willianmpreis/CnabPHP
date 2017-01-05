<?php
/*
 * CnabPHP - Geração de arquivos de Remessa e retorno em PHP
 *
 * LICENSE: The MIT License (MIT)
 *
 * Copyright (C) 2013 Ciatec.net
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify,
 * merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies
 * or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace CnabPHP\Resources\CaixaEconomicaFederal\Remessa\Cnab400;

use CnabPHP\Resources\Generico\Remessa\Cnab400\Generico1;
use CnabPHP\RemessaAbstract;

class Registro1 extends Generico1
{
    protected $meta = [
        'tipo_registro' => [
            'tamanho' => 1,
            'default' => '1',
            'tipo' => 'int',
            'required' => true
        ],
        'tipo_inscricao_empresa' => [
            'tamanho' => 2,
            'default' => '',
            'tipo' => 'int',
            'required' => true
        ],
        'numero_inscricao_empresa' => [
            'tamanho' => 14,
            'default' => '',
            'tipo' => 'int',
            'required' => true
        ],
        'codigo_empresa' => [
            'tamanho' => 16,
            'default' => '',
            'tipo' => 'int',
            'required' => true
        ],
        'filler0' => [
            'tamanho' => 2,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => true
        ],
        'taxa_permanencia' => [
            'tamanho' => 2,
            'precision' => 4,
            'default' => '',
            'tipo' => 'decimal',
            'required' => true
        ],
        'seu_numero' => [
            'tamanho' => 25,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => true,
        ],
        'nosso_numero' => [
            'tamanho' => 11,
            'default' => '',
            'tipo' => 'int',
            'required' => true
        ],
        'filler1' => [
            'tamanho' => 3,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => true
        ],
        'mensagem_bloqueto' => [
            'tamanho' => 30,
            'default' => '',
            'tipo' => 'alfa',
            'required' => true
        ],
        'cod_carteira' => [
            'tamanho' => 2,
            'default' => '1',
            'tipo' => 'int',
            'required' => true
        ],
        'codigo_movimento' => [ // codigo da ocorrencia no manual da caixa
            'tamanho' => 2,
            'default' => '01', // entrada de titulo
            'tipo' => 'int',
            'required' => true
        ],
        'numero_documento' => [
            'tamanho' => 10,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => true
        ],
        'data_vencimento' => [            //20.3
            'tamanho' => 6,
            'default' => '',
            'tipo' => 'date',
            'required' => true
        ],
        'valor' => [                 //21.3P
            'tamanho' => 13,
            'default' => '',
            'tipo' => 'decimal',
            'precision' => 2,
            'required' => true
        ],
        'codigo_banco' => [
            'tamanho' => 3,
            'default' => '104',
            'tipo' => 'int',
            'required' => true
        ],
        'agencia_cobradora' => [    //22.3P
            'tamanho' => 5,
            'default' => '0',
            'tipo' => 'int',
            'required' => true
        ],
        'especie_titulo' => [    //24.3P
            'tamanho' => 2,
            'default' => '1',
            'tipo' => 'int',
            'required' => true
        ],
        'aceite' => [            //25.3P
            'tamanho' => 1,
            'default' => 'A',
            'tipo' => 'alfa',
            'required' => true
        ],
        'data_emissao' => [            //26.3P
            'tamanho' => 6,
            'default' => '',
            'tipo' => 'date',
            'required' => true
        ],
        'cod_instrucao1' => [    //24.3P
            'tamanho' => 2,
            'default' => '0',
            'tipo' => 'int',
            'required' => true
        ],
        'cod_instrucao2' => [
            'tamanho' => 2,
            'default' => '0',
            'tipo' => 'int',
            'required' => true
        ],
        'vlr_juros' => [
            'tamanho' => 13,
            'default' => '0',
            'tipo' => 'decimal',
            'precision' => 2,
            'required' => true
        ],
        'data_desconto' => [            //31.3P
            'tamanho' => 6,
            'default' => '0',
            'tipo' => 'date',
            'required' => true
        ],
        'vlr_desconto' => [            //32.3P
            'tamanho' => 13,
            'default' => '0',
            'tipo' => 'decimal',
            'precision' => 2,
            'required' => true
        ],
        'vlr_IOF' => [            //33.3P
            'tamanho' => 13,
            'default' => '0',
            'tipo' => 'decimal',
            'precision' => 2,
            'required' => true
        ],
        'vlr_abatimento' => [            //34.3P
            'tamanho' => 13,
            'default' => '0',
            'tipo' => 'decimal',
            'precision' => 2,
            'required' => true
        ],
        'tipo_inscricao' => [
            'tamanho' => 2,
            'default' => '',
            'tipo' => 'int',
            'required' => true
        ],
        'numero_inscricao' => [
            'tamanho' => 14,
            'default' => '',
            'tipo' => 'int',
            'required' => true
        ],
        'nome_pagador' => [
            'tamanho' => 40,
            'default' => '',
            'tipo' => 'alfa',
            'required' => true
        ],
        'endereco_pagador' => [
            'tamanho' => 40,
            'default' => '',
            'tipo' => 'alfa',
            'required' => true
        ],
        'bairro_pagador' => [
            'tamanho' => 12,
            'default' => '',
            'tipo' => 'alfa',
            'required' => true
        ],
        'cep_pagador' => [
            'tamanho' => 8,
            'default' => '',
            'tipo' => 'int',
            'required' => true
        ],
        'cidade_pagador' => [
            'tamanho' => 15,
            'default' => '',
            'tipo' => 'alfa',
            'required' => true
        ],
        'uf_pagador' => [
            'tamanho' => 2,
            'default' => '',
            'tipo' => 'alfa',
            'required' => true
        ],
        'data_multa' => [
            'tamanho' => 6,
            'default' => '0',
            'tipo' => 'date',
            'required' => true
        ],
        'vlr_multa' => [
            'tamanho' => 10,
            'default' => '0',
            'tipo' => 'decimal',
            'precision' => 2,
            'required' => true
        ],
        'nome_avalista_mensagem' => [
            'tamanho' => 22,
            'default' => ' ',
            'tipo' => 'alfa',
            'required' => true
        ],
        'cod_instrucao3' => [
            'tamanho' => 2,
            'default' => '0',
            'tipo' => 'int',
            'required' => true
        ],
        'prazo_protesto' => [
            'tamanho' => 2,
            'default' => '0',
            'tipo' => 'alfa',
            'required' => true
        ],
        'codigo_moeda' => [
            'tamanho' => 1,
            'default' => '9',
            'tipo' => 'int',
            'required' => true
        ],
        'numero_registro' => [
            'tamanho' => 6,
            'default' => '0',
            'tipo' => 'int',
            'required' => true
        ]
    ];


    protected function set_nosso_numero_dv($value)
    {
        $result = self::mod11($this->data['nosso_numero']);
        $this->data['nosso_numero_dv'] = $result['digito'];
    }

    /**
     * Calcula e retorna o dÃ­gito verificador usando o algoritmo Modulo 11
     *
     * @param string $num
     * @param int $base
     * @return array Retorna um array com as chaves 'digito' e 'resto'
     */
    protected static function mod11($num)
    {
        $codigo_beneficiario = RemessaAbstract::$dados['codigo_beneficiario'] . RemessaAbstract::$dados['codigo_beneficiario_dv']; // NÃºmero do contrato: Ã o mesmo nÃºmero da conta
        $agencia = RemessaAbstract::$dados['agencia']; // NÃºmero do contrato: Ã o mesmo nÃºmero da conta

        $NossoNumero = str_pad($num, 7, 0, STR_PAD_LEFT); // AtÃ© 7 dÃ­gitos, nÃºmero sequencial iniciado em 1 (Ex.: 1, 2...)
        $qtde_nosso_numero = strlen($NossoNumero);
        $sequencia = str_pad($agencia, 4, STR_PAD_LEFT) . str_pad($codigo_beneficiario, 10, 0, STR_PAD_LEFT) . str_pad($NossoNumero, 7, 0, STR_PAD_LEFT);
        $cont = 0;
        $calculoDv = 0;
        for ($num = 0; $num < 21; $num++) {
            $cont++;
            if ($cont == 1) {
                $constante = 3;
            }
            if ($cont == 2) {
                $constante = 1;
            }
            if ($cont == 3) {
                $constante = 9;
            }
            if ($cont == 4) {
                $constante = 7;
                $cont = 0;
            }
            $calculoDv = $calculoDv + (substr($sequencia, $num, 1) * $constante);
        }

        $Resto = $calculoDv % 11;
        if ($Resto == 0 || $Resto == 1) {
            $Dv = 0;
        } else {
            $Dv = 11 - $Resto;
        }

        $result["nosso_numero"] = $NossoNumero;
        $result["digito"] = $Dv;
        return $result;
    }

}

?>
