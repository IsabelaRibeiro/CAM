<?php
function GeraSessao($IdAtivo, $Login,  $IdPerfil, $Permissoes, $Token, $Nome)
{
    global $bd;
    $_SESSION['ssIdLogado'] = $IdAtivo;
    $_SESSION['ssLogin'] = $Login;
    $_SESSION['ssUsuario'] = $Nome;
    $_SESSION['ssIdPerfil'] = $IdPerfil;
    $_SESSION['ssToken'] = $Token;
    $_SESSION['ssModulo'] = serialize($Permissoes);

}

function DestroiSessao()
{
    unset($_SESSION['ssIdLogado'],
        $_SESSION['ssLogin'],
        $_SESSION['ssUsuario'],
        $_SESSION['ssIdPerfil'],
        $_SESSION['ssModulo'],
        $_SESSION['ssToken']
    );

    unset($_SESSION);
    session_unset();
}

?>