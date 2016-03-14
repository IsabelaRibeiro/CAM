<?php

class Cidade extends Padrao {
    public $Nome;
    public $IdEstado;
    public $Estado;

    public function Cadastrar($dados) {
        $sql = "INSERT INTO CIDADE (NOME, IDESTADO) values (@nome,  #idestado) ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['#idestado'] = $dados->IdEstado;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Alterar($dados) {
        $sql = "UPDATE CIDADE SET NOME = @nome, IDESTADO = #idestado WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['#idestado'] = $dados->IdEstado;
        $this->bd->Parametro['@id'] = $dados->Id;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Excluir($dados) {
        $sql = "UPDATE CIDADE SET STATUS=ABS(STATUS)*-1 WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@id'] = $dados->Id;
        return ($this->bd->Executar());
    }

    public function Listar($aValor = '####') {
        $aValor = removerAcentos($aValor);
        $sql = "SELECT C.ID, C.NOME, E.NOME AS ESTADO
                FROM CIDADE C
                INNER JOIN ESTADO E ON (E.ID = C.IDESTADO)
                WHERE C.NOME LIKE '%{$aValor}%' AND C.STATUS > 0
                ORDER BY C.NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Lista = array();
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Cidade();
                $tmp->Id = $registro->Campo["ID"];
                $tmp->Nome = $registro->Campo["NOME"];
                $tmp->Pais = $registro->Campo["ESTADO"];
                $this->Lista[] = $tmp;
            }
        } else {
            $this->Lista [] = new Cidade();
        }
        return true;
    }

    public function Recuperar($aId) {
        $sql = "SELECT ID, NOME, IDESTADO FROM CIDADE WHERE Id = {$aId} ORDER BY NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Id = $this->bd->Registro [0]->Campo ["ID"];
            $this->Nome = $this->bd->Registro [0]->Campo ["NOME"];
            $this->IdEstado = $this->bd->Registro [0]->Campo ["IDESTADO"];
            return true;
        } else {
            return false;
        }
    }

    public function Combo() {
        $this->Lista = array();
        $sql = "SELECT ID, NOME FROM CIDADE WHERE STATUS > 0 ORDER BY NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            foreach ($this->bd->Registro as $registro) {
                $tmp = new ComboItem();
                $tmp->Id = $registro->Campo["ID"];
                $tmp->Nome = $registro->Campo["NOME"];
                $this->Lista[] = $tmp;
            }
            return true;
        } else {
            return false;
        }
    }

    public function ComboPorEstado($aIdEstado) {
        $this->Lista = array();
        $sql = "SELECT ID, NOME FROM CIDADE WHERE STATUS > 0 AND IDESTADO = {$aIdEstado} ORDER BY NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            foreach ($this->bd->Registro as $registro) {
                $tmp = new ComboItem();
                $tmp->Id = $registro->Campo["ID"];
                $tmp->Nome = $registro->Campo["NOME"];
                $this->Lista[] = $tmp;
            }
            return true;
        } else {
            return false;
        }
    }
}