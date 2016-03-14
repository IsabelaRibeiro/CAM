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
        <td>
            <span class="cpoObrigatorio">*</span>
            Nome Completo
            <br/>
            <input type="text" id="edtNome" name="edtNome" data-bind="value: selecionado.Nome" class="k-textbox"
                   maxlength="250"/>
        </td>
        <td>
            <span class="cpoObrigatorio">*</span>
            Login
            <br/>
            <input type="text" id="edtLogin" name="edtLogin" data-bind="value: selecionado.Login" class="k-textbox"
                   maxlength="50" onblur="validaLogin()"></form>
        </td>
    </tr>
    <tr>
        <td>
            <span class="cpoObrigatorio">*</span>
            Senha
            <br/>
            <input type="password" id="edtSenha" name="edtSenha" data-bind="value: selecionado.Senha" class="k-textbox"
                   maxlength="50"/>
        </td>
        <td>
            For&ccedil;a da Senha
            <br/>
            <div id="passStrength" style="width: 250px;"></div>
        </td>
    </tr>
    <tr>
        <td>
            <span class="cpoObrigatorio">*</span>
            Confirmar Senha
            <br/>
            <input type="password" id="edtConfirmaSenha" name="edtConfirmaSenha"
                   data-bind="value: selecionado.ConfirmaSenha" class="k-textbox" maxlength="50"/>
        </td>
        <td>
            <span class="cpoObrigatorio">*</span>
            Data de Nascimento
            <br/>
            <input name="edtDtNascimento" id="edtDtNascimento" data-bind="value:selecionado.DtNascimento"/>
        </td>
    </tr>
    <tr>
        <td>
            Telefone
            <br/>
            <input name="edtFone" id="edtFone" data-bind="value: selecionado.Telefone" class="k-textbox"
                   data-mask="(00) 0000 0000" data-role="maskedtextbox"/>
        </td>
        <td>
            <span class="cpoObrigatorio">*</span>
            Celular
            <br/>
            <input name="edtCelular" id="edtCelular" data-bind="value:selecionado.Celular" class="k-textbox"
                   data-mask="(00) 0000-0000" data-role="maskedtextbox"/>
        </td>
    </tr>
    <tr>
        <td>
            Email
            <br/>
            <input type="text" id="edtEmail" name="edtEmail" data-bind="value: selecionado.Email" class="k-textbox"
                   maxlength="250"/>
        </td>
        <td>
            <span class="cpoObrigatorio">*</span>
            Perfil
            <br/>
            <select class="k-combobox" id="cboPerfil" name="cboPerfil" data-bind="value: selecionado.IdPerfil"></select>
        </td>
    </tr>
    <tr>
        <td>
            <span class="cpoObrigatorio">*</span>
            Estado
            <br/>
            <select class="k-combobox" id="cboEstado" name="cboEstado" data-bind="value: selecionado.IdEstado"></select>
        </td>
        <td>
            <span class="cpoObrigatorio">*</span>
            Cidade
            <br/>
            <select class="k-combobox" id="cboCidade" name="cboCidade" data-bind="value: selecionado.IdCidade"></select>
        </td>
    </tr>
    <tr>
        <td>
            <span class="cpoObrigatorio">*</span>
            Endere&ccedil;o
            <br/>
            <input type="text" id="edtEndereco" name="edtEndereco" data-bind="value: selecionado.Endereco"
                   class="k-textbox" maxlength="250"/>
        </td>
        <td>
            <span class="cpoObrigatorio">*</span>
            Bairro
            <br/>
            <input type="text" id="edtBairro" name="edtBairro" data-bind="value: selecionado.Bairro" class="k-textbox"
                   maxlength="250"/>
        </td>
    </tr>
    <tr>
        <td>
            <br/>
            <ul class="fieldlist">
                <li>
                    <input type="radio" name="rdbReikiano" id="rdbNaoReikiano" class="k-radio" value="0" checked="checked" data-bind="checked: selecionado.Reikiano">
                    <label class="k-radio-label" for="rdbNaoReikiano">N&atilde;o &eacute; Reikiano</label>
                </li>
                <li>
                    <input type="radio" name="rdbReikiano" id="rdbReikiano1" class="k-radio" value="1" data-bind="checked: selecionado.Reikiano">
                    <label class="k-radio-label" for="rdbReikiano1">Reikiano N&iacute;vel 01</label>
                </li>
                <li>
                    <input type="radio" name="rdbReikiano" id="rdbReikiano2" class="k-radio" value="2" data-bind="checked: selecionado.Reikiano">
                    <label class="k-radio-label" for="rdbReikiano2">Reikiano N&iacute;vel 02</label>
                </li>
                <li>
                    <input type="radio" name="rdbReikiano" id="rdbReikiano3" class="k-radio" value="3" data-bind="checked: selecionado.Reikiano">
                    <label class="k-radio-label" for="rdbReikiano3">Reikiano N&iacute;vel 03</label>
                </li>
            </ul>

        </td>
        <td>
            Observa&ccedil;&otilde;es
            <br/>
            <textarea name="edtObservacao" id="edtObservacao" data-bind="value:selecionado.Observacao" rows="2"
                      class="k-textbox"></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="clBotoes">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left;">
                            <input id="edtPesquisar" name="edtPesquisar" class="k-textbox" placeholder="Pesquisar..."
                                   onkeypress="PesquisarENTER(event)"/>
                            <input type="button" value="Pesquisar" id="btnPesquisar" class="k-button"/>
                            <img class="excelLayout" title="Exportar para Excel"
                                 src="<?php echo URLBASE; ?>Imagens/excel.png" id="export"/>
                        </td>
                        <td>
                            <input type="button" value="Salvar" id="btnSalvar" data-bind="click: validar"
                                   class="k-button"/>
                            <input type="button" value="Cancelar" id="btnCancelar" data-bind="click: cancelar"
                                   class="k-button"/>
                            <input type="button" value="Excluir" id="btnExcluir" data-bind="click: excluir"
                                   class="k-button"/>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
