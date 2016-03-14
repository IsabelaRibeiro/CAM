<?php if (AbreEmJanela($_d)) {
	$Nome = $_d[0]->Nome == ""?$_SESSION['ssTela']: $_d[0]->Nome;	echo '<div id="dvForm" style="width: 100%; margin-top: -30px;"><div><h3><br/>' . $Nome . '</h3></div>';
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
				Nome
				<br/>
				<input type="text" id="edtNome" name="edtNome" data-bind="value:selecionado.Nome" class="k-textbox"/>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="clBotoes">
					<table style="width: 100%">
						<tr>
							<td style="text-align: left;">
								<input id="edtPesquisar" name="edtPesquisar" placeholder="Pesquisar..." class="k-textbox" onkeypress="PesquisarENTER(event)"/>
			   			 		<input type="button" value="Pesquisar" id="btnPesquisar" class="k-button"/>
								<img class="excelLayout" title="Exportar para Excel" src="<?php echo URLBASE; ?>Imagens/excel.png" id="export"/>
							</td>
							<td>
								<input type="button" value="Salvar"	id="btnSalvar" data-bind="click: validar" class="k-button" />
								<input type="button" value="Cancelar" id="btnCancelar" data-bind="click: cancelar" class="k-button" /> 
								<input type="button" value="Excluir" id="btnExcluir" data-bind="click: excluir" class="k-button" />
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" id="dvLista">
				<div id="dvLista">
					<span class="clSubTitulo">Sistemas j&aacute; cadastrados</span><br />
					<div id="grdDados"></div>
				</div>
			</td>
		</tr>
</table>
</div>
<script>

	/* Definicao do modelo */
	modelo = kendo.data.Model.define({
		id: "Id",
		fields: {
			Id: { editable: false, type: "number", defaultValue: 0 },
			Nome: { type: "string" },
			Status: { editable: false,  type: "number", defaultValue: 1 }
		}
	});

	/* Definicao do datasource */
	dados = criaDataSource(modelo);

	$("#btnPesquisar").click(function() {
		Pesquisar("#grdDados");
	});

	// assim que carregar a pagina
	$(document).ready(function () {
		kendo.culture("pt-BR");

		vmObjeto = criaViewModel();  // instancia o vm

		$("#export").hide();

		$("#export").click(function(e) {
			ExportarXLS();
		});

		vmObjeto.validar = function() {
			if (this.selecionado.Nome == '') {
				MsgAlerta(null, "Preencha o campo Nome");
				$(".clMsg").text(ajustaAcentosXLS("Preencha o campo Nome"));
				return;
			}
			this.salvar();
		};

		kendo.bind($("#dvForm"), vmObjeto);  // efetiva o bind nos campos

		// a grid precisa ser configurada para cada tela
		$("#grdDados").kendoGrid({
			columns: [{ field: "Nome", title: "Nome" , filterable: { multi: true, search: true }}],
			groupable: true,
			excel: {
				fileName: "Sistema - CAM.xlsx"
			},
			sortable: true,
			editable: false,
			filterable: true,
			pageable: false,
			selectable: "row",
			height: 420,
			change: function (e) {
				recuperarRegistro(this.dataItem(this.select()).Id, modelo);
                //vmObjeto.set("selecionado",this.dataItem(this.select()));
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