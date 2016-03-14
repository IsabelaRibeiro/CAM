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
            Sistema
            <br/>
            <select class="k-combobox" id="cboSistema" data-bind="value: selecionado.IdSistema"></select>
        </td>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            Nome
            <br/>
            <input type="text" id="edtNome" name="edtNome" data-bind="value:selecionado.Nome" class="k-textbox"/>
        </td>
    </tr>
    <tr>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            Identificador
            <br/>
            <input type="text" id="edtIdentificador" name="edtIdentificador" class="k-textbox" data-bind="value: selecionado.Identificador"/>
        </td>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            Ordem
            <br/>
            <input id="edtOrdem" name="edtOrdem" data-role="numerictextbox" data-bind="value: selecionado.Ordem" data-format="n0" min="1"/>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="clBotoes">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left;">
                            <input id="edtPesquisar" name="edtPesquisar" class="k-textbox" placeholder="Pesquisar..." onkeypress="PesquisarENTER(event)"/>
                            <input type="button" value="Pesquisar" id="btnPesquisar" class="k-button"/>
                            <img class="excelLayout" title="Exportar para Excel" src="<?php echo URLBASE; ?>Imagens/excel.png" id="export"/>
                        </td>
                        <td>
                            <input type="button" value="Salvar" id="btnSalvar" data-bind="click: validar" class="k-button"/>
                            <input type="button" value="Cancelar" id="btnCancelar" data-bind="click: cancelar" class="k-button"/>
                            <input type="button" value="Excluir" id="btnExcluir" data-bind="click: excluir" class="k-button"/>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
<hr/>
<div id="dvLista">
    <span class="clSubTitulo">M&oacute;dulos j&aacute; cadastrados</span><br/>
    <div id="grdDados"></div>
</div>
</div>

<script>
    /* Defini&ccedil;&atilde;o do modelo */
    modelo = kendo.data.Model.define({
        id: "Id",
        fields: {
            Id: {editable: false, nullable: false, type: "number"},
            IdSistema: {editable: true},
            Sistema: {type: "string"},
            Nome: {type: "string"},
            Ordem: {editable: true, nullable: false, type: "number", defaultValue: 1},
            Identificador: {type: "string"},
            Status: { editable: false,  type: "number", defaultValue: 1 }
        }
    });

    dados = criaDataSource(modelo);

    $("#btnPesquisar").click(function () {
        Pesquisar("#grdDados");
    });

    // assim que carregar a p&aacute;gina
    $(document).ready(function () {
        kendo.culture("pt-BR");

        vmObjeto = criaViewModel();  // instancia o vm

        $("#export").hide();

        $("#export").click(function(e) {
            ExportarXLS();
        });

        vmObjeto.validar = function () {
            if (this.selecionado.IdSistema < 1) {
                MsgAlerta(null, "Selecione o campo Sistema");
                $(".clMsg").text(ajustaAcentosXLS("Selecione o campo Sistema"));
                return;
            } else if (this.selecionado.Nome == '') {
                MsgAlerta(null, "Preencha o campo Nome");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Nome"));
                return;
            } else if (this.selecionado.Identificador == '') {
                MsgAlerta(null, "Preencha o campo Identificador");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Identificador"));
                return;
            } else if (this.selecionado.Ordem == null || this.selecionado.Ordem == "null" || this.selecionado.Ordem < 1 || this.selecionado.Ordem == "") {
                MsgAlerta(null, "Preencha o campo Ordem");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Ordem"));
                return;
            } else {
                this.salvar();
            }
        }

        montaCombo($("#cboSistema"), "ComboSistemas");


        kendo.bind($("#dvForm"), vmObjeto);  // efetiva o bind nos campos

        // a grid precisa ser configurada para cada tela
        $("#grdDados").kendoGrid({
            columns: [
                {field: "Sistema", title: "Sistema", attributes: {style: "text-align: left"}, filterable: { multi: true, search: true }},
                {field: "Nome", title: "Nome", attributes: {style: "text-align: left"}, filterable: { multi: true, search: true }},
                {field: "Identificador", title: "Identificador", attributes: {style: "text-align: left"}, filterable: { multi: true, search: true }},
                {field: "Ordem", title: "Ordem", format: "{0}", attributes: {style: "text-align: right"}},
            ],
            excel: {
                fileName: "Modulos - CAM.xlsx"
            },
            groupable: false,
            sortable: true,
            editable: false,
            filterable: true,
            pageable: true,
            selectable: "row",
            height: 420,
            change: function (e) {
                recuperarRegistro(this.dataItem(this.select()).Id, modelo);
            }
        });
    });
    /* Função que pesquisa através da tecla enter do teclado */
    function PesquisarENTER(e) {
        if (e.keyCode == 13) {
            document.getElementById('btnPesquisar').click();
        }
    }

</script>