<hr/>
<div id="dvLista">
    <span class="clSubTitulo">Usu&aacute;rios j&aacute; cadastrados</span><br/>

    <div id="grdDados"></div>
</div>
</div>
<script>

    var forcaSenha = 0;

    /* Definicao do modelo */
    modelo = kendo.data.Model.define({
        id: "Id",
        fields: {
            Id: {editable: false, defaultValue: 0},
            IdPerfil: {editable: true},
            Perfil: {editable: true},
            Login: {editable: true},
            Senha: {editable: true},
            DtNascimento: {editable: true, type: "date"},
            Telefone: {editable: true},
            Celular: {editable: true},
            Email: {editable: true},
            Endereco: {editable: true},
            IdCidade: {editable: true},
            IdEstado: {editable: true},
            Bairro: {editable: true},
            Reikiano: {editable: true, type: "number", defaultValue: 0},
            Observacao: {editable: true},
            Status: {editable: false, type: "number", defaultValue: 1}
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

        $("#export").click(function (e) {
            ExportarXLS();
        });

        vmObjeto.validar = function () {

            FormataData();

            if (this.selecionado.Login == '') {
                MsgAlerta(null, "Informe o Login");
                $(".clMsg").text("Informe o Login");
                return;
            }

            if (this.selecionado.Email == '') {
                MsgAlerta(null, "Informe o Email");
                $(".clMsg").text("Informe o Email");
                return;
            }

            if (this.selecionado.Nome == '') {
                MsgAlerta(null, "Informe o Nome");
                $(".clMsg").text("Informe o Nome");
                return;
            }

            if (this.selecionado.Id < 0) {
                if (this.selecionado.Senha == null) {
                    MsgAlerta(null, "Preencha o campo Senha");
                    $(".clMsg").text("Preencha o campo Senha");
                    return;
                }

                if (this.selecionado.ConfirmaSenha == null) {
                    MsgAlerta(null, "Preencha o campo Confirma Senha");
                    $(".clMsg").text("Preencha o campo Confirma Senha");
                    return;
                }

                if (this.selecionado.Senha != this.selecionado.ConfirmaSenha) {
                    MsgAlerta(null, "A senha digitada no campo Confirmar Senha deve ser igual ao campo Senha");
                    $(".clMsg").text("A senha digitada no campo Confirmar Senha deve ser igual ao campo Senha");
                    return;
                }
            }

            if (this.selecionado.Senha != '' && this.selecionado.Senha != null && forcaSenha < 20) {
                MsgAlerta(null, "A senha informada n&atilde;o atende os padr&otilde;es m&iacute;nimos. A senha deve ter no m&iacutenimo 6 d&iacute;gitos, 1 letra e 1 n&uacute;mero!");
                $(".clMsg").text(ajustaAcentosXLS("A senha informada n&atilde;o atende os padr&otilde;es m&iacute;nimos. A senha deve ter no m&iacute;nimo 6 d&iacute;gitos, 1 letra e 1 n&uacute;mero!"));
                return;
            }

            if (this.selecionado.IdPerfil < 1) {
                MsgAlerta(null, "Selecione o campo Perfil");
                $(".clMsg").text("Selecione o campo Perfil");
                return;
            }

            this.salvar();
            passProgress.value(0);
        }

        montaListCombo($("#cboPerfil"), "ComboPerfil");
        montaListCombo($("#cboEstado"), "ComboEstados");
        montaListCombo($("#cboCidade"), "ComboCidades", "cboEstado", vmObjeto.selecionado.IdEstado, "vmObjeto.selecionado.IdCidade");
        $("#edtDtNascimento").kendoDatePicker({format: "dd/MM/yyyy"});

        passProgress = $("#passStrength").kendoProgressBar({
            type: "value",
            max: 40,
            animation: false,
            change: onChangeSenha
        }).data("kendoProgressBar");

        passProgress.progressStatus.text("Fraca");

        $("#edtSenha").keyup(function () {
            passProgress.value(this.value.length);
        });

        kendo.bind($("#dvForm"), vmObjeto);  // efetiva o bind nos campos

        // a grid precisa ser configurada para cada tela
        $("#grdDados").kendoGrid({
            columns: [
                {field: "Nome", title: "Nome", filterable: {multi: true, search: true}},
                {field: "Login", title: "Login", filterable: {multi: true, search: true}},
                {field: "Telefone", title: "Telefone", filterable: {multi: true, search: true}},
                {field: "Celular", title: "Celular", filterable: {multi: true, search: true}},
                {field: "Perfil", title: "Perfil", filterable: {multi: true, search: true}}
            ],
            groupable: true,
            excel: {
                fileName: "Cadastro de Usuarios - CAM.xlsx"
            },
            sortable: true,
            editable: false,
            filterable: true,
            pageable: false,
            selectable: "row",
            height: 420,
            change: function (e) {
                $(".clMsg").text("");
                recuperarRegistro(this.dataItem(this.select()).Id, modelo);
            }
        });
    });

    function onChangeSenha() {
        this.progressWrapper.css({
            "background-image": "none",
            "border-image": "none"
        });

        forcaSenha = verificaForcaSenha($("#edtSenha").val());

        if (forcaSenha == 40) {
            //Excelente
            passProgress.value(forcaSenha);
            this.progressWrapper.css({
                "background-color": "#43d443"
            });
            this.progressStatus.text("Excelente");
        } else if (forcaSenha == 30) {
            //Forte
            passProgress.value(forcaSenha);
            this.progressWrapper.css({
                "background-color": "#73afe5"
            });
            this.progressStatus.text("Forte");
        } else if (forcaSenha == 20) {
            //Justa
            passProgress.value(forcaSenha);
            this.progressWrapper.css({
                "background-color": "#f6f65c"
            });
            this.progressStatus.text("Justa");
        } else {
            //fraca
            passProgress.value(forcaSenha);
            this.progressWrapper.css({
                "background-color": "#f65c5c"
            });
            this.progressStatus.text("Fraca");
        }
    }

    function validaLogin() {
        Duplicado("ID", "USUARIO", vmObjeto.selecionado.Login, "Digite outro login pois este j&aacute; consta no sistema");
    }


    function Duplicado(campo, tabela, valor, msg) {
        var retorno;
        $.ajax({
            type: "POST",
            data: {
                MODULO: "<?=$_POST["MODULO"]?>",
                TELA: "<?=$_POST["TELA"]?>",
                campo: campo,
                tabela: tabela,
                valor: valor
            },
            url: urlBase + "/principal.php?ACAO=ValidarDuplicacao",
            dataType: "json",
            success: function (result) {
                if (result.Texto == 1) {
                    MsgAlerta(null, msg);
                    retorno = true;
                } else {
                    retorno = false;
                }
            },
            beforeSend: function () {
                $(".clCarregando").show();
            },
            complete: function (msg) {
                $(".clCarregando").hide();

            }
        });
        return retorno;
    }

    function FormataData() {
        dados.transport.parameterMap = function (data, type) {
            if (type == "create" || type == "update" || type == "read") {
                data.DtNascimento = kendo.toString(data.DtNascimento, "yyyy-MM-dd");
            }
            return data;
        };
    }

    /* Função que pesquisa através da tecla enter do teclado */
    function PesquisarENTER(e) {
        if (e.keyCode == 13) {
            document.getElementById('btnPesquisar').click();
        }
    }

</script>