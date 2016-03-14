<?php

class Usuario extends Padrao {

    public $Sessao;
    public $IdPerfil;
    public $Perfil;
    public $Login;
    public $Senha;
    public $DtNascimento;
    public $Telefone;
    public $Celular;
    public $Email;
    public $Endereco;
    public $IdCidade;
    public $Bairro;
    public $Reikiano;
    public $Observacao;
    public $IdEstado;

    public function Autenticar($login, $senha) {
        $sql = "SELECT U.ID AS IDUSUARIO, U.LOGIN, U.NOME AS USUARIO, P.ID AS IDPERFIL, P.NOME AS PERFIL, U.STATUS
				FROM USUARIO AS U
				INNER JOIN PERFIL AS P ON ( U.IDPERFIL = P.ID )
				WHERE LOGIN = @login
				AND SENHA = @senha";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro["@login"] = $login;
        $this->bd->Parametro["@senha"] = $senha;
        if ($this->bd->Executar()) {
            $this->Id = $this->bd->Registro[0]->Campo["IDUSUARIO"];
            $this->Nome = $this->bd->Registro[0]->Campo["USUARIO"];
            $this->Login = $this->bd->Registro[0]->Campo["LOGIN"];
            $this->Perfil = $this->bd->Registro[0]->Campo["PERFIL"];
            $this->IdPerfil = $this->bd->Registro[0]->Campo["IDPERFIL"];
            $this->Status = $this->bd->Registro[0]->Campo["STATUS"];
            $this->Sessao = '';
            if ($this->Id > 0) {
                if ($this->Status < 1) {
                    $this->_msg = "Usu&aacute;rio inativo, entre em contato com o administrador!";
                    return false;
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function Cadastrar($dados) {
        $sql = "INSERT INTO USUARIO (NOME, LOGIN, SENHA, DTNASCIMENTO, TELEFONE, CELULAR, EMAIL, ENDERECO, IDCIDADE, BAIRRO, IDPERFIL, REIKIANO, OBSERVACAO) VALUES
                                    (@nome ,@login, @senha, @dtnascimento, @telefone, @celular, @email, @endereco, #idcidade, @bairro, #idperfil, %reikiano, @observacao) ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['@login'] = $dados->Login;
        $this->bd->Parametro['@senha'] = $dados->Senha;
        $this->bd->Parametro['@dtnascimento'] = $dados->DtNascimento;
        $this->bd->Parametro['@telefone'] = $dados->Telefone;
        $this->bd->Parametro['@celular'] = $dados->Celular;
        $this->bd->Parametro['@email'] = $dados->Email;
        $this->bd->Parametro['@endereco'] = $dados->Endereco;
        $this->bd->Parametro['#idcidade'] = $dados->IdCidade;
        $this->bd->Parametro['@bairro'] = $dados->Bairro;
        $this->bd->Parametro['#idperfil'] = $dados->IdPerfil;
        $this->bd->Parametro['%reikiano'] = $dados->Reikiano;
        $this->bd->Parametro['@observacao'] = $dados->Observacao;
        $resultado = $this->bd->Executar();
        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Alterar($dados) {
        $sql = "UPDATE USUARIO SET NOME = @nome, LOGIN = @login, SENHA = @senha, DTNASCIMENTO = @dtnascimento, TELEFONE = @telefone, CELULAR = @celular, EMAIL = @email, ENDERECO = @endereco,
                                   IDCIDADE = #idcidade, BAIRRO = @bairro, IDPERFIL = #idperfil, REIKIANO = #reikiano, OBSERVACAO = @observacao WHERE ID = @id ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro['@nome'] = $dados->Nome;
        $this->bd->Parametro['@login'] = $dados->Login;
        $this->bd->Parametro['@senha'] = $dados->Senha;
        $this->bd->Parametro['@dtnascimento'] = $dados->DtNascimento;
        $this->bd->Parametro['@telefone'] = $dados->Telefone;
        $this->bd->Parametro['@celular'] = $dados->Celular;
        $this->bd->Parametro['@email'] = $dados->Email;
        $this->bd->Parametro['@endereco'] = $dados->Endereco;
        $this->bd->Parametro['#idcidade'] = $dados->IdCidade;
        $this->bd->Parametro['@bairro'] = $dados->Bairro;
        $this->bd->Parametro['#idperfil'] = $dados->IdPerfil;
        $this->bd->Parametro['#reikiano'] = $dados->Reikiano;
        $this->bd->Parametro['@observacao'] = $dados->Observacao;
        $this->bd->Parametro['@id'] = $dados->Id;
        $resultado = $this->bd->Executar();

        if ($this->bd->Duplicado) {
            $this->_msg = "Registro Duplicado";
        }
        return ($resultado);
    }

    public function Excluir($dados) {
        $sql = "UPDATE USUARIO SET STATUS=ABS(STATUS)*-1 WHERE ID = @id ";
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
        $sql = "SELECT U.ID, U.NOME, U.LOGIN, P.NOME AS PERFIL, U.CELULAR, U.TELEFONE
                FROM USUARIO U
                INNER JOIN PERFIL P ON (P.ID = U.IDPERFIL)
                WHERE U.NOME LIKE '%{$aValor}%' AND U.STATUS > 0 {$where}
                ORDER BY U.NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Lista = array();
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Usuario();
                $tmp->Id = $registro->Campo["ID"];
                $tmp->Nome = $registro->Campo["NOME"];
                $tmp->Login = $registro->Campo["LOGIN"];
                $tmp->Perfil = $registro->Campo["PERFIL"];
                $tmp->Celular = $registro->Campo["CELULAR"];
                $tmp->Telefone = $registro->Campo["TELEFONE"];
                $this->Lista[] = $tmp;
            }
        } else {
            $this->Lista [] = new Usuario();
        }
        return true;
    }

    public function Recuperar($aId) {
        $sql = "SELECT U.ID, U.NOME, U.LOGIN, U.DTNASCIMENTO, U.TELEFONE, U.CELULAR, U.EMAIL, U.ENDERECO, U.IDCIDADE,
                       U.BAIRRO, U.IDPERFIL, U.REIKIANO, U.OBSERVACAO, C.IDESTADO
                FROM USUARIO U
                INNER JOIN CIDADE C ON ( C.ID = U.IDCIDADE )
                WHERE U.ID = {$aId}
                ORDER BY U.NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        if ($this->bd->Executar()) {
            $this->Id = $this->bd->Registro [0]->Campo ["ID"];
            $this->Nome = $this->bd->Registro [0]->Campo ["NOME"];
            $this->Login = $this->bd->Registro [0]->Campo ["LOGIN"];
            $this->DtNascimento = $this->bd->Registro [0]->Campo ["DTNASCIMENTO"];
            $this->Telefone = $this->bd->Registro [0]->Campo ["TELEFONE"];
            $this->Celular = $this->bd->Registro [0]->Campo ["CELULAR"];
            $this->Email = $this->bd->Registro [0]->Campo ["EMAIL"];
            $this->Endereco = $this->bd->Registro [0]->Campo ["ENDERECO"];
            $this->IdCidade = $this->bd->Registro [0]->Campo ["IDCIDADE"];
            $this->Bairro = $this->bd->Registro [0]->Campo ["BAIRRO"];
            $this->IdPerfil = $this->bd->Registro [0]->Campo ["IDPERFIL"];
            $this->Reikiano = $this->bd->Registro [0]->Campo ["REIKIANO"];
            $this->Observacao = $this->bd->Registro [0]->Campo ["OBSERVACAO"];
            $this->IdEstado = $this->bd->Registro [0]->Campo ["IDESTADO"];
            return true;
        } else {
            return false;
        }
    }

    public function Combo() {
        $this->Lista = array();
        $sql = "SELECT ID, NOME FROM USUARIO WHERE STATUS > 0 ORDER BY NOME";
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

    public function ListarPermissoes($IdPerfil) {
        require 'Persistencia/Seguranca/PermissaoSP.php';
        $Permissao = new Permissao();
        $Permissao->setaConexao($this->bd);
        $Permissao->ListarMenu($IdPerfil);
        $this->Permissoes = $Permissao->Lista;
        return true;
    }
}		