<?php

class Sistema extends Padrao {
    public $Nome;
    public $Selecionado;

    public function Cadastrar($dados) {
        $sql = "INSERT INTO SISTEMA (NOME) values (@nome) ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $resultado = $this->bd->Executar("SISTEMA", "NOME='{$dados->Nome}' AND STATUS > 0 ");
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Alterar($dados) {
        $sql = "UPDATE SISTEMA SET NOME = @nome,  WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['@id'] = $dados->Id;
        $resultado = $this->bd->Executar("SISTEMA", "NOME='{$dados->Nome}' AND STATUS > 0 AND ID <> {$dados->Id}");
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Excluir($dados) {
        $sql = "UPDATE SISTEMA SET STATUS=ABS(STATUS)*-1 WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@id'] = $dados->Id;
        return ($this->bd->Executar());
    }

    public function Listar($aValor = '####') {
        $aValor = removerAcentos($aValor);
        $sql = "SELECT ID,NOME FROM SISTEMA WHERE NOME LIKE '%{$aValor}%' AND STATUS > 0 ORDER BY NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Lista = array();
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Sistema();
                $tmp->Id = $registro->Campo["ID"];
                $tmp->Nome = $registro->Campo["NOME"];
                $this->Lista[] = $tmp;
            }
        } else {
            $this->Lista [] = new Sistema();
        }
        return true;
    }

    public function Recuperar($aId) {
        $sql = "SELECT ID, NOME FROM SISTEMA WHERE Id = {$aId} ORDER BY NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Id = $this->bd->Registro [0]->Campo ["ID"];
            $this->Nome = $this->bd->Registro [0]->Campo ["NOME"];
            return true;
        } else {
            return false;
        }
    }

    public function Combo() {
        $this->Lista = array();
        $sql = "SELECT ID, NOME FROM SISTEMA WHERE STATUS > 0 ORDER BY NOME";
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