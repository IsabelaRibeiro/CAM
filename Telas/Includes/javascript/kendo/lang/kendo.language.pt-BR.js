/*
* Kendo UI Localization Project for v2012.3.1114 
* Copyright 2012 Telerik AD. All rights reserved.
* 
* Brazilian Portuguese (pt-BR) Language Pack
*
* Project home  : https://github.com/loudenvier/kendo-global
* Kendo UI home : http://kendoui.com
* Author        : Felipe Machado (Loudenvier) 
*                 http://feliperochamachado.com.br/index_en.html
*
* This project is released to the public domain, although one must abide to the 
* licensing terms set forth by Telerik to use Kendo UI, as shown bellow.
*
* Telerik's original licensing terms:
* -----------------------------------
* Kendo UI Web commercial licenses may be obtained at
* https://www.kendoui.com/purchase/license-agreement/kendo-ui-web-commercial.aspx
* If you do not own a commercial license, this file shall be governed by the
* GNU General Public License (GPL) version 3.
* For GPL requirements, please review: http://www.gnu.org/copyleft/gpl.html
*/

kendo.ui.Locale = "Portugu&ecirc;s Brasileiro (pt-BR)";
kendo.ui.ColumnMenu.prototype.options.messages = 
  $.extend(kendo.ui.ColumnMenu.prototype.options.messages, {

/* COLUMN MENU MESSAGES 
 ****************************************************************************/   
  sortAscending: "Ascendente",
  sortDescending: "Descendente",
  filter: "Filtro",
  columns: "Colunas"
 /***************************************************************************/   
});

kendo.ui.Groupable.prototype.options.messages = 
  $.extend(kendo.ui.Groupable.prototype.options.messages, {

/* GRID GROUP PANEL MESSAGES 
 ****************************************************************************/   
  empty: "Arraste colunas aqui para agrupar pelas mesmas"
 /***************************************************************************/   
});

kendo.ui.FilterMenu.prototype.options.messages = 
  $.extend(kendo.ui.FilterMenu.prototype.options.messages, {
  
/* FILTER MENU MESSAGES 
 ***************************************************************************/   
        info: "T&iacute;tulo:",        // sets the text on top of the filter menu
        filter: "Filtrar",      // sets the text for the "Filter" button
        clear: "Limpar",        // sets the text for the "Clear" button
        // when filtering boolean numbers
        isTrue: "&Eacute; verdadeiro", // sets the text for "isTrue" radio button
        isFalse: "&Eacute; falso",     // sets the text for "isFalse" radio button
        //changes the text of the "And" and "Or" of the filter menu
        and: "E",
        or: "Ou",
  selectValue: "-selecione um valor-"
 /***************************************************************************/   
});

/* FILTER MULTICHECK
 ****************************************************************************/
kendo.ui.FilterMultiCheck.prototype.options.messages =
  $.extend(kendo.ui.FilterMultiCheck.prototype.options.messages, 	{
				checkAll:"Selecionar todos",
				clear:"Limpar",
				filter:"Filtrar"
});
         
kendo.ui.FilterMenu.prototype.options.operators =           
  $.extend(kendo.ui.FilterMenu.prototype.options.operators, {

/* FILTER MENU OPERATORS (for each supported data type) 
 ****************************************************************************/   
  string: {
      eq: "&Eacute; igual",
      neq: "&Eacute; diferente",
      startswith: "Come&ccedil;a com",
      contains: "Cont&eacute;m",
      doesnotcontain: "N&atilde;o cont&eacute;m",
      endswith: "Termina com",
      isempty: "Vazio",
      isnotempty: "Preenchido"      
  },
  number: {
      eq: "&Eacute; igual",
      neq: "&Eacute; diferente",
      gte: "&Eacute; maior que ou igual a",
      gt: "&Eacute; maior que",
      lte: "&Eacute; menor que ou igual a",
      lt: "&Eacute; menor que",
      isempty: "Vazio",
      isnotempty: "Preenchido"
  },
  date: {
      eq: "&Eacute; igual",
      neq: "&Eacute; diferente",
      gte: "&Eacute; igual ou mais recente que",
      gt: "&Eacute; mais recente que",
      lte: "&Eacute; igual ou mais antigo que",
      lt: "&Eacute; mais antigo que",
      isempty: "Vazio",
      isnotempty: "Preenchido"
  },
  enums: {
      eq: "&Eacute; igual",
      neq: "&Eacute; diferente",
      isempty: "Vazio",
      isnotempty: "Preenchido"
  }
 /***************************************************************************/   
});

