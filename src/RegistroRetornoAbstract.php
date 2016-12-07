<?php
/*
* CnabPHP - GeraÃ§Ã£o de arquivos de remessa e retorno em PHP
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
namespace CnabPHP;

/**
 * Class RegistroRetornoAbstract
 * @package CnabPHP
 */
abstract class RegistroRetornoAbstract
{
    /**
     * @var array
     */
    protected $data;
    /**
     * @var mixed
     */
    protected $meta;
    /**
     * @var mixed
     */
    protected $children;

    /**
     * RegistroRetornoAbstract constructor.
     * @param $line
     */
    public function __construct($line)
    {
        $posicao = 0;
        foreach ($this->meta as $key => $value) {
            $valor = (isset($value['precision'])) ? substr($line, $posicao, $value['tamanho'] + $value['precision']) : substr($line, $posicao, $value['tamanho']);

            /** @noinspection PhpVariableVariableInspection */
            $this->$key = $valor;
            $posicao += (isset($value['precision'])) ? $value['tamanho'] + $value['precision'] : $value['tamanho'];

        }
    }


    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (method_exists($this, 'set_' . $name)) {
            call_user_func([$this, 'set_' . $name], $value);
        } else {
            $metaData = (isset($this->meta[$name])) ? $this->meta[$name] : null;
            switch ($metaData['tipo']) {
                case 'decimal':
                    $inteiro = abs(substr($value, 0, $metaData['tamanho']));
                    $decimal = abs(substr($value, $metaData['tamanho'], $metaData['precision'])) / 100;
                    $retorno = ($inteiro + $decimal);
                    $this->data[$name] = $retorno;
                    break;
                case 'int':
                    $retorno = abs($value);
                    $this->data[$name] = $retorno;
                    break;
                case 'alfa':
                    $retorno = trim($value);
                    $this->data[$name] = $retorno;
                    break;
                case 'date':
                    if ($metaData['tamanho'] == 6) {
                        $data = \DateTime::createFromFormat('dmy', sprintf('%06d', $value));
                        $retorno = $date->format('Y-m-d');
                        $this->data[$name] = $retorno;

                    } elseif ($metaData['tamanho'] == 8) {
                        $data = \DateTime::createFromFormat('dmY', sprintf('%08d', $value));
                        $retorno = $data->format("Y-m-d");
                        $this->data[$name] = $retorno;
                    }
                    break;
                default:
                    $this->data[$name] = $value;
                    break;
            }
        }
    }

    /*
    * mÃ©todo __get()
    * executado sempre que uma propriedade for requerida
    */
    public function __get($prop)
    {
        // verifica se existe mÃ©todo get_<propriedade>
        if (method_exists($this, 'get_' . $prop)) {
            // executa o mÃ©todo get_<propriedade>
            return call_user_func(array($this, 'get_' . $prop));
        } else {
            return $this->data[$prop];
        }
    }

    /*
    * mÃ©todo ___get()
    * metodo auxiliar para ser chamado para dentro de metodo get personalizado
    */
    public function ___get($prop)
    {
        // retorna o valor da propriedade
        if (isset($this->meta[$prop])) {
            $metaData = (isset($this->meta[$prop])) ? $this->meta[$prop] : null;
            //$this->data[$prop] = !isset($this->data[$prop]) || $this->data[$prop]==''?$metaData['default']:$this->data[$prop];
            //if($metaData['required']==true && ($this->data[$prop]=='' || !isset($this->data[$prop])))
            //{
            //	throw new Exception('Campo faltante ou com valor nulo:'.$prop);
            //}
            switch ($metaData['tipo']) {
                case 'decimal':
                    return ($this->data[$prop]) ? number_format($this->data[$prop], $metaData['precision'], ',', '.') : '';
                    //return str_pad($retorno,$metaData['tamanho'],'0',STR_PAD_LEFT);
                    break;
                case 'int':
                    return (isset($this->data[$prop])) ? abs($this->data[$prop]) : '';
                    //return str_pad($retorno,$metaData['tamanho'],'0',STR_PAD_LEFT);
                    break;
                case 'alfa':
                    return ($this->data[$prop]) ? $this->prepareText($this->data[$prop]) : '';
                    //return str_pad(substr($retorno,0,$metaData['tamanho']),$metaData['tamanho'],' ',STR_PAD_RIGHT);
                    break;
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 6:
                    return ($this->data[$prop]) ? date("d/m/y", strtotime($this->data[$prop])) : '';
                    //return str_pad($retorno,$metaData['tamanho'],'0',STR_PAD_LEFT);
                    break;
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 8:
                    return ($this->data[$prop]) ? date("d/m/Y", strtotime($this->data[$prop])) : '';
                    //return str_pad($retorno,$metaData['tamanho'],'0',STR_PAD_LEFT);
                    break;
                default:
                    return null;
                    break;
            }
        }
    }

    public function get_meta()
    {
        return $this->meta;
    }

    /*
    * mÃ©todo getUnformated()
    * busca o valor de dentro do campo dentro do objeto de forma simples sem formataÃ§Ã£o de valor por exemplo
    */
    public function getUnformated($prop)
    {
        // retorna o valor da propriedade
        if (isset($this->data[$prop])) {
            return $this->data[$prop];
        }
    }

    /*
    * mÃ©todo getChilds()
    * Metodo que retorna todos os filhos
    */
    public function getChilds()
    {
        return $this->children;
    }

    /*
    * mÃ©todo getChild()
    * Metodo que retorna um filho
    */
    public function getChild($index = 0)
    {
        return $this->children[$index];
    }

}