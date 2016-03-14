// declaraÔøΩÔøΩo das variaveis globais
var dados; // variÔøΩvel para conter o dataSource principal da tela
var vmObjeto; // objeto de ViewModel para controle de binds
var validador; // validator para os campos
var modelo; // modelo de objeto a ser trabalhado na tela
var titulo;
var recuperado = false;

// configura a cultura como brasileira
kendo.culture("pt-BR");

// cria o objeto observ&aacute;vel para o padr&atilde;o MVVM da Kendo, recebendo com parametro o modelo de objeto que ser&aacute; controlado na tela
function criaViewModel() {
  return new kendo.observable({
    registros: dados,
    selecionado: new modelo(),
    salvar: function () {
      $(".clMsg").text("");

      if (this.selecionado.Id == 0) {
        if (this.registros.get(0) != undefined) {
          this.registros.remove(this.registros.get(0));
        }
        this.registros.add(this.selecionado);
      } else {
        if (recuperado) {
          var indice = this.registros.indexOf(this.registros.get(this.selecionado.Id));
          var registro = this.registros.data()[indice];
          for (var _propriedade in modelo.fields) {
            registro.set(_propriedade, this.selecionado[_propriedade]);
          }
        }
      }
      this.registros.sync();
    },
    cancelar: function () {
      $(".clMsg").text("");
      this.registros.cancelChanges();
      this.set("selecionado", new modelo());
    },
    excluir: function () {
      if (this.selecionado != null && this.selecionado.Id > 0) {
        MsgPergunta(null, "Confirma a exclus&atilde;o do registro?", Excluir);
      }
    },
  });
}


function Excluir() {
  vmObjeto.registros.remove(vmObjeto.registros.get(vmObjeto.selecionado.Id));
  vmObjeto.registros.sync();
}

// cria o datasource para conectar ao serviÔøΩo JSON
function criaDataSource(aModelo, aTotalizador, aPageSize, aValor, aDataInicial, aDataFinal, aId) {
  aPageSize = 'TodosRegistros';
  if (aValor == undefined || aValor == null || aValor == 'null') {
    aValor = '####';
  }
  if (aDataInicial == undefined || aDataInicial == null || aDataInicial == 'null') {
    aDataInicial = '####';
  }
  if (aDataFinal == undefined || aDataFinal == null || aDataFinal == 'null') {
    aDataFinal = '####';
  }
  if (aPageSize == 'TodosRegistros') {
    return new kendo.data.DataSource({
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=Listar",
          dataType: "json",
          type: "POST",
          data: {
            MODULO: modulo,
            TELA: tela,
            VALOR: aValor,
            DATAINI: aDataInicial,
            DATAFIM: aDataFinal,
            Id: aId
          }
        },
        create: {
          url: urlBase + "/principal.php?ACAO=Cadastrar",
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela}
        },
        update: {
          url: urlBase + "/principal.php?ACAO=Alterar",
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela}
        },
        destroy: {
          url: urlBase + "/principal.php?ACAO=Excluir",
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela}
        }
      },
      // daqui para cima precisa atualizar para cada nova tela
      batch: false,
      schema: {model: aModelo},
      aggregate: aTotalizador,
      requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        var type = e.type;
        if (type == "create" || type == "update" || type == "destroy") {
          this.read();
          $(".clMsg").text(e.response.Texto);
          var msg = e.response.Texto;
          if (msg.indexOf("erro") > 0)
            MsgErro(e, msg);
          else
            MsgInforma(e, msg);
          if (e.response.Tipo) {
            vmObjeto.set("selecionado", new modelo());
          }
        } else if (type == 'read' && e.response != undefined && e.response[0] != undefined && e.response[0].Id > 0) {
          $("#export").show();
        }

        $(".clCarregando").hide();
      }
    });
  } else {
    return new kendo.data.DataSource({
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=Listar",
          dataType: "json",
          type: "POST",
          data: {
            MODULO: modulo,
            TELA: tela,
            VALOR: aValor,
            DATAINI: aDataInicial,
            DATAFIM: aDataFinal,
            Id: aId
          }
        },
        create: {
          url: urlBase + "/principal.php?ACAO=Cadastrar",
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela}
        },
        update: {
          url: urlBase + "/principal.php?ACAO=Alterar",
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela}
        },
        destroy: {
          url: urlBase + "/principal.php?ACAO=Excluir",
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela}
        },
      },
      // daqui para cima precisa atualizar para cada nova tela
      batch: false,
      schema: {model: aModelo},
      pageSize: aPageSize,
      aggregate: aTotalizador,
      requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        var type = e.type;
        if (type == "create" || type == "update" || type == "destroy") {
          this.read();
          $(".clMsg").text(e.response.Texto);
          var msg = e.response.Texto;
          if (msg.indexOf("erro") > 0)
            MsgErro(e, msg);
          else
            MsgInforma(e, msg);
          if (e.response.Tipo) {
            vmObjeto.set("selecionado", new modelo());
          }
        } else if (type == 'read' && e.response != undefined && e.response[0] != undefined && e.response[0].Id > 0) {
          $("#export").show();
        }

        $(".clCarregando").hide();
      }
    });
  }
}

