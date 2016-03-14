<?php
// valida o acesso a este script, se n&atilde;o foram postados as configura&ccedil;�es de M&oacute;dulo, Tela e A&ccedil;&atilde;o, ser&aacute; redirecionado para a Index do login
if ((isset($_GET ['JANELA']) && $_GET ['JANELA'] == 1)){
    $_POST ["MODULO"] = $_GET ['MODULO'];
    $_POST ["TELA"] = $_GET ['TELA'];
    $_POST ["JANELA"] = $_GET ['JANELA'];
    $_POST ["ID"] = $_GET ['ID'];
}

if (! isset($_POST ["MODULO"]) || ! isset($_POST ["TELA"])) {
    $_msg = "Tentativa de acesso indevido...";
    require 'index.php';
    exit();
}

if ($_POST ["MODULO"] == 'sair' || $_POST ["TELA"] == 'sair') {
    $_msg = "Sess&atilde;o Encerrada";
    require "Logica/Seguranca/SessaoSL.php";
    DestroiSessao();
    require 'index.php';
    exit();
}

if (substr($_GET ["ACAO"], 0, 5) == "Combo" && $_POST ["TELA"] == "Autenticar") {
    exit;
}

// carrega as configura&ccedil;�es
require 'Includes/configuracoes.php';

// --------------VERIFICA AS PERMISS�ES----------------------
if ($_SESSION ["ssIdLogado"] != '' || $_POST ["TELA"] == 'Autenticar') {
    if ($_SESSION ["ssModulo"] == '' && $_POST ["TELA"] != 'Autenticar') 	// Verificar se o usuario realmente tem permissao
    {
        $_msg = "Usuario sem Permiss&atilde;o";
        require 'index.php';
        exit();
    }
} else {
    $_msg = "Login n&atilde;o realizado";
    require 'index.php';
    exit();
}

// valida se as vari&aacute;veis de m&oacute;dulo e tela recebidas s&atilde;o v&aacute;lidas
$_arquivo = CAMINHO . '/Logica/' . $_POST ["MODULO"] . '/' . $_POST ["TELA"] . "SL.php";
if (! file_exists($_arquivo)) {
    $_msg = "Tentativa de acesso a um arquivo inexistente ($_arquivo)...";
    require 'index.php';
    exit();
}

// instancia conex&atilde;o com o banco de dados
$bd = new bdMysql();
$bd->NoLock = true;
$bd->Aplicativo = "CAM";
$bd->EchoErrors = true;


// carrega a parte logica do sistema de acordo com as vari&aacute;veis que foram postadas
require_once 'Logica/' . $_POST ["MODULO"] . '/' . $_POST ["TELA"] . "SL.php";

// caso seja para renderizar uma p&aacute;gina web para o navegador
if ($_renderizar) {
    // prepara tela e modulo quando &#65533; janela padr&#65533;o do perfil
    if (($_POST ["TELA"] == "Autenticar" || $_POST ["TELA"] == "principal" ) && $_SESSION['ssIdPerfil'] > 0 && !empty($_SESSION['ssIdenModulo']) && !empty($_SESSION ['ssIdenTela'])) {
        $_POST ["MODULO"] = $_SESSION['ssIdenModulo'];
        $_POST ["TELA"] = $_SESSION ['ssIdenTela'];
        require_once 'Logica/' . $_POST ["MODULO"] . '/' . $_POST ["TELA"] . "SL.php";
    }

    // carrega o cabe&ccedil;alho do sistema
    if (substr($_POST ["TELA"], 0, 9) != "Relatorio" && substr($_POST ["TELA"], 0, 9) != "ContratoG"){
        include 'Telas/Includes/cabecalho.php';
        echo "<br />&nbsp;<br />";

        if ($_POST["TELA"]=="Autenticar") {
            echo $_aviso;
        }
    }

    if ($_POST ["TELA"] != "principal" && $_POST ["TELA"] != "Autenticar") {
        require 'Telas/' . $_POST ["MODULO"] . '/' . $_POST ["TELA"] . "ST.php";
    }
    ?>



    <style scoped>
        #example {
            min-height: 840px;
        }

        #undo {
            text-align: center;
            position: absolute;
            white-space: nowrap;
            border-width: 1px;
            border-style: solid;
            padding: 2em;
            cursor: pointer;
        }
    </style>
    <?php

    // carrega o rodap�
    if (substr($_POST ["TELA"], 0, 9) != "Relatorio" && substr($_POST ["TELA"], 0, 8) != "Contrato"){
        include 'Telas/Includes/rodape.php';
    }
} else {
    // se n�o renderizar, escreve o que estiver em $_dados
    if (substr($_GET ["ACAO"], 0, 5) == "Combo" && $_GET ["ACAO"] != 'ComboQuebras' && $_GET ["ACAO"] != 'ComboMetas' && $_GET["ACAO"] != 'ComboPreCadastrosTms') {
        $selecione = new ComboItem();
        $selecione->Id = 0;
        $selecione->Nome = "Selecione...";
        $selecione->Ativo = 1;
        $tmp_dados [0] = $selecione;
        if($_dados != '')
        {
            $_dados = array_merge($tmp_dados, $_dados);
        }else {
            $_dados = $tmp_dados;
        }
    }
    if ($_dados) {
        echo @json_encode($_dados);
    }
}