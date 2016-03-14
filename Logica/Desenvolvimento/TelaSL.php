<?php
require_once 'Persistencia/Desenvolvimento/TelaSP.php';

// cancela a renderiza&ccedil;&atilde;o como HTML, para evitar erros no JSON
$_renderizar = false;

switch ($_GET ["ACAO"]) {
	case "Recuperar" :
		// instancia objeto de persist&ecirc;ncia
		$obj = new Tela ();
		$obj->setaConexao ( $bd );
		$obj->Recuperar ( $_POST ["ID"] );
		$_dados = $obj;
		break;
	case "Cadastrar" :
	case "Alterar" :
		// cria o objeto Model com os dados postados
		$post = arrayToObject ( $_POST, "Tela" );
		// valida&ccedil;&atilde;o de preenchimento dos campos
		if (trim ( $post->IdSistema ) < 1) {
			$_msg = "Selecione o campo Sistema";
		} else if (trim ( $post->IdModulo ) < 1) {
			$_msg = "Selecione o campo Módulo";
		} else if (@trim ( $post->Funcionalidades )) {
			$_msg = "Preencha o campo Funcionalidade";
		} else if (trim ( $post->Nome ) == '') {
			$_msg = "Preencha o campo Nome";
		} else if (trim ( $post->Identificador ) == '') {
			$_msg = "Preencha o campo Identificador";
		} else if (trim ( $post->Ordem ) < 0) {
			$_msg = "Preencha o campo Ordem";
		} else if (trim ( $post->Altura ) < 0) {
			$_msg = "Preencha o campo Altura";
		} else if (trim ( $post->Largura ) < 0) {
			$_msg = "Preencha o campo Largura";
		} else {
			// instancia objeto de persist&ecirc;ncia
			$obj = new Tela ();
			$obj->setaConexao ( $bd );
			// salva o registro, cadastrando em caso de um novo e alterando em caso de j&aacute; existente
			$resultado = ($post->Id > 0) ? $obj->Alterar ( $post ) : $obj->Cadastrar ( $post );
			// monta a mensagem de resultado
			$_msg = ($resultado) ? "Registro " . substr ( $_GET ["ACAO"], 0, - 1 ) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
		}
		// converte os dados retornados para JSON e retorna para a View
		$_dados = new MensagemModel ( true, $_msg );
		break;
	case "ComboModulos" :
		require_once 'Persistencia/Desenvolvimento/ModuloSP.php';
		// instancia objeto de persist&ecirc;ncia
		$obj = new Modulo ();
		$obj->setaConexao ( $bd );
		$obj->Combo ( preparaFiltro () );
		// converte os dados retornados para JSON e retorna para a View
		$_dados = $obj->Lista;
		
		break;
	case "ComboSistemas" :
		require_once 'Persistencia/Desenvolvimento/SistemaSP.php';
		// instancia objeto de persist&ecirc;ncia
		$obj = new Sistema ();
		$obj->setaConexao ( $bd );
		$obj->Combo ();
		// converte os dados retornados para JSON e retorna para a View
		$_dados = $obj->Lista;
		break;
	case "MultiSelectFuncionalidades" :
		require_once 'Persistencia/Desenvolvimento/FuncionalidadeSP.php';
		// instancia objeto de persist&ecirc;ncia
		$obj = new Funcionalidade ();
		$obj->setaConexao ( $bd );
		$obj->Combo ();
		// converte os dados retornados para JSON e retorna para a View
		$_dados = $obj->Lista;
		break;
	case "Excluir" :
		// cria o objeto Model com os dados postados
		$post = arrayToObject ( $_POST, "Tela" );
		// verifica se foi postado um registro com Id v&aacute;lido
		if ($post->Id > 0) {
			// instancia objeto de persist&ecirc;ncia
			$obj = new Tela ();
			$obj->setaConexao ( $bd );
			// executa a fun&ccedil;&atilde;o que exclui o registro
			$resultado = $obj->Excluir ( $post );
			// monta a mensagem de resultado
			$_msg = ($resultado) ? "Registro " . substr ( $_GET ["ACAO"], 0, - 2 ) . "&iacute;do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
			// converte os dados retornados para JSON e retorna para a View
			$_dados = new MensagemModel ( true, $_msg );
		}
		break;
	case "Listar" :
		// instancia o objeto de persist&ecirc;ncia
		$obj = new Tela ();
		$obj->setaConexao ( $bd );
		// executa a pesquisa
		$obj->Listar ($_POST['VALOR']);
			// converte os dados retornados para JSON e retorna para a View
		$_dados = $obj->Lista;
		break;
	default :
		$janela = new Tela ();
		$janela->setaConexao ( $bd );
		$janela->AbreEmJanela ( $_POST ['TELA'] );
		$_d = $janela->Lista;

		$_renderizar = true;
}