function criaDataSourceFiltro(aModelo, aTotalizador, atipo, aId) {
  return new kendo.data.DataSource({
    transport: {
      read: {
        url: urlBase + "/principal.php?ACAO=Listar" + atipo,
        dataType: "json",
        type: "POST",
        data: {MODULO: modulo, TELA: tela, ID: aId}
      }
    },
    batch: false,
    schema: {model: aModelo},
    aggregate: aTotalizador,
    requestStart: function (e) {
      $(".clCarregando").show();
    },
    requestEnd: function (e) {
      $(".clCarregando").hide();
    }
  });
}

function retornaSessao(aId) {
  return new kendo.data.DataSource({
    transport: {
      read: {
        url: urlBase + "/principal.php?ACAO=RetornaSessao",
        dataType: "json",
        type: "POST",
        data: {MODULO: modulo, TELA: tela, ID: aId}
      }
    },
    batch: false,
    requestStart: function (e) {
      $(".clCarregando").show();
    },
    requestEnd: function (e) {
      $(".clCarregando").hide();
    }
  });
}

function recuperarRegistro(aId, modeloobsoleto, atributo, acao, funcao) {
  if (atributo === undefined)
    atributo = "selecionado"
  if (acao === undefined)
    acao = "Recuperar"
  var dsRecuperar = new kendo.data.DataSource({
    transport: {
      read: {
        url: urlBase + "/principal.php?ACAO=" + acao,
        dataType: "json",
        type: "POST",
        data: {MODULO: modulo, TELA: tela, ID: aId}
      }
    },
    batch: false,
    schema: {model: modelo},
    requestStart: function (e) {
      $(".clCarregando").show();
    },
    requestEnd: function (e) {
      recuperado = true;
      vmObjeto.set(atributo, e.response);
      if (funcao != undefined && funcao != "")
        funcao();
      $(".clCarregando").hide();
    }
  });

  dsRecuperar.fetch();
}

function recuperarCNPJCPF(aCNPJ) {
  var dsRecuperar = new kendo.data.DataSource({
    transport: {
      read: {
        url: urlBase + "/principal.php?ACAO=RecuperarCNPJCPF",
        dataType: "json",
        type: "POST",
        data: {MODULO: "Cadastros", TELA: "Entidade", ID: aCNPJ}
      }
    },
    batch: false,
    schema: {model: modelo},
    requestStart: function (e) {
      $(".clCarregando").show();
    },
    requestEnd: function (e) {
      if (e.response.Id > 0) {
        recuperado = true;
        vmObjeto.set("selecionado", e.response);
      } else {
        $(".clCarregando").hide();
      }
    }
  });

  dsRecuperar.fetch();
}

