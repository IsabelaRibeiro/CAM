<?php
require_once 'Persistencia/Desenvolvimento/SistemaSP.php';

// cancela a renderiza&ccedil;&atilde;o como HTML, para evitar erros no JSON
$_renderizar = false;

switch ($_GET ["ACAO"]) {
	case "Cadastrar" :
	case "Alterar" :
		$post = arrayToObject ( $_POST, "Sistema" );
		if (trim ( $post->Nome ) == '') {
			$_msg = "Preencha o campo Nome";
		} else {
			$obj = new Sistema ();
			$obj->setaConexao ( $bd );
			$resultado = ($post->Id > 0) ? $obj->Alterar ( $post ) : $obj->Cadastrar ( $post );
			$_msg = ($resultado) ? "Registro " . substr ( $_GET ["ACAO"], 0, - 1 ) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
		}
		$_dados = new MensagemModel ( true, $_msg );
		break;
	case "Recuperar" :
		$obj = new Sistema();
		$obj->setaConexao ( $bd );
		$obj->Recuperar ( $_POST ["ID"] );
		$_dados = $obj;
		break;
	case "Excluir" :
		$post = arrayToObject ( $_POST, "Sistema" );
		if ($post->Id > 0) {
			$obj = new Sistema ();
			$obj->setaConexao ( $bd );
			$resultado = $obj->Excluir ( $post );
			$_msg = ($resultado) ? "Registro " . substr ( $_GET ["ACAO"], 0, - 1 ) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
			$_dados = new MensagemModel ( true, $_msg );
		}
		break;
	case "Listar" :
		$obj = new Sistema ();
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