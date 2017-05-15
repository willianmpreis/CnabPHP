<?php
/*
* CnabPHP - CnabPHP - Geração de arquivos de remessa e retorno em PH
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

use Exception;

/**
 * Class RegistroRemessaAbstract
 * @package CnabPHP
 */
abstract class RegistroRemessaAbstract
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
     * RegistroRemessaAbstract constructor.
     * @param null $data
     */
    public function __construct($data = NULL)
    {
        if ($data) {
            foreach ($this->meta as $key => $value) {
                /** @noinspection PhpVariableVariableInspection */
                $this->$key = (isset($data[$key])) ? $data[$key] : $this->meta[$key]['default'];
            }
        }
    }


    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        if (method_exists($this, 'set_' . $key)) {
            call_user_func([$this, 'set_' . $key], $value);
        } else {
            $metaData = (isset($this->meta[$key])) ? $this->meta[$key] : null;
            if (($value == "" || $value === NULL) && isset($metaData[$key]) && $metaData[$key]['default'] != "") {
                $this->data[$key] = $metaData[$key]['default'];
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * @param $prop
     * @return mixed|null|string
     */
    public function __get($prop)
    {
        if (method_exists($this, 'get_' . $prop)) {
            return call_user_func([$this, 'get_' . $prop]);
        } else {
            return $this->___get($prop);
        }
    }

    /**
     * @param $prop
     * @return null|string
     * @throws Exception
     */
    public function ___get($prop)
    {
        // retorna o valor da propriedade
        if (isset($this->meta[$prop])) {
            $metaData = (isset($this->meta[$prop])) ? $this->meta[$prop] : null;
            $this->data[$prop] = !isset($this->data[$prop]) || $this->data[$prop] == '' ? $metaData['default'] : $this->data[$prop];
            if ($metaData['required'] == true && (!isset($this->data[$prop]) || $this->data[$prop] == '')) {
                throw new Exception('Campo faltante ou com valor nulo: ' . $prop);
            }
            switch ($metaData['tipo']) {
                case 'decimal':
                    $retorno = ($this->data[$prop]) ? number_format($this->data[$prop], $metaData['precision'], '', '') : '';
                    return str_pad($retorno, $metaData['tamanho'] + $metaData['precision'], '0', STR_PAD_LEFT);
                    break;
                case 'int':
                    $retorno = (isset($this->data[$prop])) ? abs($this->data[$prop]) : '';
                    return str_pad($retorno, $metaData['tamanho'], '0', STR_PAD_LEFT);
                    break;
                case 'alfa':
                    $retorno = ($this->data[$prop] || $this->data[$prop] === '0' || $this->data[$prop] === 0) ? $this->prepareText($this->data[$prop]) : '';
                    return str_pad(mb_substr($retorno, 0, $metaData['tamanho'], "UTF-8"), $metaData['tamanho'], ' ', STR_PAD_RIGHT);
                    break;
                case 'alfa2':
                    $retorno = ($this->data[$prop]) ? $this->data[$prop] : '';
                    return str_pad(mb_substr($retorno, 0, $metaData['tamanho'], "UTF-8"), $metaData['tamanho'], ' ', STR_PAD_RIGHT);
                    break;
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 6:
                    $retorno = ($this->data[$prop]) ? date("dmy", strtotime($this->data[$prop])) : '';
                    return str_pad($retorno, $metaData['tamanho'], '0', STR_PAD_LEFT);
                    break;
                case $metaData['tipo'] == 'date' && $metaData['tamanho'] == 8:
                    $retorno = ($this->data[$prop]) ? date("dmY", strtotime($this->data[$prop])) : '';
                    return str_pad($retorno, $metaData['tamanho'], '0', STR_PAD_LEFT);
                    break;
                default:
                    return null;
                    break;
            }
        }
    }

    /**
     * @param $prop
     * @return mixed
     */
    public function getUnformated($prop)
    {
        if (isset($this->data[$prop])) {
            return $this->data[$prop];
        }
        return null;
    }

    /**
     * @param $text
     * @param null $remove
     * @return mixed|string
     */
    private function prepareText($text, $remove = null)
    {
        $result = strtoupper($this->removeAccents(trim(html_entity_decode($text))));
        if ($remove)
            $result = str_replace(str_split($remove), '', $result);
        return $result;
    }

    /**
     * @param $string
     * @return mixed
     */
    private function removeAccents($string)
    {
        return preg_replace(
            [
                '/\xc3[\x80-\x85]/', '/\xc3\x87/', '/\xc3[\x88-\x8b]/', '/\xc3[\x8c-\x8f]/', '/\xc3([\x92-\x96]|\x98)/',
                '/\xc3[\x99-\x9c]/', '/\xc3[\xa0-\xa5]/', '/\xc3\xa7/', '/\xc3[\xa8-\xab]/', '/\xc3[\xac-\xaf]/',
                '/\xc3([\xb2-\xb6]|\xb8)/', '/\xc3[\xb9-\xbc]/',
            ],
            str_split('ACEIOUaceiou', 1),
            $this->isUtf8($string) ? $string : utf8_encode($string)
        );
    }

    /**
     * @param $string
     * @return bool
     */
    private function isUtf8($string)
    {
        return (bool)preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]
            | [\xC2-\xDF][\x80-\xBF]
            | \xE0[\xA0-\xBF][\x80-\xBF]
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
            | \xED[\x80-\x9F][\x80-\xBF]
            | \xF0[\x90-\xBF][\x80-\xBF]{2}
            | [\xF1-\xF3][\x80-\xBF]{3}
            | \xF4[\x80-\x8F][\x80-\xBF]{2}
            )*$%xs',
            $string
        );
    }

    /**
     *
     */
    public function getArquivo()
    {
        $retorno = '';
        foreach ($this->meta as $key => $value) {
            /** @noinspection PhpVariableVariableInspection */
            //print "{$key} :  {$this->$key} <br>";
            $retorno .= $this->$key;
        }

        RemessaAbstract::$retorno[] = $retorno;
        if ($this->children) {
            foreach ($this->children as $child) {
                $child->getArquivo();
            }
        }
    }
}