function RecuperarPorCodigo(aCodigo) {
  var dsRecuperar = new kendo.data.DataSource({
    transport: {
      read: {
        url: urlBase + "/principal.php?ACAO=RecuperarPorCodigo",
        dataType: "json",
        type: "POST",
        data: {MODULO: "Distribuicao", TELA: "Nota", ID: aCodigo}
      }
    },
    batch: false,
    schema: {model: modelo},
    requestStart: function (e) {
      $(".clCarregando").show();
    },
    requestEnd: function (e) {
      recuperado = true;
      vmObjeto.set("selecionado", e.response);
      $(".clCarregando").hide();
    }
  });

  dsRecuperar.fetch();
}

function montaCombo(aObj, aAcao, aCbPai, aId, aSelecionado, auto, onchange) {
  var cbo = aObj.kendoComboBox({
    autoBind: auto == undefined ? true : auto,
    cascadeFrom: aCbPai,
    dataTextField: "Nome",
    placeholder: "Selecione...",
    change: function (e) {
      if (this.value() && this.selectedIndex < 0) {
        this.text('');
        this._selectItem();
      }
      if (onchange != undefined) {
        onchange();
      }
    },
    dataValueField: "Id",
    dataSource: {
      serverFiltering: true,
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=" + aAcao,
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela, Id: aId}
        },
      }, requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        $(".clCarregando").hide();
      }
    }
  }).data("kendoComboBox");

  return cbo;
}

function montaListCombo(aObj, aAcao, aCbPai, aId, aSelecionado, auto, onchange, textoOption) {
  textoOption = (textoOption == undefined || textoOption == null) ? "Selecione..." : ajustaAcentosXLS(textoOption);
  var cbo = aObj.kendoDropDownList({
    autoBind: auto == undefined ? true : auto,
    cascadeFrom: aCbPai,
    dataTextField: "Nome",
    change: onchange,
    dataValueField: "Id",
    optionLabel: {
      Nome: textoOption,
      Id: 0
    },
    dataSource: {
      serverFiltering: true,
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=" + aAcao,
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela, Id: aId}
        },
      }, requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        $(".clCarregando").hide();
      }
    }
  }).data("kendoDropDownList");

  return cbo;
}

function montaListComboIco(aObj, aAcao, aCbPai, aId, aSelecionado, auto, templateTela, onchange, imgSelecione) {
  var cbo = aObj.kendoDropDownList({
    autoBind: auto == undefined ? true : auto,
    cascadeFrom: aCbPai,
    dataTextField: "Nome",
    change: onchange,
    dataValueField: "Id",
    optionLabel: {
      Nome: "Selecione...",
      Id: 0,
    },
    dataSource: {
      serverFiltering: true,
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=" + aAcao,
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela, Id: aId}
        },
      }, requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        $(".clCarregando").hide();
      }
    },
    valueTemplate: templateTela,
    template: kendo.template(templateTela),
  }).data("kendoDropDownList");

  return cbo;
}


function montaComboIco(aObj, aAcao, aCbPai, aId, aSelecionado, auto, template, onchange) {
  var cbo = aObj.kendoComboBox({
    autoBind: auto == undefined ? true : auto,
    cascadeFrom: aCbPai,
    dataTextField: "Nome",
    placeholder: "Selecione...",
    change: function (e) {
      if (this.value() && this.selectedIndex < 0) {
        this.text('');
        this._selectItem();
      }
      if (onchange != undefined) {
        onchange();
      }
    },
    dataValueField: "Id",
    dataSource: {
      serverFiltering: true,
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=" + aAcao,
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela, Id: aId}
        },
      }, requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        $(".clCarregando").hide();
      }
    },
    valueTemplate: template,
    template: kendo.template(template),
  }).data("kendoComboBox");

  return cbo;
}

