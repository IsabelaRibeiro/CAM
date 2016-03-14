
<?php
require_once 'Persistencia/Seguranca/PermissaoSP.php';

$_renderizar = false;

switch ($_GET ["ACAO"]) {
    case "Cadastrar":
        $post = arrayToObject($_POST, "Permissao");
        if (trim($post->IdUsuario) == '') {
            $_msg = "Selecione um Perfil";
        } else {
            $obj = new Permissao();
            $obj->setaConexao($bd);
            $resultado = $obj->Cadastrar($post);
            $_msg = ($resultado) ? "Registro " . substr($_GET ["ACAO"], 0, -1) . "do com sucesso" : "Ocorreu um erro ao " . $_GET ["ACAO"] . " o Registro. {$obj->_msg}";
            $_dados = new MensagemModel (true, $_msg);
        }
        break;

    case "ComboPerfil" :
        require_once 'Persistencia/Seguranca/PerfilSP.php';
        $obj = new Perfil ();
        $obj->setaConexao($bd);
        $obj->Combo();
        $_dados = $obj->Lista;
        break;

    case "Listar" :
        $post = arrayToObject($_POST, "Permissao");
        $_dados = new MensagemModel (true, $_msg);
        $obj = new Permissao ();
        $obj->setaConexao($bd);
        if (trim($post->IdTipoColaborador) < 1) {
            if ($obj->Listar(0)) {
                $_dados = PrepararPermissoes($obj->Lista);
            }
        } else {
            if ($obj->Listar($post->IdUsuario)) {
                $_dados = PrepararPermissoes($obj->Lista);
            }
        }
        break;

    default :
        $janela = new Tela ();
        $janela->setaConexao($bd);
        $janela->AbreEmJanela($_POST ['TELA']);
        $_d = $janela->Lista;

        $_renderizar = true;
}
function PrepararPermissoes($aLista)
{
    $oldSistema = "";
    $oldModulo = "";
    $oldTela = "";
    $telas = array();
    $tela = null;
    $modulos = array();
    $modulo = null;
    $sistemas = array();
    $sistema = null;

    for ($i = 0, $q = count($aLista); $i < $q; $i++) {
        // verifica se é do mesmo módulo
        if ($oldModulo != $aLista [$i]->IdModulo) {
            if ($i > 0) {
                $modulo->Filhos = $telas;
                $modulos [] = $modulo;
            }

            $telas = array();
            $modulo = new ArvoreItem ();
            $modulo->Id = $aLista [$i]->IdModulo;
            $modulo->Nome = $aLista [$i]->Modulo;
            $modulo->Tipo = "Modulo";
            $modulo->Selecionado = false;
        }

        $tela = new ArvoreItem ();
        $tela->Id = $aLista [$i]->IdTela;
        $tela->Nome = $aLista [$i]->Tela;
        $tela->Tipo = "Tela";
        $tela->Selecionado = $aLista [$i]->Selecionado;
        $modulo->Selecionado = $modulo->Selecionado || $tela->Selecionado;
        $telas [] = $tela;

        // verifica se é do mesmo sistema
        if ($oldSistema != $aLista [$i]->IdSistema) {
            if ($i > 0) {
                $sistema->Filhos = $modulos;
                $sistemas [] = $sistema;
            }

            $modulos = array();
            $sistema = new ArvoreItem ();
            $sistema->Id = $aLista [$i]->IdSistema;
            $sistema->Nome = $aLista [$i]->Sistema;
            $sistema->Tipo = "Sistema";
            $sistema->Selecionado = false;
        }

        $sistema->Selecionado = $sistema->Selecionado || $modulo->Selecionado;

        // atualiza as variáveis de controle
        $oldTela = $aLista [$i]->IdTela;
        $oldModulo = $aLista [$i]->IdModulo;
        $oldSistema = $aLista [$i]->IdSistema;
    }

    $modulo->Filhos = $telas;
    $modulos [] = $modulo;
    $sistema->Filhos = $modulos;
    $sistemas [] = $sistema;

    return $sistemas;
}