<?php
    $logo = "Imagens/LogoSAD_menu.png";
    $logoRodape = "Imagens/LogoSAD_rodape.png";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Centro de Apoio</title>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/jquery-2.0.0.min.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/jquery.smartWizard.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/jquery.base64.min.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/kendo/kendo.all.min.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/jquery-ui-1.10.4.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/kendo/kendo.web.plugins.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/kendo/cultures/kendo.culture.pt-BR.min.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/kendo/lang/kendo.language.pt-BR.js"></script>

    <script type="text/javascript" charset="iso-8859-1">
        var urlBase = "http://<?php  echo $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"]; echo "";?>";
        var modulo = "<?=$_POST["MODULO"]?>";
        var tela = "<?=$_POST["TELA"]?>";
    </script>

    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/funcoes.js" charset="iso-8859-1"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/kendo/jszip.min.js"></script>
    <script type="text/javascript" src="<?php echo URLBASE; ?>Telas/Includes/javascript/kendo/require.js"></script>

    <link rel="stylesheet" href="<?php echo URLBASE; ?>Telas/Includes/css/kendo/kendo.common.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo URLBASE; ?>Telas/Includes/css/kendo/kendo.default.min.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo URLBASE; ?>Telas/Includes/css/kendo/kendo.web.plugins.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo URLBASE; ?>Telas/Includes/css/estilo.css" type="text/css"/>
    


    <script type="text/javascript">
        function acessaTela(modulo, tela, acao, janela, nome, altura, largura, id, opcaoextra) {
            if (janela != 1) {
                $("#MODULO").val(modulo);
                $("#TELA").val(tela);
                $("#ID").val(id);
                $("#ACAO").val(acao);
                $('#frmNavegar').attr('action', "?ACAO=" + acao);
                $("#frmNavegar").submit();
            } else {
                var window = $("#janelam" + modulo + "t" + tela);
                if (!window.length) {
                    $("#janelas").html('<div id="janelam' + modulo + "t" + tela + '"></div>');
                    window = $("#janelam" + modulo + "t" + tela);
                }
                window.kendoWindow({
                    iframe: true,
                    title: nome,
                    width: largura,
                    position: {top: 50},
                    height: altura,
                    minTopPosition: 50,
                    content: "principal.php?ACAO=" + acao + "&MODULO=" + modulo + "&TELA=" + tela + "&JANELA=" + janela + "&ID=" + id + "&OPCAOEXTRA=" + opcaoextra,
                    resize: CorrigeTamanho,
                    dragend: PosicionaLimite
                });
                window = $("#janelam" + modulo + "t" + tela).data("kendoWindow");
                window.center();
                window.open();
                menu = $("#ulMenu").data("kendoMenu");
                if (menu != null) {
                    menu.close();
                }
            }
        }

        function fecharTela(aTela, aMsg) {
            var window = $(aTela).data("kendoWindow");
            window.close();
            if (aMsg != undefined && aMsg != '') {
                MsgAlerta(null, aMsg);
            }
        }

        function PosicionaLimite(e) {
            if (this.wrapper != null)
                var obj = this.wrapper;
            else
                var obj = e.sender.wrapper;
            var posicao = obj.css("top");
            posicao = posicao.replace('px', '');
            if (posicao < 30)
                obj.css({top: 30});

        }

        function CorrigeTamanho(e) {
            PosicionaLimite(e);
            var tam = this.wrapper.css("width");
            tam = tam.replace('px', '');
            tam -= 30;
            $('#boxGrid').css('width', tam);
            //	this.wrapper.css({ height : tam});
        }

        window.onresize = function () {
            var tam = window.innerWidth - 40;
            $('#dvLista').css('width', tam);
            var tam = window.innerHeight - 110;
            $('#horizontal').css('height', tam);
            resizeGrid();
        };

        $(document).ready(function () {
            var tam = window.innerWidth - 40;
            $('#dvLista').css('width', tam);
            var tam = window.innerHeight - 110;
            $('#horizontal').css('height', tam);
            resizeGrid();
        });

        function resizeGrid() {
            if (dados != undefined && $("#grdDados").length) {
                var gridElement = $("#grdDados"),
                    dataArea = gridElement.find(".k-grid-content"),
                    gridHeight = window.innerHeight - 150;//gridElement.innerHeight(),
                otherElements = gridElement.children().not(".k-grid-content"),
                    otherElementsHeight = 100;
                otherElements.each(function () {
                    otherElementsHeight += $(this).outerHeight();
                });
                $('#grdDados').css('height', "100%");
                dados.pageSize(Math.ceil((gridHeight - otherElementsHeight) / 26));
                dataArea.height(gridHeight - otherElementsHeight);
            }

            if ($("#grdSubDados").length) {
                var gridElement = $("#grdSubDados"),
                    dataArea = gridElement.find(".k-grid-content"),
                    gridHeight = window.innerHeight - 150;//gridElement.innerHeight(),
                otherElements = gridElement.children().not(".k-grid-content"),
                    otherElementsHeight = 100;
                otherElements.each(function () {
                    otherElementsHeight += $(this).outerHeight();
                });
                $('#grdSubDados').css('height', "100%");
                dataArea.height(gridHeight - otherElementsHeight);
            }
        }
    </script>
