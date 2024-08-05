<?php

  class Mensagem {
    private $para = null;
    private $assunto = null;
    private $mensagem = null;
    public function __construct($para, $assunto, $mensagem) {
      $this->para = $para;
      $this->assunto = $assunto;
      $this->mensagem = $mensagem;
    }

    public function __get($attr){
      return $this->$attr;
    }

    public function __set($attr, $value) {
      $this->$attr = $value;
    }

    public function mensagemValida(){
      if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
        return false;
      } else {
        return true;
      }
    }
  }

  $mensagem = new Mensagem($_POST['para'], $_POST['assunto'], $_POST['mensagem']);
  if($mensagem->mensagemValida()){
    echo 'Mensagem válida!';
  } else {
    echo 'Mensagem inválida. Falta(m) um ou mais parâmetros';
  }

