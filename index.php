<?
unset($_SESSION['ssIdLogado'], $_SESSION['ssLogin'], $_SESSION['ssIdChefe'], $_SESSION['ssTipoColaborador']);
unset($_SESSION);
@session_destroy();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Centro de Apoio</title>
    <style>
        html, body {
            width: 99%;
            height: 98%;
            background: #ff9538;
            margin: 0px;
            padding: 0px;
        }

        a {
            color: gray;
            font-size: small;
        }

        .tbLayout {
            background: white;
            border: 0;
            border-style: hidden;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -160px;
            margin-top: -250px;
        }

        .form {
            text-align: center;
            width: 350px;
            left: 400px;
        }

        .msgAlerta {
            text-align: center;
            color: red;
            font-size: medium;
        }

        .k-textbox {
            width: 160px;
            font-size: small;
        }

        .logo {
            background-image: url(Imagens/Logo.png);
            width: 300px;
            height: 300px;
            background-repeat: no-repeat;
            margin-top: 10px;
            margin-left: 25px;
            vertical-align: middle;
        }

    </style>
</head>
<body>
<table class="tbLayout">
    <tr class="cabecalho">
        <td>
            <div class="logo"></div>
        </td>
    </tr>
    <tr>
        <td>
            <form name="frmLogin" action="principal.php?ACAO=Autenticar" method="post" class="form" target="_parent">
                <span class="msgAlerta"><?php echo $_msg; ?></span> <br/>
                <input type="hidden" name="MODULO" value="Seguranca"/>
                <input type="hidden" name="TELA" value="Autenticar"/>
                <br/>
                <input type="text" name="edtLogin" class="k-textbox" placeholder="Login" value="<?php echo $_POST['edtLogin']; ?>"/><br/>
                <input type="password" name="edtSenha" class="k-textbox" placeholder="Senha"/><br/>
                <input type="submit" class="k-button" name="btnAutenticar" value="Entrar"/> <br/>
            </form>
        </td>
    </tr>
    <tr class="rodape">
        <td>
            <footer>&nbsp;</footer>
        </td>
    </tr>
</table>
</body>
</html>