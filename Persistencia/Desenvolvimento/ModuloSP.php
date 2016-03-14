<?php

class Modulo extends Padrao {
    public $Nome;
    public $Identificador;
    public $Ordem;
    public $IdSistema;
    public $Sistema;
    public $Selecionado;

    public function Cadastrar($dados) {
        $sql = "INSERT INTO MODULO (NOME, IDENTIFICADOR, ORDEM, IDSISTEMA) VALUES (@nome, @identificador, #ordem, #idsistema)";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro ["@nome"] = $dados->Nome;
        $this->bd->Parametro ["@identificador"] = $dados->Identificador;
        $this->bd->Parametro ["#ordem"] = $dados->Ordem;
        $this->bd->Parametro ["#idsistema"] = $dados->IdSistema;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Alterar($dados) {
        $sql = "UPDATE MODULO SET NOME = @nome, IDENTIFICADOR = @identificador, ORDEM = #ordem, IDSISTEMA = #idsistema WHERE ID= #id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro ["@nome"] = $dados->Nome;
        $this->bd->Parametro ["@identificador"] = $dados->Identificador;
        $this->bd->Parametro ["#ordem"] = $dados->Ordem;
        $this->bd->Parametro ["#idsistema"] = $dados->IdSistema;
        $this->bd->Parametro ["#id"] = $dados->Id;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Excluir($dados) {
        $sql = "UPDATE MODULO SET STATUS=ABS (STATUS) * -1 WHERE ID = #id "; // ABS = Status absoluto, deste modo, caso dois usuarios excluam ele vai permanecer negativo
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro ["#id"] = $dados->Id;
        return ($this->bd->Executar());
    }

    public function Recuperar($aId) {
        $sql = "SELECT ID, NOME, IDENTIFICADOR, ORDEM, IDSISTEMA FROM MODULO WHERE Id = {$aId} ORDER BY NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Id = $this->bd->Registro [0]->Campo ["ID"];
            $this->Nome = $this->bd->Registro [0]->Campo ["NOME"];
            $this->Identificador =$this->bd->Registro [0]->Campo ["IDENTIFICADOR"];
            $this->Ordem = $this->bd->Registro [0]->Campo ["ORDEM"];
            $this->IdSistema =$this->bd->Registro [0]->Campo ["IDSISTEMA"];
            return true;
        } else {
            return false;
        }
    }

    public function Listar($aValor = '####') {
        $aValor = removerAcentos($aValor);
        $sql = "SELECT M.ID,IDSISTEMA,M.NOME,IDENTIFICADOR,ORDEM,M.STATUS AS STATUS,M.DTHRCADASTRO AS DTCADASTRO, S.NOME AS SNOME
				FROM MODULO M
				INNER JOIN  SISTEMA AS S ON (M.IDSISTEMA = S.ID)
				WHERE (M.NOME LIKE '%{$aValor}%' OR IDENTIFICADOR LIKE '%{$aValor}%') AND M.STATUS > 0
				ORDER BY S.NOME, M.ORDEM";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Lista = array();
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Modulo ();
                $tmp->Id = $registro->Campo ["ID"];
                $tmp->IdSistema = $registro->Campo ["IDSISTEMA"];
                $tmp->Nome = $registro->Campo ["NOME"];
                $tmp->Identificador = $registro->Campo ["IDENTIFICADOR"];
                $tmp->Ordem = $registro->Campo ["ORDEM"];
                $tmp->DtCadastro = $registro->Campo ["DTCADASTRO"];
                $tmp->Status = $registro->Campo ["STATUS"];
                $tmp->Sistema = $registro->Campo ["SNOME"];
                $tmp->Selecionado = false;
                $this->Lista [] = $tmp;
            }
            return true;
        } else {
            $this->Lista [] = new Modulo();
            return false;
        }
    }

    public function Combo($aId = 0) {
        $this->Lista = array();
        $where = $aId > 0 ? " AND IDSISTEMA = {$aId} " : "";
        $sql = "SELECT ID, NOME FROM MODULO WHERE STATUS > 0 $where ORDER BY NOME ";
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
