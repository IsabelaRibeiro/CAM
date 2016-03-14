<?php
require_once 'Persistencia/Desenvolvimento/SistemaSP.php';
require_once 'Persistencia/Desenvolvimento/ModuloSP.php';
require_once 'Persistencia/Desenvolvimento/TelaSP.php';

class Permissao extends Padrao {
    public $IdTela;
    public $Tela;
    public $Identificador;
    public $MIdentificador;
    public $IdSistema;
    public $Sistema;
    public $IdModulo;
    public $Modulo;
    public $Janela;
    public $Selecionado;
    public $Altura;
    public $Largura;
    public $Arquivo;
    public $IdPerfil;
    public $Permissoes;

    public function Cadastrar($dados) {
        $sql = "DELETE FROM PERMISSAOPERFIL WHERE IDPERFIL = #idperfil  ";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $this->bd->Parametro ['idperfil'] = $dados->IdPerfil;
        if ($this->bd->Executar()) {
            if ($dados->Permissoes != '') {
                $permissoes = explode(';', $dados->Permissoes);
                $sql = "INSERT INTO PERMISSAOPERFIL (IDTELA, IDPERFIL) VALUES ";
                foreach ($permissoes as $reg) {
                    $dado = explode('_', $reg);
                    $IdTela = $dado[1];
                    if ($IdTela > 0 ) {
                        $sql .= "(".$IdTela.",$dados->IdPerfil),";
                    }
                }
                $this->bd->Clear();
                $this->bd->setSQL(substr($sql,0,-1));
                $resultado = $this->bd->Executar();
            } else
                $resultado = true;
        } else
            return false;
        return ($resultado);
    }

    public function Listar($aIdUsuario) {
        if ($_SESSION['ssIdLogado'] != IDDESENVOLVEDOR) {
            $where = " AND M.ID NOT IN (2)";
        } else {
            $where = "";
        }

        $sql = "SELECT M.IDSISTEMA, S.NOME AS SISTEMA,
                       T.IDMODULO, M.NOME AS MODULO,
                       T.NOME AS TELA, T.ID AS IDTELA,
                       CAST(CASE WHEN ISNULL(PP.IDTELA) = T.ID OR PP.IDPERFIL = 1  THEN 1 ELSE 0 END AS BINARY) AS SELECIONADO,
                       T.JANELA
                FROM  SISTEMA S
                INNER JOIN MODULO M ON (S.ID = M.IDSISTEMA)
                INNER JOIN TELA T ON (M.ID = T.IDMODULO)
                LEFT JOIN PERMISSAOPERFIL PP ON (PP.IDTELA = {$aIdUsuario} AND PP.IDTELA = T.ID)
                LEFT JOIN PERFIL P ON (P.ID = PP.IDPERFIL)
                WHERE S.STATUS > 0 AND M.STATUS > 0 AND T.STATUS > 0 {$where}
	            ORDER BY S.NOME,M.NOME,T.NOME";
        $this->bd->Clear ();
        $this->bd->setSQL ( $sql );
        if ($this->bd->Executar()) {
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Permissao();
                $tmp->IdTela = $registro->Campo["IDTELA"];
                $tmp->Tela = $registro->Campo["TELA"];
                $tmp->IdSistema = $registro->Campo["IDSISTEMA"];
                $tmp->Sistema = $registro->Campo["SISTEMA"];
                $tmp->IdModulo = $registro->Campo["IDMODULO"];
                $tmp->Modulo = $registro->Campo["MODULO"];
                $tmp->Janela = $registro->Campo["JANELA"];
                $tmp->Selecionado = $registro->Campo["SELECIONADO"];
                $this->Lista[] = $tmp;
            }
            return true;
        } else {
            return false;
        }
    }

    public function ListarMenu($IdPerfil) {
        $sql = "SELECT S.ID AS IDSISTEMA, S.NOME AS SISTEMA,
					   M.ID AS IDMODULO, M.NOME AS MODULO, M.IDENTIFICADOR AS MIDENTIFICADOR,
					   T.ID AS IDTELA, T.NOME AS TELA,
					   T.JANELA, T.ALTURA, T.LARGURA, T.IDENTIFICADOR
				FROM SISTEMA AS S
				INNER JOIN MODULO AS M ON (M.IdSistema = S.Id)
				INNER JOIN TELA AS T ON (T.IdModulo = M.Id) ";

        $where = "";

        if ($IdPerfil != 1) { // valida permissao�o para desenvovedor
            $sql .= "	INNER JOIN PERMISSAOPERFIL AS PP ON ( PP.IDPERFIL = {$IdPerfil}) ";
            $where = " AND M.ID NOT IN (2) ";
        }

        $sql .= "	WHERE T.STATUS=1 AND M.STATUS > 0 $where
					ORDER BY S.NOME,M.ORDEM,M.NOME,T.ORDEM,T.NOME";
        $this->bd->Clear();
        $this->bd->setSQL($sql);
        $menu = array();
        if ($this->bd->Executar()) {
            foreach ($this->bd->Registro as $registro) {
                $tmp = new Permissao ();
                $tmp->IdSistema = $registro->Campo ["IDSISTEMA"];
                $tmp->Sistema = $registro->Campo ["SISTEMA"];
                $tmp->IdModulo = $registro->Campo ["IDMODULO"];
                $tmp->Modulo = $registro->Campo ["MODULO"];
                $tmp->IdTela = $registro->Campo ["IDTELA"];
                $tmp->Tela = $registro->Campo ["TELA"];
                $tmp->Janela = $registro->Campo ["JANELA"];
                $tmp->Altura = $registro->Campo ["ALTURA"];
                $tmp->Largura = $registro->Campo ["LARGURA"];
                $tmp->Identificador = $registro->Campo ["IDENTIFICADOR"];
                $tmp->MIdentificador = $registro->Campo ["MIDENTIFICADOR"];
                $tmp->Arquivo = $registro->Campo ["IDENTIFICADOR"];
                $tmp->Selecionado = true;
                $menu [$tmp->Sistema] [$tmp->MIdentificador] [$tmp->Tela] = $tmp;
            }
            $this->Lista = $menu;
            return true;
        } else {
            return false;
        }
    }


}