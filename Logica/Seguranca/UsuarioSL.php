<?php
require_once 'Persistencia/Seguranca/UsuarioSP.php';

$_renderizar = false;

switch ($_GET ["ACAO"]) {
    case "Cadastrar" :
    case "Alterar" :
        $post = arrayToObject($_POST, "Usuario");
        if (trim($post->Nome) == '') {
            $_msg = "Preencha o campo Nome";
        } else {
            $obj = new Usuario ();
            $obj->setaConexao($bd);
            $resultado = ($post->Id > 0) ? $obj->Alterar($post) : $obj->Cadastrar($post);
            $_msg = ($resultado) ? "Registro " . substr($_GET ["ACAO"], 0, -1) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
        }
        $_dados = new MensagemModel (true, $_msg);
        break;
    case "Recuperar" :
        $obj = new Usuario();
        $obj->setaConexao($bd);
        $obj->Recuperar($_POST ["ID"]);
        $_dados = $obj;
        break;
    case "Excluir" :
        $post = arrayToObject($_POST, "Usuario");
        if ($post->Id > 0) {
            $obj = new Usuario ();
            $obj->setaConexao($bd);
            $resultado = $obj->Excluir($post);
            $_msg = ($resultado) ? "Registro " . substr($_GET ["ACAO"], 0, -1) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
            $_dados = new MensagemModel (true, $_msg);
        }
        break;
    case "Listar" :
        $obj = new Usuario ();
        $obj->setaConexao($bd);
        $obj->Listar($_POST['VALOR']);
        $_dados = $obj->Lista;
        break;
    case "ComboEstados" :
        require_once 'Persistencia/PreCadastros/EstadoSP.php';
        $obj = new Estado ();
        $obj->setaConexao($bd);
        if ($obj->Combo()) {
            $_dados = $obj->Lista;
        }
        break;
    case "ComboCidades" :
        require_once 'Persistencia/PreCadastros/CidadeSP.php';
        $obj = new Cidade ();
        $obj->setaConexao($bd);
        if ($obj->Combo(preparaFiltro())) {
            $_dados = $obj->Lista;
        }
        break;
    case "ComboPerfil" :
        require_once 'Persistencia/Seguranca/PerfilSP.php';
        // instancia objeto de persist&ecirc;ncia
        $obj = new Perfil ();
        $obj->setaConexao($bd);
        if ($obj->Combo()) {
            $_dados = $obj->Lista;
        }
        break;
    default :
        require_once 'Persistencia/Desenvolvimento/TelaSP.php';
        $janela = new Tela ();
        $janela->setaConexao($bd);
        $janela->AbreEmJanela($_POST ['TELA']);
        $_d = $janela->Lista;

        $_renderizar = true;
}