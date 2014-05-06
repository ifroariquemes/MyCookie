<?php

namespace controller\user\accountType;

use model\user\accountType\AccountType;

class AccountTypeControl {

  public function __call($acao, $args) {

    global $_Biscoito;

    switch ($_Biscoito->getVariaveisDaURL(2)) {

      case 'editar':

        TTipoUsuarioControl::Editar($_Biscoito->getVariaveisDaURL(3));

        break;

      case 'excluir':

        TTipoUsuarioControl::Excluir($_Biscoito->getVariaveisDaURL(3));

        break;
    }
  }

  public static function ListAccountTypes() {        
    return AccountType::Select('a')->orderBy('a.name')->getQuery()->execute();
  }

  public static function Gerenciar() {

    $tiposUsuario = new TTipoUsuario();

    $tiposUsuario = $tiposUsuario->ListarTodos();

    include('tipousuario.view.gerenciar.php');
  }

  public static function Adicionar() {

    $tipoUsuario = new TTipoUsuario();

    $acao = 'Adicionar';

    include('tipousuario.view.edicao.php');
  }

  public static function Editar($id) {

    $tipoUsuario = new TTipoUsuario();

    $tipoUsuario = $tipoUsuario->ListarPorId($id);

    $acao = 'Editar';

    include('tipousuario.view.edicao.php');
  }

  public static function Excluir($id) {

    $tipoUsuario = new TTipoUsuario();

    $tipoUsuario = $tipoUsuario->ListarPorId($id);

    $tipoUsuario->DeletarRegistro();
  }

  public function Salvar() {
    $tipoUsuario = new TTipoUsuario();
    $tipoUsuario->CarregarSerial($_REQUEST['obj']);
    $tipoUsuario->setNome($_REQUEST['nome']);
    $tipoUsuario->setFlag($_REQUEST['flag']);
    $tipoUsuario->Salvar();
  }

  public static function getUsuariosDoTipo() {
    $usuario = new \Biscoito\Modulos\Usuario\TUsuario();
    echo count($usuario->ListarTodosOnde("tipousuario_id = {$_REQUEST['id']}"));
  }

  public static function ExibirSelecao($usuario_id = null, $id = null) {
    global $_Biscoito;
    global $_UsuarioLogado;
    $tiposUsuario = new TTipoUsuario();
    $tiposUsuario = $tiposUsuario->ListarTodos();
    $tiposUsuario = $_Biscoito->ordenarObjetos($tiposUsuario, 'Nome', SORT_ASC);
    $readonly = ($_UsuarioLogado->getId() == $usuario_id);
    include('tipousuario.view.select.php');
  }

}

?>