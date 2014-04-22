<?php

namespace controller\user;

use model\user;
use lib\MyCookie;

class UserControl {

    public function __construct() {
        if (is_null(filter_input(INPUT_SERVER, MyCookie::MessageSession))) {
            $_SESSION[MyCookie::MessageSession] = '';
        }
    }

    public function FirstRun() {
        $accAdm = new user\accountType\AccountType();
        $accAdm->setName('Administrador');
        $accAdm->setFlag('ADMINISTRATOR');
        $accAdm->Save();
        $accUsr = new user\accountType\AccountType();
        $accUsr->setName('Usuário');
        $accUsr->setFlag('USER');
        $accUsr->Save();
        $user = new user\User;
        $user->setName('Administrador');
        $user->setLastName('Padrão');
        $user->setLogin('admin');
        $user->setPassword('admin');
        $user->setAccountType($accAdm);
        $user->setStatus('1');
        $user->Save();
    }

    public static function Manage() {
        UserControl::VerifyAccessLevel('ADMINISTRADOR');
        include('usuario.view.gerenciar.php');
    }

    public static function Salvar() {
        global $_MyCookieUser;
        $usuario = new TUsuario;
        $usuario->CarregarSerial($_REQUEST['obj']);
        $usuario->setNome($_REQUEST['nome']);
        $usuario->setNomeDoMeio($_REQUEST['nomeMeio']);
        $usuario->setSobrenome($_REQUEST['sobrenome']);
        $usuario->setTipoUsuario_Id($_REQUEST['tipousuario_id']);
        $usuario->setUsuario($_REQUEST['usuario']);
        if (isset($_REQUEST['novaSenha']))
            $usuario->setSenha(md5($_REQUEST['novaSenha']));
        $usuario->Salvar();
        if ($_MyCookieUser->getId() == $usuario->getId())
            $_SESSION['MyCookie_SESSAO_USUARIO'] = serialize($usuario);
    }

    public static function AlterarSenha() {

        $usuario = new TUsuario;

        $usuario->CarregarSerial($_REQUEST['obj']);

        $usuario->setSenha(md5($_REQUEST['senha']));

        $usuario->Salvar();
    }

    public static function getNomeUsuario() {

        $usuario = unserialize($_SESSION['MyCookie_SESSAO_USUARIO']);

        if (is_object($usuario))
            return $usuario->getNome();
    }

    public static function getSobrenomeUsuario() {

        $usuario = unserialize($_SESSION['MyCookie_SESSAO_USUARIO']);

        if (is_object($usuario))
            return $usuario->getSobrenome();
    }

    public function Login() {
        $users = user\User::Select('u')
                ->where('u.login = :login')
                ->setParameter('login', filter_input(INPUT_POST, 'login'))
                ->getQuery()
                ->execute();
        $_SESSION[MyCookie::MessageSession] = 'Usuário ou senha inválida. Tente novamente.';
        if (count($users) == 1)
            if ($users[0]->getPassword() == md5(filter_input(INPUT_POST, 'password')))
                if ($users[0]->getStatus()) {
                    $_SESSION[MyCookie::UserIdSession] = $users[0]->getId();
                    $_SESSION[MyCookie::MessageSession] = 'Sucesso!';
                } else
                    $_SESSION[MyCookie::MessageSession] = 'Usuário desativado. Contate o administrador.';
        header('location:' . $_SERVER['HTTP_REFERER']);
    }

    public static function LoadSessionUser() {
        global $_MyCookieUser;
        if (array_key_exists(MyCookie::UserIdSession, $_SESSION) & !empty($_SESSION[MyCookie::UserIdSession])) {
            $_MyCookieUser = user\User::Select('u')
                ->where('u.id = :id')
                ->setParameter('id', $_SESSION[MyCookie::UserIdSession])
                ->getQuery()
                ->execute()[0];            
        }
    }

    public function Logout() {
        global $_MyCookie;
        unset($_SESSION['MyCookie_SESSAO_USUARIO']);
        unset($_SESSION['MyCookie_SESSAO_MSG']);
        header('location:' . $_MyCookie->getSite());
    }

    public static function isAdministratorLoggedIn() {
        if (UserControl::isUserLoggedIn()) {
            global $_MyCookieUser;
            return ($_MyCookieUser->getAccountType()->getFlag() == 'ADMINISTRATOR');
        }
        return false;
    }

    public static function isUserLoggedIn() {
        return isset($_SESSION[MyCookie::UserIdSession]);
    }

    public static function VerifyAccessLevel($acesso, $_ = null) {
        global $_MyCookie;
        global $_MyCookieUser;
        $acessos = func_get_args();
        if (!in_array($_MyCookieUser->getTipoUsuario()->getFlag(), $acessos))
            header('location: ' . $_MyCookie->getSite() . 'administrador/');
    }

    public static function ExibirTabelaUsuariosPorTipo($tipo) {
        global $_MyCookie;
        global $_MyCookieUser;
        $usuarios = new TUsuario;
        $usuarios = $usuarios->ListarTodosOnde("TipoUsuario_Id = $tipo");
        $usuarios = $_MyCookie->sortObjects($usuarios, 'Nome', SORT_ASC);
        $usuarios = $_MyCookie->sortObjects($usuarios, 'Status', SORT_DESC);
        include('usuario.view.ui.table.gerenciar.php');
    }

    public static function Adicionar() {
        TUsuarioControl::VerificarNivelAcesso('ADMINISTRADOR');
        $usuario = new TUsuario;
        $acao = 'Adicionar';
        include('usuario.view.edicao.php');
    }

    public static function Editar() {
        global $_MyCookie;
        global $_MyCookieUser;
        $usuario = new TUsuario;
        $usuario = $usuario->ListarPorId($_MyCookie->getURLVariables(2));
        if ($_MyCookieUser->getId() != $usuario->getId())
            TUsuarioControl::VerificarNivelAcesso('ADMINISTRADOR');
        $acao = 'Editar';
        include('usuario.view.edicao.php');
    }

    public static function VerificarSenhaAtual() {

        $usuario = new TUsuario;

        $usuario->CarregarSerial($_REQUEST['obj']);

        echo ($usuario->getSenha() == md5($_REQUEST['senhaAtual'])) ? 'true' : 'false';
    }

    public static function ReativarUsuario() {
        $usuario = new TUsuario;
        $usuario->CarregarSerial($_REQUEST['obj']);
        $usuario->setStatus('1');
        $usuario->Salvar();
    }

    public static function DesativarUsuario() {
        $usuario = new TUsuario;
        $usuario->CarregarSerial($_REQUEST['obj']);
        $usuario->setStatus('0');
        $usuario->Salvar();
    }

    public static function Excluir() {
        $usuario = new TUsuario;
        $usuario->CarregarSerial($_REQUEST['obj']);
        $usuario->DeletarRegistro();
    }

    public static function VerificarUsuario() {

        $usuario = new TUsuario;

        if (count($usuario->ListarTodosOnde("usuario = '{$_REQUEST['usuario']}'")) > 0)
            echo '1';
    }

    public static function AlterarTipo() {

        $_SESSION['TIPO_INDEX'] = $_REQUEST['tipo'];
    }

    public static function ResetarTipo() {
        unset($_SESSION['TIPO_INDEX']);
    }

}

?>