</head>
<body>
<div class="clCarregando">
    &nbsp;<br/> <img src="Imagens/loading_2x.gif"/>
</div>
<table class="tbLayout">
    <tr class="cabecalho">
        <td style="height: 0px;">
            <?php
            require_once 'Persistencia/Seguranca/PermissaoSP.php';
            $Modulos = unserialize($_SESSION["ssModulo"]);
            if (!isset($_POST ['JANELA'])) {
                ?>
                <div id="dvMenu">
                    <ul id="ulMenu">
                        <li><a href="javascript:acessaTela('Seguranca','Autenticar','Novo')">Home</a></li>
                        <?php
                        $relatorio = false;
                        foreach ($Modulos as $ids => $sis) {
                            foreach ($sis as $nome => $mod) {
                                echo "<li>" . $nome . '<ul>';
                                foreach ($mod as $tela) {
                                    if (($tela->Tela == "MenuPessoal") || ($tela->Tela == "Home") || isset($TelaJaExibida [$tela->IdTela])) {
                                        continue;
                                    } else {
                                        if (strtolower(substr($tela->Tela, 0, 5)) == "relat" || strtolower(substr($tela->Tela, 0, 5)) == "gráfi") {
                                            if ($relatorio == '') {
                                                $relatorio = "<li>Relat&oacute;rios<ul>";
                                            }
                                            $t = explode("#", $tela->Arquivo);
                                            $acao = isset($t [1]) ? $t [1] : "Novo";
                                            $relatorio .= '<li><a href="javascript:acessaTela(' . "'" . $tela->MIdentificador . "', '" . $t [0] . "', '" . $acao . "'" . ",'" . $tela->Janela . "','" . $tela->Tela . "','" . $tela->Altura . "','" . $tela->Largura . "')" . '">' . $tela->Tela . '</a></li>';
                                        } else {
                                            $t = explode("#", $tela->Arquivo);
                                            $acao = isset($t [1]) ? $t [1] : "Novo";
                                            echo '<li><a href="javascript:acessaTela(' . "'" . $tela->MIdentificador . "', '" . $t [0] . "', '" . $acao . "'" . ",'" . $tela->Janela . "','" . $tela->Tela . "','" . $tela->Altura . "','" . $tela->Largura . "')" . '">' . $tela->Tela . '</a></li>';
                                        }
                                        $TelaJaExibida [$tela->IdTela] = $tela->IdTela;
                                    }
                                }
                                if ($relatorio != '') {
                                    echo $relatorio .= "</ul></li>";
                                    $relatorio = '';
                                }
                                echo "</ul></li>\n";
                            }
                        }
                        ?>
                        <li><a href="javascript:acessaTela('sair','sair','sair')">Sair</a></li>
                    </ul>
                    <script>
                        $("#ulMenu").kendoMenu();
                    </script>
                </div>
            <?php } ?>
            <form name="frmNavegar" id="frmNavegar" class="frmNavegar"
                  method="post">
                <input type="hidden" id="MODULO" name="MODULO"/> <input
                    type="hidden" id="TELA" name="TELA"/> <input type="hidden"
                                                                 id="ID" name="ID"/>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <div id="janelas"></div>