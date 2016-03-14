<?php

abstract class Padrao
{
    protected $bd;
    public $Id;
    public $Nome;
    public $Status;
    public $DataCadastro;
    public $Lista;
    public $_msg;

    public function __construct()
    {
        $this->Lista = array();
        $this->Id = 0;
        $this->Nome = "";
        $this->Status = 1;
    }


    public function setaConexao(bdMysql &$bd)
    {
        $this->bd = &$bd;
    }

    function VerificarCampos($aTabela, $aCamposWhere, $aCampos)
    {
        $aCamposWhere = removerAcentos2($aCamposWhere);
        $sql = "SELECT {$aCampos} FROM {$aTabela} WHERE {$aCamposWhere}";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Executar();
        return $this->bd->Registro[0]->Campos[0] > 0;
    }
}