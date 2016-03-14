<?php if (AbreEmJanela($_d)) {
    $Nome = $_d[0]->Nome == "" ? $_SESSION['ssTela'] : $_d[0]->Nome;
    echo '<div id="dvForm" style="width: 100%; margin-top: -30px;"><div><h3><br/>' . $Nome . '</h3></div>';
} else {
    echo '<div id="dvForm" class="dvForm" style="margin-top: -25px;">';
}
?>
<table class="tbForm">
    <tr>
        <td colspan="2" class="clMsg"></td>
    </tr>
    <tr>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            <span class="clSubTitulo">Perfil</span>
            <br/>
            <select class="k-combobox" id="cboPerfil" data-bind="value: selecionado.IdPerfil" onchange="CarregaDados();"></select>
        </td>
    </tr>
    <tr>
        <td>
            <br/>
            <span class="clSubTitulo">Permiss&otilde;es</span>
            <br/>
            <div id="treeview"><br/></div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="clBotoes">
                <table style="width: 100%">
                    <tr>
                        <td>
                            <input type="button" value="Salvar" id="btnSalvar" data-bind="click: validar" class="k-button"/>
                            <input type="button" value="Cancelar" id="btnCancelar" data-bind="click: limpar" class="k-button"/>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</div>
<script>
    var treeView;
    var cboPerfil;
    var dadosDois;
    var checkedd = [];
    var selecionado = 0;
    var sessaoPerfil = <?php echo $_SESSION['ssIdLogado']?>
        
    /*Definição do Modelo*/
    modelo = kendo.data.Model.define({
        id: "Id",
        fields: {
            Id: {editable: false, nullable: false, type: "number", defaultValue: 0},
            IdPerfil: {nullable: false, type: "number", defaultValue: 0},
            Permissoes: {nullable: false, type: "string"}
        }
    });

    /*Definição do DadaSource*/
    dados = criaDataSource("Permissoes", modelo); //1º parametro é o controller e modelo é a variável criada na definição do modelo

    dadosDois = new kendo.data.HierarchicalDataSource({
        transport: {
            read: {
                url: urlBase + "/principal.php?ACAO=Listar",
                dataType: "json",
                type: "POST",
                data: {MODULO: "<?=$_POST["MODULO"]?>", TELA: "<?=$_POST["TELA"]?>"}
            },
            parameterMap: function (data, type) {
                data.IdPerfil = selecionado;
                data.Permissoes = modelo.Permissoes;
                return data;
            }
        },
        schema: {
            model: {
                children: "Filhos",
                expanded: true,
                Selecionado: "Selecionado",
                Nome: "Nome"
            }
        }
    });

    function CarregaDados() {
        selecionado = $("#cboPerfil").data("kendoComboBox").value();
        dadosDois.read();
    }

    function CancelaOperacao() {
        var chk = $('#treeview').find('input:checkbox');
        var checked = chk.prop("checked");
        chk.prop("checked", false);

    }

    //após o carregamento da página
    $(document).ready(function () {
        vmObjeto = criaViewModel();  // instancia o vm

        vmObjeto.validar = function () {
            if (this.selecionado.IdPerfil < 1) {
                MsgAlerta(null, "Selecione um Perfil!");
                $(".clMsg").text("Selecione um Perfil!");
                return;
            }

            if (this.selecionado.IdPerfil == sessaoPerfil) {
                MsgAlerta(null, "Opera&ccedil;&atilde;o imposs&iacute;vel! <br/> Voc&ecirc; n&atilde;o pode alterar o seu perfil!");
                $(".clMsg").text(ajustarAcentos("Opera&ccedil;&atilde;o imposs&iacute;vel! <br/> Voc&ecirc; n&atilde;o pode alterar o seu perfil!"));
                return;
            }

            this.salvar();
        }

        vmObjeto.limpar = function () {
            this.cancelar();
            CancelaOperacao();
        };

        dados.transport.parameterMap = function (data, type) {
            if (type == "create" || type == "update") {
                var checkedNodes = [],
                    treeView = $("#treeview").data("kendoTreeView");
                checkedNodeIds(treeView.dataSource.view(), checkedNodes);

                if (checkedNodes.length > 0) {
                    data.Permissoes = checkedNodes.join(";");
                }
                data.IdPerfil = selecionado;
                CancelaOperacao();
            }

            return data;
        };

        montaCombo($("#cboPerfil"), "ComboPerfil", undefined, undefined, undefined, undefined, undefined, "Selecione um Perfil...");

        kendo.bind($("#dvForm"), vmObjeto);  // efetiva o bind nos campos

        $("#treeview").kendoTreeView({
            checkboxes: {
                template: "<input type='checkbox'  #= item.Selecionado == 1 ? 'checked' : '' # value='#= item.id #' />",
                checkChildren: true,
            },
            batch: false,
            dataSource: dadosDois,
            loadOnDemand: false,
            dataTextField: ["Nome", "Nome"]
        }).data("kendoTreeView");

        function checkedNodeIds(nodes, checkedNodes, idpai) {
            for (var i = 0; i < nodes.length; i++) {
                if ((nodes[i].checked || (nodes[i].checked == null && nodes[i].Selecionado == 1)) && nodes[i].Filhos.length == 0) {
                    checkedNodes.push(idpai + "_" + nodes[i].Id);
                }

                if (nodes[i].hasChildren) {
                    checkedNodeIds(nodes[i].children.view(), checkedNodes, nodes[i].Id);
                }
            }
        }

    });
</script>