kendo.ui.Pager.prototype.options.messages = 
  $.extend(kendo.ui.Pager.prototype.options.messages, {
  
/* PAGER MESSAGES 
 ****************************************************************************/   
  display: "{0} - {1} de {2} itens",
  empty: "Nada a exibir",
  page: "P&aacute;gina",
  of: "de {0}",
  itemsPerPage: "itens por p&aacute;gina",
  first: "Vai para primeira p&aacute;gina",
  previous: "Vai para p&aacute;gina anterior",
  next: "Vai para p&aacute;gina seguinte",
  last: "Vai para &uacute;ltima p&aacute;gina",
  refresh: "Atualiza"
 /***************************************************************************/   
});

kendo.ui.Validator.prototype.options.messages = 
  $.extend(kendo.ui.Validator.prototype.options.messages, {

/* VALIDATOR MESSAGES 
 ****************************************************************************/   
  required: "{0} &eacute; obrigat&oacute;rio",
  pattern: "{0} n&atilde;o &eacute; v&aacute;lido",
  min: "{0} deve ser maior que ou igual a {1}",
  max: "{0} deve ser menor que ou igual a {1}",
  step: "{0} n&atilde;o &eacute; v&aacute;lido",
  email: "{0} n&atilde;o &eacute; um email v&aacute;lido",
  url: "{0} n&atilde;o &eacute; uma URL v&aacute;lida",
  date: "{0} n&atilde;o &eacute; uma data v&aacute;lida"
 /***************************************************************************/   
});

kendo.ui.ImageBrowser.prototype.options.messages = 
  $.extend(kendo.ui.ImageBrowser.prototype.options.messages, {

/* IMAGE BROWSER MESSAGES 
 ****************************************************************************/   
  uploadFile: "Enviar",
  orderBy: "Classificar por",
  orderByName: "Nome",
  orderBySize: "Tamanho",
  directoryNotFound: "Uma pasta com este nome n&atilde;o foi encontrada.",
  emptyFolder: "Pasta Vazia",
  deleteFile: 'Tem certeza que deseja excluir "{0}"?',
  invalidFileType: "O arquivo selecionado \"{0}\" n&atilde;o &eacute; v&aacute;lido. Os tipos de arquivo suportados s&atilde;o {1}.",
  overwriteFile: "Um arquivo com o nome \"{0}\" j&aacute; existe na pasta atual. Voc&ecirc; quer sobrescrev&ecirc;-lo?",
  dropFilesHere: "solte arquivos aqui para envi&aacute;-los"
 /***************************************************************************/   
});

kendo.ui.Editor.prototype.options.messages = 
  $.extend(kendo.ui.Editor.prototype.options.messages, {

/* EDITOR MESSAGES 
 ****************************************************************************/   
  bold: "Negrito",
  italic: "It&aacute;lico",
  underline: "Sublinhado",
  strikethrough: "Tachado",
  superscript: "Sobrescrito",
  subscript: "Subscrito",
  justifyCenter: "Centralizar texto",
  justifyLeft: "Alinhar texto à esquerda",
  justifyRight: "Alinhar texto à direita",
  justifyFull: "Justificar",
  insertUnorderedList: "Inserir list n&atilde;o ordenada",
  insertOrderedList: "Iserir lista ordenada",
  indent: "Aumentar recuo",
  outdent: "Diminuir recuo",
  createLink: "Inserir link",
  unlink: "Remover link",
  insertImage: "Inserir imagem",
  insertHtml: "Inserir HTML",
  fontName: "Selecionar fam&iacute;lia da fonte",
  fontNameInherit: "(fonte herdada)",
  fontSize: "Selecionar tamanho da fonte",
  fontSizeInherit: "(tamanho herdado)",
  formatBlock: "Formatar",
  foreColor: "Cor",
  backColor: "Cor de fundo",
  style: "Estilos",
  emptyFolder: "Pasta Vazia",
  uploadFile: "Enviar",
  orderBy: "Classificar por:",
  orderBySize: "Tamanho",
  orderByName: "Nome",
  invalidFileType: "O arquivo selecionado \"{0}\" n&atilde;o &eacute; v&aacute;lido. Os arquivos suportados s&atilde;o {1}.",
  deleteFile: 'Tem certeza que deseja excluir "{0}"?',
  overwriteFile: 'Um arquivo com o nome "{0}" j&aacute; existe na pasta atual. Voc&ecirc; quer sobrescrev&ecirc;-lo?',
  directoryNotFound: "Uma pasta com este nome n&atilde;o foi encontrada.",
  imageWebAddress: "Endere&ccedil;o internet",
  imageAltText: "Texto alternativo",
  dialogInsert: "Inserir",
  dialogButtonSeparator: "ou",
  dialogCancel: "Cancelar"
 /***************************************************************************/   
});