function montaListView(aObj, aAcao, aCbPai, aId, aSelecionado) {
  var cbo = aObj.kendoListView({
    dataSource: {
      serverFiltering: true,
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=" + aAcao,
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela, Id: aId}
        },
      }, requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        $(".clCarregando").hide();
      }
    },
    template: kendo.template($("#template").html())
  }).data("kendoListView");

  return cbo;
}

function montaMultiSelect(aObj, aAcao, aId, aSelecionado) {
  var ms = aObj.kendoMultiSelect({
    autoBind: true,
    filter: "contains",
    placeholder: "Selecione...",
    dataTextField: "Nome",
    dataValueField: "Id",
    dataSource: {
      serverFiltering: false,
      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=" + aAcao,
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela, Id: aId}
        },
      }, requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        $(".clCarregando").hide();
      }
    }
  });
  return ms;
}

function montaAutoComplete(aObj, aAcao, aCbPai, aId, aSelecionado) {
  var ac = aObj.kendoAutoComplete({
    autoBind: true,
    filter: "startswith",
    cascadeFrom: aCbPai,
    dataTextField: "Id",
    highlightFirst: true,
    placeholder: "Buscar...",
    dataSource: {

      transport: {
        read: {
          url: urlBase + "/principal.php?ACAO=" + aAcao,
          dataType: "json",
          type: "POST",
          data: {MODULO: modulo, TELA: tela, Id: aId}
        },
      }, requestStart: function (e) {
        $(".clCarregando").show();
      },
      requestEnd: function (e) {
        $(".clCarregando").hide();
      }
    }
  });
  return ac;
}

function MsgAlerta(e, msg) {
  kendo.ui.ExtAlertDialog.show({
    title: "Informativo",
    message: kendo.format(msg),
    icon: "k-ext-warning"
  });
}

function MsgPergunta(e, msg, callback) {
  var defer = $.Deferred();
  kendo.ui.ExtOkCancelDialog.show({
    title: "Confirme...",
    message: kendo.format(msg),
    icon: "k-ext-question"
  }).done(function (response) {
    if (response.button == "OK") {
      callback();
    }
    defer.resolve(response.button == "OK")
  });
  return defer.promise();
}

function MsgInput(e, msg) {
  var defer = $.Deferred();
  kendo.ui.ExtInputDialog.show({
    title: "Confirme...",
    message: kendo.format(msg),
    icon: "k-ext-question"
  }).done(function (response) {
    if (response.button == "OK") {
      defer.resolve(response.button + ":" + response.input);
    } else {
      defer.resolve("");
    }
  });
  return defer.promise();
}

function MsgInforma(e, msg) {
  kendo.ui.ExtAlertDialog.show({
    title: "Informativo",
    message: kendo.format(msg),
    icon: "k-ext-information"
  });
}

function MsgAjuda(e, msg) {
  kendo.ui.ExtHelpDialog.show({
    title: "Ajuda",
    message: kendo.format(msg),
    icon: "k-ext-question",
  });
}

function MsgErro(e, msg) {
  kendo.ui.ExtAlertDialog.show({
    title: "Erro",
    message: kendo.format(msg),
    icon: "k-ext-error"
  });
}


function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds) {
      break;
    }
  }
}

function TrocaMes(mes) { //recebe o m√™s por escrito e retorna o n√∫mero do m√™s
  if (mes == 'Jan' || mes == 'Janeiro' || mes == 'January') {
    return 1;
  }
  if (mes == 'Fev' || mes == 'Fevereiro' || mes == 'February' || mes == 'Feb') {
    return 2;
  }
  if (mes == 'Mar' || mes == 'Mar√ßo') {
    return 3;
  }
  if (mes == 'Abr' || mes == 'Abril' || mes == 'April' || mes == 'Apr') {
    return 4;
  }
  if (mes == 'Maio' || mes == 'Maio' || mes == 'May' || mes == 'May') {
    return 5;
  }
  if (mes == 'Jun' || mes == 'Junho' || mes == 'June') {
    return 6;
  }
  if (mes == 'Jul' || mes == 'Julho' || mes == 'July') {
    return 7;
  }
  if (mes == 'Ago' || mes == 'Agosto' || mes == 'August' || mes == 'Aug') {
    return 8;
  }
  if (mes == 'Set' || mes == 'Setembro' || mes == 'September' || mes == 'Sep') {
    return 9;
  }
  if (mes == 'Out' || mes == 'Outubro' || mes == 'October' || mes == 'Oct') {
    return 10;
  }
  if (mes == 'Nov' || mes == 'Novembro' || mes == 'November') {
    return 11;
  }
  if (mes == 'Dez' || mes == 'Dezembro' || mes == 'December' || mes == 'Dec') {
    return 12;
  }
}


