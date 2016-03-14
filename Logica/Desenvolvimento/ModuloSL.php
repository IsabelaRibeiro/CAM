<?php
require_once 'Persistencia/Desenvolvimento/ModuloSP.php';

$_renderizar = false;

switch ($_GET ["ACAO"]) {
	case "Cadastrar" :
	case "Alterar" :
		$post = arrayToObject ( $_POST, "Modulo" );
		if (trim ( $post->IdSistema < 1 )) {
			$_msg = "Selecione o Sistema";
		} else if (trim ( $post->Nome ) == '') {
			$_msg = "Preencha o campo Nome";
		} else if (trim ( $post->Identificador ) == '') {
			$_msg = "Preencha o campo Identificador";
		} else if (trim ( $post->Ordem ) < 0) {
			$_msg = "Preencha o campo Ordem";
		} else {
			$obj = new Modulo ();
			$obj->setaConexao ( $bd );
			$resultado = ($post->Id > 0) ? $obj->Alterar ( $post ) : $obj->Cadastrar ( $post );
			$_msg = ($resultado) ? "Registro " . substr ( $_GET ["ACAO"], 0, - 1 ) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
		}
		$_dados = new MensagemModel ( true, $_msg );
		break;
	case "ComboSistemas" :
		require_once 'Persistencia/Desenvolvimento/SistemaSP.php';
		$obj = new Sistema ();
		$obj->setaConexao ( $bd );
		if ($obj->Combo ()) {
			$_dados = $obj->Lista;
		}
		break;
	case "Excluir" :
		$post = arrayToObject ( $_POST, "Modulo" );
		if ($post->Id > 0) {
			$obj = new Modulo ();
			$obj->setaConexao ( $bd );
			$resultado = $obj->Excluir ( $post );
			$_msg = ($resultado) ? "Registro " . substr ( $_GET ["ACAO"], 0, - 1 ) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
			$_dados = new MensagemModel ( true, $_msg );
		}
		break;

	case "Recuperar" :
		$obj = new Modulo();
		$obj->setaConexao ( $bd );
		$obj->Recuperar ( $_POST ["ID"] );
		$_dados = $obj;
		break;

	case "Listar" :
		$obj = new Modulo ();
		$obj->setaConexao ( $bd );
		$obj->Listar ($_POST['VALOR']);
		$_dados = $obj->Lista;
		break;
	
	default :
		require_once 'Persistencia/Desenvolvimento/TelaSP.php';
		$janela = new Tela ();
		$janela->setaConexao ( $bd );
		$janela->AbreEmJanela ( $_POST ['TELA'] );
		$_d = $janela->Lista;

		$_renderizar = true;
}