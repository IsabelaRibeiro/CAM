<?php
require_once 'Persistencia/Seguranca/UsuarioSP.php';
require_once 'Logica/Seguranca/SessaoSL.php';

switch ($_GET ["ACAO"]) {
    case "Autenticar" :
        $obj = new Usuario ();
        $obj->setaConexao($bd);
        if ($obj->Autenticar($_POST ['edtLogin'], $_POST ['edtSenha'])) {
           $_SESSION ['ssIdAtivo'] = $obj->Id;
            $obj->ListarPermissoes ($obj->IdPerfil);
            GeraSessao ( $obj->Id, $obj->Login, $obj->IdPerfil, $obj->Permissoes, $obj->Sessao, $obj->Nome);
            $_renderizar = true;
        } else {
            if ($obj->_msg == '') {
                $_msg = "Falha na autentica&ccedil;&atilde;o, confirme seu login e senha";
                require 'index.php';
                exit();
            } else {
                $_msg = $obj->_msg;
                require_once 'index.php';
            }
        }
        break;
    default :
        $_renderizar = true;
}