<?php

class Estado extends Padrao {
    public $Nome;
    public $Sigla;
    public $IdPais;
    public $Pais;

    public function Cadastrar($dados) {
        $sql = "INSERT INTO ESTADO (NOME, SIGLA, IDPAIS) values (@nome, @sigla, #idpais) ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['@sigla'] = $dados->Sigla;
        $this->bd->Parametro['#idpais'] = $dados->IdPais;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Alterar($dados) {
        $sql = "UPDATE ESTADO SET NOME = @nome, SIGLA = @sigla, IDPAIS = #idpais WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['@sigla'] = $dados->Sigla;
        $this->bd->Parametro['#idpais'] = $dados->IdPais;
        $this->bd->Parametro['@id'] = $dados->Id;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Excluir($dados) {
        $sql = "UPDATE ESTADO SET STATUS=ABS(STATUS)*-1 WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@id'] = $dados->Id;
        return ($this->bd->Executar());
    }

    public function Listar($aValor = '####') {
        $aValor = removerAcentos($aValor);
        $sql = "SELECT E.ID, E.NOME, P.NOME AS PAIS, E.SIGLA
                FROM ESTADO E
                INNER JOIN PAIS P ON (P.ID = E.IDPAIS)
                WHERE E.NOME LIKE '%{$aValor}%' AND E.STATUS > 0
                ORDER BY E.NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Lista = array();
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Estado();
                $tmp->Id = $registro->Campo["ID"];
                $tmp->Nome = $registro->Campo["NOME"];
                $tmp->Pais = $registro->Campo["PAIS"];
                $tmp->Sigla = $registro->Campo["SIGLA"];
                $this->Lista[] = $tmp;
            }
        } else {
            $this->Lista [] = new Estado();
        }
        return true;
    }

    public function Recuperar($aId) {
        $sql = "SELECT ID, NOME, SIGLA, IDPAIS FROM ESTADO WHERE Id = {$aId} ORDER BY NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Id = $this->bd->Registro [0]->Campo ["ID"];
            $this->Nome = $this->bd->Registro [0]->Campo ["NOME"];
            $this->Sigla = $this->bd->Registro [0]->Campo ["SIGLA"];
            $this->IdPais = $this->bd->Registro [0]->Campo ["IDPAIS"];
            return true;
        } else {
            return false;
        }
    }

    public function Combo() {
        $this->Lista = array();
        $sql = "SELECT ID, NOME FROM ESTADO WHERE STATUS > 0 ORDER BY NOME";
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

    public function ComboPorPais($aIdPais) {
        $this->Lista = array();
        $sql = "SELECT ID, NOME FROM ESTADO WHERE STATUS > 0 AND IDPAIS = {$aIdPais} ORDER BY NOME";
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