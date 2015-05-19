<?php

namespace lib\util\HTML;

class TInput extends TTag {

  public function __construct($nome, $tipo = 'text', $classe = '', $valor = '', $selecionado = false) {
    parent::__construct('input');    
    $this->setAtributo('type', $tipo);
    $this->setAtributo('id', $tipo . $nome);
    $this->setAtributo('name', $nome);
    $this->setAtributo('value', $valor);
    $this->setAtributo('class', $classe);
    if ($selecionado)
      $this->setAtributo('selected', 'selected');
  }

}

?>