/* funciona para datas que chegam com o tipo "string", se vier em formato de objeto utilize a fun√ß√£o FormataDataObjeto()
 Formata de: Tue Oct 07 2014 04:41:17 GMT-0300 (Hora oficial do Brasil)	Para: 07/10/2014 04:41:17*/

function FormataDataCompleta(data) {
  mes = data.substr(4, 3);
  mes = TrocaMes(mes);
  dia = data.substr(8, 2);
  ano = data.substr(11, 4);
  hora = data.substr(16, 8);

  resultado = dia + "/" + mes + "/" + ano + " " + hora;

  return resultado;
}

function FormataDataObjeto(data) {
  mes = data.getMonth() < 10 ? "0" + data.getMonth() : data.getMonth();
  dia = data.getDate() < 10 ? "0" + data.getDate() : data.getDate();
  ano = data.getYear() < 10 ? "0" + data.getYear() : data.getYear();
  hora = (data.getHours() < 10 ? "0" + data.getHours() : data.getHours()) + ":" + (data.getMinutes() < 10 ? "0" + data.getMinutes() : data.getMinutes()) + ":" + (data.getSeconds() < 10 ? "0" + data.getSeconds() : data.getSeconds());

  resultado = dia + "/" + mes + "/" + ano + " " + hora;

  return resultado;
}

function FormataDataBanco(data) { // Formata de: 07/10/2014 04:41:17	Para: 2014-10-07 04:41:47
  if (data != null || data != "") {
    data = data.substr(6, 4) + "-" + data.substr(3, 2) + "-" + data.substr(0, 2) + " " + data.substr(11, 5);
    return data;
  }
}


function datediff(fromDate, toDate, interval) {
  /*
   * DateFormat month/day/year hh:mm:ss
   * ex.
   * datediff('01/01/2011 12:00:00','01/01/2011 13:30:00','minutes');
   */
  var second = 1000, minute = second * 60, hour = minute * 60, day = hour * 24, week = day * 7;
  fromDate = new Date(fromDate);
  toDate = new Date(toDate);
  var timediff = toDate - fromDate;
  if (isNaN(timediff)) return NaN;
  switch (interval) {
    case "years":
      return toDate.getFullYear() - fromDate.getFullYear();
    case "months":
      return (
          ( toDate.getFullYear() * 12 + toDate.getMonth() )
          -
          ( fromDate.getFullYear() * 12 + fromDate.getMonth() )
      );
    case "weeks"  :
      return Math.floor(timediff / week);
    case "days"   :
      return Math.floor(timediff / day);
    case "hours"  :
      return Math.floor(timediff / hour);
    case "minutes":
      return Math.floor(timediff / minute);
    case "seconds":
      return Math.floor(timediff / second);
    default:
      return undefined;
  }
}

function strpad(x) {
  return (x < 0 || x >= 10 ? "" : "0") + x
}

