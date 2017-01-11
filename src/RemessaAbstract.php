<?php

namespace CnabPHP;

/**
 * Class RemessaAbstract
 * @package CnabPHP
 */
abstract class RemessaAbstract
{
    /**
     * @var array
     */
    public static $BANCOS = [
        '104' => 'CaixaEconomicaFederal',
        '237' => 'Bradesco',
        '341' => 'Itau',
        '756' => 'Sicoob',
    ];
    /**
     * @var string
     */
    public static $banco;
    /**
     * @var string
     */
    public static $leiaute;
    /**
     * @var string
     */
    public static $cabecalho;
    /**
     * @var string
     */
    public static $dados;
    /**
     * @var int
     */
    public static $loteContador = 1; // contador de lotes
    /**
     * @var array
     */
    private static $filhos = []; // armazena os registros filhos da classe remessa
    /**
     * @var array
     */
    public static $retorno = []; // durante a geração do txt de retorno se tornara um array com as linhas do arquvio

    /**
     * RemessaAbstract constructor.
     * @param string $banco
     * @param string $leiaute
     * @param array $dados
     */
    public function __construct($banco, $leiaute, $dados)
    {
        self::$banco = self::$BANCOS[$banco];
        self::$leiaute = $leiaute;
        self::$dados = $dados;

        $class = $this->register(self::$banco, self::$leiaute, 0);

        self::$cabecalho = new $class($dados);
        self::$filhos[] = self::$cabecalho;
    }

    /**
     * @param $banco
     * @param $leiaute
     * @param $registro
     * @return string
     */
    protected function register($banco, $leiaute, $registro)
    {
        return "\\CnabPHP\\Resources\\{$banco}\\Remessa\\{$leiaute}\\Registro{$registro}";
    }

    /**
     * @param $data
     */
    public function adicionarDetalhe($data)
    {
        //$class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
        $class = $this->register(self::$banco, self::$leiaute, 1);

        self::adicionarFilho(new $class($data));
        //self::$counter++;
    }

    /**
     * @param $data
     */
    public function adicionarDetalheMensagem($data)
    {
        //$class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
        $class = $this->register(self::$banco, self::$leiaute, 2);

        self::adicionarFilho(new $class($data));
        //self::$counter++;
    }

    /**
     * @param $leiaute
     */
    public function trocarLeiaute($leiaute)
    {
        self::$leiaute = $leiaute;
    }

    /**
     * @param RegistroRemessaAbstract $child
     */
    static private function adicionarFilho(RegistroRemessaAbstract $child)
    {
        self::$filhos[] = $child;
    }

    /**
     * @param array $data
     * @return RemessaAbstract
     */
    public function adicionarLote(array $data)
    {
        if (strpos(self::$leiaute, '240')) {
            //$class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro1';
            $class = $this->register(self::$banco, self::$leiaute, 1);
            $loteData = $data ? $data : RemessaAbstract::$dados;
            $lote = new $class($loteData);
            self::adicionarFilho($lote);
        } else {
            $lote = $this;
        }
        self::$loteContador++;

        return $lote;
    }

    /**
     * @param $index
     * @return mixed
     */
    public static function getLote($index)
    {
        if (isset(self::$filhos[$index])) {
            return self::$filhos[$index];
        } else if (isset(self::$filhos[$index - 1])) {
            return self::$filhos[$index - 1];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getArquivo()
    {
        foreach (self::$filhos as $child) {
            $child->getArquivo();
        }
        //$class = '\CnabPHP\Resources\\' . self::$banco . '\remessa\\' . self::$layout . '\Registro9';
        $class = $this->register(self::$banco, self::$leiaute, 9);
        /** @var RegistroRemessaAbstract $header */
        $header = new $class(['1' => 1]);
        $header->getArquivo();
        return implode("\r\n", self::$retorno) . "\r\n";
    }
}