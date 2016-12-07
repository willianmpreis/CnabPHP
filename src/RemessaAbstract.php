<?php

namespace CnabPHP;

/**
 * Class RemessaAbstract
 * @package CnabPHP
 */
abstract class RemessaAbstract
{
    /**
     * @var string
     */
    public static $banco;
    /**
     * @var string
     */
    public static $layout;
    /**
     * @var string
     */
    public static $header;
    /**
     * @var string
     */
    public static $entryData;
    /**
     * @var int
     */
    public static $loteCounter = 1; // contador de lotes
    /**
     * @var array
     */
    private static $children = []; // armazena os registros filhos da classe remessa
    /**
     * @var array
     */
    public static $retorno = []; // durante a geração do txt de retorno se tornara um array com as linhas do arquvio

    /**
     * RemessaAbstract constructor.
     * @param string $banco
     * @param string $layout
     * @param array $data
     */
    public function __construct($banco, $layout, $data)
    {
        self::$banco = "B" . $banco;
        self::$layout = $layout;
        $class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro0';
        self::$entryData = $data;
        self::$header = new $class($data);
        self::$children[] = self::$header;
    }

    /**
     * @param $data
     */
    public function addDetail($data)
    {
        $class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
        self::addChild(new $class($data));
        //self::$counter++;
    }

    /**
     * @param $newLayout
     */
    public function changeLayout($newLayout)
    {
        self::$layout = $newLayout;
    }

    /**
     * @param RegistroRemessaAbstract $child
     */
    static private function addChild(RegistroRemessaAbstract $child)
    {
        self::$children[] = $child;
    }

    /**
     * @param array $data
     * @return RemessaAbstract
     */
    public function addLot(array $data)
    {
        if (strpos(self::$layout, '240')) {
            $class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
            $loteData = $data ? $data : RemessaAbstract::$entryData;
            $lote = new $class($loteData);
            self::addChild($lote);
        } else {
            $lote = $this;
        }
        self::$loteCounter++;

        return $lote;
    }

    /**
     * @param $index
     * @return mixed
     */
    public static function getLot($index)
    {
        return self::$children[$index];
    }

    /**
     * @return string
     */
    public function getText()
    {
        foreach (self::$children as $child) {
            $child->getText();
        }
        $class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro9';
        /** @var RegistroRemessaAbstract $header */
        $header = new $class(['1' => 1]);
        $header->getText();
        return implode("\r\n", self::$retorno) . "\r\n";
    }
}