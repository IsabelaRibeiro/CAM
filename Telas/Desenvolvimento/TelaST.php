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
            M&oacute;dulo
            <br/>
            <select class="k-combobox" id="cboModulo" data-bind="value: selecionado.IdModulo" disabled="disabled"></select>
        </td>
    </tr>
    <tr>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            Nome
            <br/>
            <input type="text" id="edtNome" name="edtNome" data-bind="value:selecionado.Nome" class="k-textbox"/>
        </td>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            Identificador
            <br/>
            <input type="text" id="edtIdentificador" name="edtIdentificador" data-bind="value:selecionado.Identificador" class="k-textbox"/>
        </td>
    </tr>
    <tr>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            Ordem
            <br/>
            <input id="edtOrdem" name="edtOrdem" data-role="numerictextbox" data-bind="value: selecionado.Ordem" data-format="n0" class="bootstrap-timepicker" min="1"/>
        </td>
        <td class="clLegenda">
            <span class="cpoObrigatorio">*</span>
            Dimens&otilde;es (largura x altura)
            <br/>
            <input id="edtLargura" min="400" name="edtLargura" data-role="numerictextbox" data-bind="value: selecionado.Largura" data-format="n0" style="width: 80px;"/> x
            <input id="edtAltura" min="400" name="edtAltura" data-role="numerictextbox" data-bind="value: selecionado.Altura" data-format="n0" style="width: 80px;"/>
        </td>
    </tr>
    <tr>
        <td>
            <br/>
            <input type="checkbox" name="chkJanela" id="chkJanela" class="k-checkbox" data-bind="checked: selecionado.Janela"/>
            <label class="k-checkbox-label" for="chkJanela">Abertura em Janela</label>
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
    <span class="clSubTitulo">Telas j&aacute; cadastradas</span><br/>
    <div id="grdDados"></div>
<script>

    /* Defini&ccedil;&atilde;o do modelo */
    modelo = kendo.data.Model.define({
        id: "Id",
        fields: {
            Id: {editable: false, type: "number"},
            IdSistema: {editable: true},
            Funcionalidades: {editable: true},
            Sistema: {type: "string"},
            IdModulo: {editable: true},
            Modulo: {type: "string"},
            Nome: {type: "string"},
            Identificador: {type: "string"},
            Ordem: {editable: true, nullable: false, type: "number", defaultValue: 1},
            Janela: {editable: true, defaultValue: true},
            Largura: {editable: true, defaultValue: 600},
            Altura: {editable: true, defaultValue: 400},
            Status: { editable: false,  type: "number", defaultValue: 1 }
        }
    });

    /* Defini&ccedil;&atilde;o do datasource */
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
                MsgAlerta(null, "Selecione um Sistema");
                $(".clMsg").text(ajustaAcentosXLS("Selecione um Sistema"));
                return;
            }
            if (this.selecionado.IdModulo < 1) {
                MsgAlerta(null, "Selecione um M&oacute;dulo");
                $(".clMsg").text(ajustaAcentosXLS("Selecione um M&oacute;dulo"));
                return;
            }
            if (this.selecionado.Nome == '') {
                MsgAlerta(null, "Preencha o campo Nome");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Nome"));
                return;
            }
            if (this.selecionado.Identificador == '') {
                MsgAlerta(null, "Preencha o campo Identificador");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Identificador"));
                return;
            }
            if (this.selecionado.Ordem == null || this.selecionado.Ordem == "null" || this.selecionado.Ordem < 1 || this.selecionado.Ordem == "") {
                MsgAlerta(null, "Preencha o campo Ordem");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Ordem"));
                return;
            }
            if (this.selecionado.Altura < 0) {
                MsgAlerta(null, "Preencha o campo Altura");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Altura"));
                return;
            }
            if (this.selecionado.Largura < 0) {
                MsgAlerta(null, "Preencha o campo Largura");
                $(".clMsg").text(ajustaAcentosXLS("Preencha o campo Largura"));
                return;
            }
            this.salvar();

        }

        montaCombo($("#cboSistema"), "ComboSistemas");
        montaCombo($("#cboModulo"), "ComboModulos", "cboSistema", vmObjeto.selecionado.IdSistema, "vmObjeto.selecionado.IdModulo");

        kendo.bind($("#dvForm"), vmObjeto);  // efetiva o bind nos campos

        // a grid precisa ser configurada para cada tela
        $("#grdDados").kendoGrid({
            columns: [{field: "Sistema", title: "Sistema", filterable: { multi: true, search: true }},
                {field: "Modulo", title: "M&oacute;dulo", filterable: { multi: true, search: true }},
                {field: "Nome", title: "Nome", filterable: { multi: true, search: true }},
                {field: "Identificador", title: "Identificador", filterable: { multi: true, search: true }},
                {field: "Ordem", title: "Ordem"}
            ],
            excel: {
                fileName: "Modulos - CAM.xlsx"
            },
            groupable: true,
            sortable: true,
            editable: false,
            filterable: true,
            pageable: true,
            selectable: "row",
            height: 420,
            change: function (e) {
                //vmObjeto.set("selecionado", this.dataItem(this.select()));
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