function Validador(campo, operador, valNaoAceito, alerta, id) {

  var result;
  switch (operador) {
    case "==":
      result = (campo == valNaoAceito) ? true : false;
      break;
    case  "=":
      result = (campo == valNaoAceito) ? true : false;
      break;
    case "!=":
      result = (campo != valNaoAceito) ? true : false;
      break;
    case ">":
      result = (campo > valNaoAceito) ? true : false;
      break;
    case "<":
      result = (campo < valNaoAceito) ? true : false;
      break;
    case ">=":
      result = (campo >= valNaoAceito) ? true : false;
      break;
    case "<=":
      result = (campo <= valNaoAceito) ? true : false;
      break;
  }
  if (result == true) {
    if (id != null) {
      $(id).css({"color": "red", "font-weight": "bold"});
    }
    if (alerta != null) {
      MsgAlerta(null, alerta);
    }
  } else {
    if (id != null) {
      $(id).css({"color": "", "font-weight": ""});
    }
  }
  return result;
}

function PesquisarEnter(e) {
  if (e.keyCode == 13) {
    document.getElementById('btnPesquisar').click();
  }
}

function ExportarXLS(nomeGrid) {
  nomeGrid = nomeGrid == undefined ? "#grdDados" : nomeGrid;
  var grid = $(nomeGrid).data("kendoGrid");
  grid.bind("excelExport", function (e) {
    var rows = e.workbook.sheets[0].rows;
    for (var ri = 0; ri < rows.length; ri++) {
      var row = rows[ri];
      var dd = '';
      if (row.type == "group-header" || row.type == "header" || row.type == "group-footer" || row.type == "footer") {
        for (var ci = 0; ci < row.cells.length; ci++) {
          var cell = row.cells[ci];
          if (cell.value) {
            cell.value = ajustaAcentosXLS(cell.value);
          }
        }
      }
    }
  });
  grid.saveAsExcel();
}

function Pesquisar(nomeGrid) {
  nomeGrid = nomeGrid == undefined ? "#grdDados" : nomeGrid;
  dados = null;
  $("#export").hide();
  var valor = $("#edtPesquisar").val();
  dados = criaDataSource(modelo, undefined, undefined, valor);  // instancia o vm
  //dados.read();
  var grid = $(nomeGrid).data("kendoGrid");
  grid.setDataSource(dados);
  vmObjeto.set("registros", dados);
};

