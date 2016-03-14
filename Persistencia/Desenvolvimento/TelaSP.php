<?php
class Tela extends Padrao {
	public $IdSistema;
	public $Sistema;
	public $IdModulo;
	public $Modulo;
	public $Nome;
	public $Identificador;
	public $Ordem;
	public $Funcionalidades;
	public $Janela;
	public $Selecionado;
	public $Altura;
	public $Largura;

	public function Cadastrar($dados) {
		$sql = "INSERT INTO TELA (IDMODULO, NOME, IDENTIFICADOR, ORDEM, JANELA, ALTURA, LARGURA) VALUES  (#idmodulo, @nome, @identificador, #ordem, %janela, #altura, #largura) ";
		$this->bd->Clear ();
		$this->bd->setSQL ( $sql );
		$this->bd->Parametro ["#idmodulo"] = $dados->IdModulo;
		$this->bd->Parametro ["@nome"] = $dados->Nome;
		$this->bd->Parametro ["@identificador"] = $dados->Identificador;
		$this->bd->Parametro ["#ordem"] = $dados->Ordem;
		$this->bd->Parametro ["%janela"] = $dados->Janela;
		$this->bd->Parametro ["#altura"] = $dados->Altura;
		$this->bd->Parametro ["#largura"] = $dados->Largura;
		$resultado = $this->bd->Executar ();
		if ($this->bd->Duplicado) {
			$this->_msg = "Registro Duplicado";
		} 
		return ($resultado);
	}

	public function Alterar($dados) {
		$sql = "UPDATE TELA SET IDMODULO = #idmodulo, NOME = @nome, IDENTIFICADOR = @identificador, ORDEM = #ordem, JANELA = %janela, ALTURA = #altura, LARGURA = #largura WHERE ID = #id ";
		$this->bd->Clear ();
		$this->bd->setSQL ( $sql );
		$this->bd->Parametro ["#idmodulo"] = $dados->IdModulo;
		$this->bd->Parametro ["@nome"] = $dados->Nome;
		$this->bd->Parametro ["@identificador"] = $dados->Identificador;
		$this->bd->Parametro ["#ordem"] = $dados->Ordem;
		$this->bd->Parametro ["%janela"] = $dados->Janela;
		$this->bd->Parametro ["#altura"] = $dados->Altura;
		$this->bd->Parametro ["#largura"] = $dados->Largura;
		$this->bd->Parametro ["#id"] = $dados->Id;
		$resultado = $this->bd->Executar ();
		if ($this->bd->Duplicado) {
			$this->_msg = "Registro Duplicado";
		}
		return ($resultado);
	}

	public function Excluir($dados) {
		$sql = "UPDATE TELA SET STATUS=ABS (STATUS) * -1 WHERE ID = #id ";
		$this->bd->Clear ();
		$this->bd->setSQL ( $sql );
		$this->bd->Parametro ["#id"] = $dados->Id;
		return ($this->bd->Executar ());
	}

	public function Listar($aValor='####') {
		$aValor = removerAcentos($aValor);
		$sql = "SELECT M.NOME AS MODULO, T.ID, T.IDMODULO, T.NOME, T.IDENTIFICADOR, T.ORDEM, M.IDSISTEMA, S.NOME AS SISTEMA, T.JANELA, T.ALTURA, T.LARGURA
		FROM TELA T
		INNER JOIN MODULO M ON (T.IDMODULO = M.ID)
		LEFT JOIN SISTEMA S ON (M.IDSISTEMA = S.ID)
		WHERE (M.NOME LIKE '%{$aValor}%' OR S.NOME LIKE '%{$aValor}%' OR T.IDENTIFICADOR LIKE '%{$aValor}%') AND T.STATUS > 0
		ORDER BY M.IDSISTEMA, M.NOME, T.NOME";
		$this->bd->Clear ();
		$this->bd->setSQL ( $sql );
		if ($this->bd->Executar ()) {
			$this->Lista = array ();
			foreach ( $this->bd->Registro as $registro ) {
				$tmp = new Tela ();
				$tmp->Id = $registro->Campo ["ID"];
				$tmp->IdModulo = $registro->Campo ["IDMODULO"];
				$tmp->Modulo = $registro->Campo ["MODULO"];
				$tmp->IdSistema = $registro->Campo ["IDSISTEMA"];
				$tmp->Sistema = $registro->Campo ["SISTEMA"];
				$tmp->Nome = $registro->Campo ["NOME"];
				$tmp->Identificador = $registro->Campo ["IDENTIFICADOR"];
				$tmp->Ordem = $registro->Campo ["ORDEM"];
				$tmp->Janela = $registro->Campo ["JANELA"];
				$tmp->Altura = $registro->Campo ["ALTURA"];
				$tmp->Largura = $registro->Campo ["LARGURA"];
				$tmp->Selecionado = false;
				$this->Lista [] = $tmp;
			}
			return true;
		} else {
			$this->Lista [] = new Tela();
			return false;
		}
	}

