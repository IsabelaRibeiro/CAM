<?php

class Perfil extends Padrao {

    public function Cadastrar($dados) {
        $sql = "INSERT INTO PERFIL (NOME) VALUES (@nome) ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Alterar($dados) {
        $sql = "UPDATE PERFIL SET NOME = @nome WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['@id'] = $dados->Id;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Excluir($dados) {
        $sql = "UPDATE PERFIL SET STATUS=ABS(STATUS)*-1 WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@id'] = $dados->Id;
        return ($this->bd->Executar());
    }

    public function Listar($aValor = '####') {

        if ($_SESSION['ssIdPerfil'] != 1) {
            $where = " AND U.ID <> {$_SESSION['ssIdLogado']} ";
        }

        $aValor = removerAcentos($aValor);
        $sql = "SELECT P.ID, P.NOME
                FROM  PERFIL P
                WHERE P.NOME LIKE '%{$aValor}%' AND P.STATUS > 0 {$where}
                ORDER BY U.NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Lista = array();
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Perfil();
                $tmp->Id = $registro->Campo["ID"];
                $tmp->Nome = $registro->Campo["NOME"];
                $this->Lista[] = $tmp;
            }
        } else {
            $this->Lista [] = new Perfil();
        }
        return true;
    }

    public function Recuperar($aId) {
        $sql = "SELECT ID, NOME
                FROM PERFIL
                WHERE ID = {$aId}
                ORDER BY NOME";
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
        $sql = "SELECT ID, NOME FROM PERFIL WHERE STATUS > 0 ORDER BY NOME";
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