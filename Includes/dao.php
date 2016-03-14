<?php

class Registro {
  public $Campo;
  public $Campos;

  public function __construct(& $nomes) {
    $this->Campo = array();
    $this->Campos = array();
    for ($i = 0; $i < count($nomes); $i++) {
      $this->Campo[$nomes[$i]] = "";
      $this->Campos[$i] = & $this->Campo[$nomes[$i]];
    }
  }

}

abstract class DataBase {
  private $caseSQL;
  private $tipo;
  protected $conectado;
  protected $conexao;
  protected $servidor;
  protected $banco;
  protected $usuario;
  protected $senha;
  protected $codigo;
  protected $SQL;
  protected $slashe;
  protected $avancado;
  protected $persistente;
  protected $erros;
  public $Parametros;
  public $Parametro;
  public $Registros;
  public $Registro;

  //Metodos do Objeto
  public function __toString() {
    echo "<pre>";
    print_r($this);
    echo "</pre>";
    return "";
  }

  public function __construct() {
    $this->parametros = array();
    $this->parametro = array();
    $this->banco = BANCO;
    $this->servidor = SERVIDOR;
    $this->usuario = LOGIN;
    $this->senha = SENHA;
    $this->persistente = true;
    $this->conexao = null;
    $this->caseSQL = 0;
    $this->slashe = true;
    $this->avancado = true;
    $this->tipo = "mysql";
    $this->conectado = false;
  }

  public function SQLAdd($texto) {
    $this->SQL .= " " . $texto . " ";
  }

  public function setSQL($texto) {
    $this->SQL = $texto;
  }

  public function getSQL() {
    return $this->SQL;
  }

  public function Clear() {
    unset($this->SQL, $this->Parametro, $this->Parametros, $this->Registros, $this->Registro, $this->Combo, $this->Combo->HTML, $this->codigo);
  }

  public function getCodigo() {
    return $this->codigo;
  }

  //@ texto com '', # numero e % texto sem ''
  public function Executar($tabela = "n", $where = "n") {
    try {
      if ($this->caseSQL == 1)
        $this->SQL = strtoupper($this->SQL);
      elseif ($this->caseSQL == 2)
        $this->SQL = strtolower($this->SQL);

      // trata os parametros
      if (count($this->Parametro) > 0){
        foreach ($this->Parametro as $k => $v) {
          if ($k[0] == "#"){
            $this->SQL = str_replace($k, $v, $this->SQL);
          } elseif ($k[0] == "%"){
            $this->SQL = str_replace($k, addslashes ($v), $this->SQL);
          } else {
            $this->SQL = str_replace($k, "'" . addslashes ($v) . "'", $this->SQL);
          }
        }
      }
      if (strtoupper(substr(trim($this->SQL), 0, 6)) == "SELECT")
        return $this->consulta();
      elseif (strtoupper(substr(trim($this->SQL), 0, 6)) == "INSERT")
        return $this->manipula($tabela, $where);
      elseif (strtoupper(substr(trim($this->SQL), 0, 6)) == "DELETE")
        return $this->manipula($tabela, $where);
      elseif (strtoupper(substr(trim($this->SQL), 0, 6)) == "UPDATE")
        return $this->manipula($tabela, $where);
      elseif (strtoupper(substr(trim($this->SQL), 0, 6)) == "EXECUT")
        return $this->manipula($tabela, $where);
      elseif (strtoupper(substr(trim($this->SQL), 0, 4)) == "EXEC")
        return $this->consulta();
      else {
        return false;
        exit;
      }
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

}

class bdMysql extends DataBase {

  private function conecta() {
    if (!$this->conexao){
      $this->conexao = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->banco);
      //mysql_select_db($this->banco, $this->conexao);
      $this->conectado = true;
    }
  }

  private function desconecta() {
    if (!$this->persistente){
      mysqli_close($this->conexao);
      $this->conexao = false;
      $this->conectado = false;
    }
  }

  protected function consulta() {
    $this->conecta();
    $consulta = mysqli_query($this->conexao, $this->SQL);
    $this->Registros = array();
    if (mysqli_num_rows($consulta) < 1){
      return false;
      exit;
    }

    if (!$this->avancado){
      $this->Registros = array();
      $w = 0;
      while (($a = mysqli_fetch_row($consulta))) {
        $this->Registros[$w] = array();
        if ($this->slashe){
          for ($z = 0; $z < count($a); $z++)
            $this->Registros[$w][$z] = trim(addslashes($a[$z]));
        } else {
          for ($z = 0; $z < count($a); $z++)
            $this->Registros[$w][$z] = trim($a[$z]);
        }
        $w++;
      }

      return true;
      exit;
    } else {
      $w = 0;
      $this->Registro = array();
      while ($property = mysqli_fetch_field($consulta)) {
        $nomes[] =  strtoupper($property->name);
      }
      while ($a = mysqli_fetch_row($consulta)) {
        $this->Registro[$w] = new Registro($nomes);
        if ($this->slashe){
          for ($z = 0; $z < count($a); $z++) {
            $this->Registro[$w]->Campos[$z] = trim(addslashes($a[$z]));
            $this->Registro[$w]->Campo[$nomes[$z]] = & $this->Registro[$w]->Campos[$z];
          }
        } else {
          for ($z = 0; $z < count($a); $z++) {
            $this->Registro[$w]->Campos[$z] = trim($a[$z]);
            $this->Registro[$w]->Campo[$nomes[$z]] = & $this->Registro[$w]->Campos[$z];
          }
        }
        $w++;
      }

      return true;
      exit;
    }
  }

  protected function manipula(& $tabela, & $where) {
    global $configs;
    $this->conecta();
    if ($tabela != "n"){
      $con = mysqli_query($this->conexao,"SELECT COUNT(*) as QTD FROM $tabela WHERE $where");
      $res = mysqli_fetch_object($con);
      if ($res->QTD > 0){

        unset($res, $con);
        return false;
        exit;
      }
      unset($res, $con);
    }

    $manipula = mysqli_query($this->conexao, $this->SQL);
    if (!$manipula){
      return false;
      exit;
    }

    if (strtoupper(substr(trim($this->SQL), 0, 6)) == "INSERT"){
      $this->codigo = mysqli_insert_id($this->conexao);
    }


    return $manipula;
    exit;
  }

  private function registraLog() {
    // modulo, tela, acao, id, sql
  }

}
?>