	public function Recuperar($aIdTela) {
		$this->Id = $aIdTela;
		$sql = "SELECT T.ID,T.IDMODULO,T.NOME,T.IDENTIFICADOR,T.ORDEM, M.IDSISTEMA, T.JANELA, T.ALTURA, T.LARGURA
				FROM TELA T
				INNER JOIN MODULO M ON (T.IDMODULO = M.ID)
				WHERE T.ID = {$this->Id}";
		$this->bd->Clear ();
		$this->bd->setSQL ( $sql );
		if ($this->bd->Executar ()) {
			$this->IdModulo = $this->bd->Registro [0]->Campo ["IDMODULO"];
			$this->IdSistema = $this->bd->Registro [0]->Campo ["IDSISTEMA"];
			$this->Nome = $this->bd->Registro [0]->Campo ["NOME"];
			$this->Identificador = $this->bd->Registro [0]->Campo ["IDENTIFICADOR"];
			$this->Ordem = $this->bd->Registro [0]->Campo ["ORDEM"];
			$this->Janela = $this->bd->Registro [0]->Campo ["JANELA"] == 1;
			$this->Altura = $this->bd->Registro [0]->Campo ["ALTURA"];
			$this->Largura = $this->bd->Registro [0]->Campo ["LARGURA"];
			return true;
		} else {
			return false;
		}
	}

	public function Combo() {
		$this->Lista = array ();
		$sql = "SELECT ID, NOME FROM TELA WHERE STATUS > 0 ORDER BY NOME ";
		$this->bd->Clear ();
		$this->bd->setSQL ( $sql );
		if ($this->bd->Executar ()) {
			foreach ( $this->bd->Registro as $registro ) {
				$tmp = new ComboItem ();
				$tmp->Id = $registro->Campo ["ID"];
				$tmp->Nome = $registro->Campo ["NOME"];
				$this->Lista [] = $tmp;
			}
			return true;
		} else {
			return false;
		}
	}
	
	//combo por modulo
	public function ComboPorModulo($aIdModulo) {
		$this->Lista = array();
		$sql = "SELECT ID, NOME FROM TELA WHERE STATUS > 0 AND IDMODULO = {$aIdModulo} ORDER BY NOME ";
		$this->bd->Clear();
		$this->bd->setSQL( $sql );
		if ($this->bd->Executar()) {
			foreach ($this->bd->Registro as $registro ) {
				$tmp = new ComboItem ();
				$tmp->Id = $registro->Campo["ID"];
				$tmp->Nome = $registro->Campo["NOME"];
				$this->Lista [] = $tmp;
			}
		} else {
			$this->Lista[] = new Tela();
		}
		return true;
	}
	
	//combo por modulo, traz somente janelas que abrem no fundo da tela
	public function ComboTelasFundoPorModulo($aIdModulo) {
		$this->Lista = array();
		$sql = "SELECT ID, NOME FROM TELA WHERE STATUS > 0 AND IDMODULO = {$aIdModulo} AND JANELA = 0 ORDER BY NOME ";
		$this->bd->Clear();
		$this->bd->setSQL( $sql );
		if ($this->bd->Executar()) {
			foreach ($this->bd->Registro as $registro ) {
				$tmp = new ComboItem ();
				$tmp->Id = $registro->Campo["ID"];
				$tmp->Nome = $registro->Campo["NOME"];
				$this->Lista [] = $tmp;
			}
		} else {
			$this->Lista[] = new Tela();
		}
		return true;
	}
	

	public function AbreEmJanela($aIdentificadorTela) {
		$sql = "SELECT JANELA, NOME FROM TELA WHERE IDENTIFICADOR='{$aIdentificadorTela}' AND STATUS > 0";
		$this->bd->Clear ();
		$this->bd->setSQL ( $sql );
		if ($this->bd->Executar ()) {
			foreach ( $this->bd->Registro as $registro ) {
				$tmp = new Tela();
				$tmp->Janela = $registro->Campo ["JANELA"];
				$tmp->Nome = $registro->Campo["NOME"];
				$this->Lista [] = $tmp;
			}
		} else {
			$this->Lista[] = new Tela();
		}
		return true;
	}
}