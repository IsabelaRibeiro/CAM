<?php

function ValidaPermissaoFuncionaliade($aFuncionalidades, $aItem) {
	return (strstr($aFuncionalidades,$aItem) > -1);
}

function AbreEmJanela($_d){
	if ($_d[0]->Janela == 0){
		return true;
	} else {
		return false;
	}
}


// fun&ccedil;&atilde;o que converte um array para um objeto
function arrayToObject(array $array, $className) {
  return unserialize(sprintf('O:%d:"%s"%s', strlen($className), $className, strstr(serialize($array), ':')));
}

function removerAcentos($string) { //Fun��o para o banco de dados.
  $string = utf8_decode($string);
  $string = preg_replace("/[����������]/", "a", $string);
  $string = preg_replace("/[������]/", "e", $string);
  $string = preg_replace("/[����]/", "i", $string);
  $string = preg_replace("/[����������]/", "o", $string);
  $string = preg_replace("/[������]/", "u", $string);
  $string = preg_replace("/��/", "c", $string);
  $string = preg_replace("/[][><}{)(:;,!?*%~^`&#@]�/", "", $string);
  $string = preg_replace("/ /", "_", $string);
  $string = strtolower($string);
  $string = str_replace("arquivo", "Arquivo", $string);
  return $string;
}

function removerAcentos2($string) {
	$map = array('�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'e', '�' => 'e',
	   		     '�' => 'i', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'u', '�' => 'u',
	    		 '�' => 'c', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'E',
	    		 '�' => 'E', '�' => 'I', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'U',
	    		 '�' => 'U', '�' => 'C'
	);
	return  strtr(utf8_decode($string), $map);
}

function DiferencaDatas($dataInicial, $dataFinal){		
	$date = new DateTime($dataInicial); 
	$date2 = new DateTime($dataFinal); 
	return ($date->diff($date2));	
}

function formatarPlaca($aPlaca, $aTraco = true) {
  // retira espa�os em branco no in�cio e fim
  $aPlaca = trim($aPlaca);
  // monta a placa com as letras dos 3 primeiro caracteres mais os n�meros dos 4 �ltimos
  $placa = eregi_replace("^[^a-zA-Z]{*}$", "", substr($aPlaca, 0, 3)) . "-" . eregi_replace("^[^0-9]{*}$", "", substr($aPlaca, - 4));
  if (! $aTraco) {
    $placa = str_replace("-", "", $placa);
  }
  return strtoupper($placa);
}

function formatarData($aData) {
	if ($aData != null || $aData != ""){
		$aData = substr($aData, 8, 2) . "/" . substr($aData, 5, 2) . "/" . substr($aData, 0, 4) . " " . substr($aData, 11, 5);
		return $aData;
	}	
}

function formatarDataBanco($aData){
	if ($aData != null || $aData != ""){
		$aData = substr($aData, 6, 4) . "-" . substr($aData, 3, 2) . "-" . substr($aData, 0, 2) . " " . substr($aData, 11, 5);
		return $aData;
	}
}

function formatarCPFCNPJ($aCpfCnpj, $aPontos = true, $aTraco = true, $aBarra = true) {
  // retira espa�os em branco no in�cio e fim
  $valosr = trim($aCpfCnpj);
  // retira tudo que n�o � n�mero
  $valor = eregi_replace("^[^0-9]{*}$", "", $valosr);
  $retorno = "";
  if (strlen($valor) > 11) { // se tiver mais que 11 numeros � CNPJ
    $valor = str_pad($valor, 14, "0", STR_PAD_LEFT);
    if ($aPontos) {
      $retorno = substr($valor, 0, 2) . "." . substr($valor, 2, 3) . "." . substr($valor, 5, 3);
    } else {
      $retorno = substr($valor, 0, 8);
    }
    if ($aBarra) {
      $retorno .= "/";
    }
    
    $retorno .= substr($valor, 8, 4);
    if ($aTraco) {
      $retorno .= "-";
    }
    $retorno .= substr($valor, - 2);
  } else {
    $valor = str_pad($valor, 11, "0", STR_PAD_LEFT); // 023.151.829-30 -- 02315182930
    
    if ($aPontos) {
      $retorno = substr($valor, 0, 3) . "." . substr($valor, 3, 3) . "." . substr($valor, 6, 3);
    } else {
      $retorno = $retorno = substr($valor, 0, 9);
    }
    
    if ($aTraco) {
      $retorno .= "-";
    }
    
    $retorno .= substr($valor, - 2);
  }
  
  return $retorno;
}

// fun��o usada para preparar o filtro das combos que dependem de outra
function preparaFiltro() {
  $filtro = $_POST["filter"];
  return $filtro["filters"][0]["value"];
}

// fun&ccedil;&atilde;o usada para auxiliar no debug, escrendo na tela todo os conte&uacute;do e estrutura de uma vari&aacute;vel
function debbug($var, $finaliza = FALSE) {
  echo "<pre>";
  print_r($var);
  echo "</pre>";
  
  if ($finaliza) {
    exit();
  }
}

// classe para troca de mensagens com o JavaScript via JSON
class MensagemModel {
  public $Tipo;
  public $Texto;
  public $Dados;

  public function __construct($tipo, $texto, $dados = '') {
    $this->Tipo = $tipo;
    $this->Texto = utf8_encode($texto);
    $this->Dados = $dados;
  }
}

// classe para ser utilizada nos combos, listbox e multiselects
class ComboItem {
  public $Id;
  public $Nome;
  
  function __construct($id = "", $nome = ""){
  	$this->Id = $id;
  	$this->Nome = $nome;
  }
}

// classe para treeview
class ArvoreItem extends ComboItem {
  public $Tipo;
  public $Selecionado;
  public $Filhos;

  public function __construct() {
    $this->Filhos = array();
  }
}