function ajustaAcentosXLS(aTXT) {
  aTXT = aTXT.replace(/<.*?>/g, '');
  aTXT = aTXT.replace(/&aacute;/g, '\u00e1');
  aTXT = aTXT.replace(/&agrave;/g, '\u00e0');
  aTXT = aTXT.replace(/&acirc;/g, '\u00e2');
  aTXT = aTXT.replace(/&atilde;/g, '\u00e3');
  aTXT = aTXT.replace(/&auml;/g, '\u00e4');
  aTXT = aTXT.replace(/&Aacute;/g, '\u00c1');
  aTXT = aTXT.replace(/&Agrave;/g, '\u00c0');
  aTXT = aTXT.replace(/&Acirc;/g, '\u00c2');
  aTXT = aTXT.replace(/&Atilde;/g, '\u00c3');
  aTXT = aTXT.replace(/&Auml;/g, '\u00c4');
  aTXT = aTXT.replace(/&eacute;/g, '\u00e9');
  aTXT = aTXT.replace(/&egrave;/g, '\u00e8');
  aTXT = aTXT.replace(/&ecirc;/g, '\u00ea');
  aTXT = aTXT.replace(/&Eacute;/g, '\u00c9');
  aTXT = aTXT.replace(/&Egrave;/g, '\u00c8');
  aTXT = aTXT.replace(/&Ecirc;/g, '\u00ca');
  aTXT = aTXT.replace(/&Euml;/g, '\u00cb');
  aTXT = aTXT.replace(/&iacute;/g, '\u00ed');
  aTXT = aTXT.replace(/&igrave;/g, '\u00ec');
  aTXT = aTXT.replace(/&icirc;/g, '\u00ee');
  aTXT = aTXT.replace(/&iuml;/g, '\u00ef');
  aTXT = aTXT.replace(/&Iacute;/g, '\u00cd');
  aTXT = aTXT.replace(/&Igrave;/g, '\u00cc');
  aTXT = aTXT.replace(/&Icirc;/g, '\u00ce');
  aTXT = aTXT.replace(/&Iuml;/g, '\u00cf');
  aTXT = aTXT.replace(/&oacute;/g, '\u00f3');
  aTXT = aTXT.replace(/&ograve;/g, '\u00f2');
  aTXT = aTXT.replace(/&ocirc;/g, '\u00f4');
  aTXT = aTXT.replace(/&otilde;/g, '\u00f5');
  aTXT = aTXT.replace(/&ouml;/g, '\u00f6');
  aTXT = aTXT.replace(/&Oacute;/g, '\u00d3');
  aTXT = aTXT.replace(/&Ograve;/g, '\u00d2');
  aTXT = aTXT.replace(/&Ocirc;/g, '\u00d4');
  aTXT = aTXT.replace(/&Otilde;/g, '\u00d5');
  aTXT = aTXT.replace(/&Ouml;/g, '\u00d6');
  aTXT = aTXT.replace(/&uacute;/g, '\u00fa');
  aTXT = aTXT.replace(/&ugrave;/g, '\u00f9');
  aTXT = aTXT.replace(/&ucirc;/g, '\u00fb');
  aTXT = aTXT.replace(/&uuml;/g, '\u00fc');
  aTXT = aTXT.replace(/&Uacute;/g, '\u00da');
  aTXT = aTXT.replace(/&Ugrave;/g, '\u00d9');
  aTXT = aTXT.replace(/&Ucirc;/g, '\u00db');
  aTXT = aTXT.replace(/&ccedil;/g, '\u00e7');
  aTXT = aTXT.replace(/&Ccedil;/g, '\u00c7');
  aTXT = aTXT.replace(/&ntilde;/g, '\u00f1');
  aTXT = aTXT.replace(/&Ntilde;/g, '\u00d1');
  aTXT = aTXT.replace(/&amp;/g, '\u0026');
  aTXT = aTXT.replace(/&lsquo;';/g, '\u0027');
  aTXT = aTXT.replace(/&rsquo;';/g, '\u0027');
  aTXT = aTXT.replace(/&ordm;';/g, '\u00ba');
  aTXT = aTXT.replace(/&ordf;';/g, '\u00aa');

  return aTXT;
}

Date.prototype.addDays = function (days) {
  var dat = new Date(this.valueOf());
  dat.setDate(dat.getDate() + days);
  return dat;
}

//Funcao que substitui as letras com acesntos 
function removeAcento(strToReplace) {
  str_acento = "·‡„‚‰ÈËÍÎÌÏÓÔÛÚıÙˆ˙˘˚¸Á¡¿√¬ƒ…» ÀÕÃŒœ”“’÷‘⁄Ÿ€‹«";
  str_sem_acento = "aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC";
  var nova = "";
  for (var i = 0; i < strToReplace.length; i++) {
    if (str_acento.indexOf(strToReplace.charAt(i)) != -1) {
      nova += str_sem_acento.substr(str_acento.search(strToReplace.substr(i, 1)), 1);
    } else {
      nova += strToReplace.substr(i, 1);
    }
  }
  return nova;
}

//Verifica a forÁa de uma senha
function verificaForcaSenha(senha) {
  forca = 0;

  if(senha.length >= 6 && senha.match(/[a-z]+/) && senha.match(/[A-Z]+/) && senha.match(/[0-9]+/) &&senha.match(/[^a-zA-Z 0-9]+/g)){ //Excelente
    forca = 40;
  } else if((senha.length >= 6) && senha.match(/[a-z]+/) && senha.match(/[A-Z]+/) && senha.match(/[0-9]+/)){ //Forte
    forca = 30;
  } else if((senha.length >= 6) && senha.match(/[a-z]+/) && senha.match(/[0-9]+/)){ //Justa
    forca = 20;
  } else {
    forca = 10;
  }

  return forca;
}