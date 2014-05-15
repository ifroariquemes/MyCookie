<?php

namespace controller\user;

use lib\MyCookie;
use model\user\accountType\AccountType;
use model\user\User;

class UserController {

    public function __construct() {
        if (is_null(filter_input(INPUT_SERVER, MyCookie::MessageSession))) {
            $_SESSION[MyCookie::MessageSession] = '';
        }
    }

    public static function firstRun() {
        $acAdmin = new AccountType();
        $acAdmin->setFlag('ADMINISTRATOR');
        $acAdmin->setName('Administrator');
        $acAdmin->save();
        $acUser = new AccountType();
        $acUser->setFlag('USER');
        $acUser->setName('User');
        $acUser->save();
        $uAdmin = new User();
        $uAdmin->setName('Administrator');
        $uAdmin->setLastName('Default');
        $uAdmin->setLogin('admin');
        $uAdmin->setPassword('admin');
        $uAdmin->setAccountType($acAdmin);
        $uAdmin->save();
    }

    public static function manage() {
        global $_MyCookie;
        UserController::VerifyAccessLevel('ADMINISTRATOR');
        $_MyCookie->LoadView('user', 'Manage');
    }

    public static function add() {
        global $_MyCookie;
        UserController::VerifyAccessLevel('ADMINISTRATOR');
        $_MyCookie->LoadView('user', 'Edit', array('action' => 'Add', 'user' => new User));
    }

    public static function edit() {
        global $_MyCookie;
        global $_MyCookieUser;
        $user = User::select('u')->where('u.id =  ?1')
                        ->setParameter(1, $_MyCookie->getURLVariables(2))->getQuery()->getSingleResult();
        if ($_MyCookieUser->getId() != $user->getId()) {
            UserController::VerifyAccessLevel('ADMINISTRATOR');
        }
        $_MyCookie->LoadView('user', 'Edit', array('action' => 'Edit', 'user' => $user));
    }

    public static function save() {
        $user = (empty(filter_input(INPUT_POST, 'id'))) ? new User() : User::select('u')->where('u.id =  ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        $user->setName(filter_input(INPUT_POST, 'name'));
        $user->setMiddleName(filter_input(INPUT_POST, 'middleName'));
        $user->setLastname(filter_input(INPUT_POST, 'lastName'));
        $user->setLogin(filter_input(INPUT_POST, 'login'));
        if (!empty(filter_input(INPUT_POST, 'newPassword'))) {
            $user->setPassword(filter_input(INPUT_POST, 'newPassword'));
        }
        $user->setAccountType(AccountType::select('a')->where('a.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'accountTypeId'))->getQuery()->getSingleResult());
        $user->save();
    }

    public static function deactivate() {
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        $user->setStatus(0);
        $user->save();
    }

    public static function reactivate() {
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        $user->setStatus(1);
        $user->save();
    }

    public static function delete() {
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        $user->delete();
    }

    public static function checkActualPassword() {
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        echo ($user->getPassword() == md5(filter_input(INPUT_POST, 'actualPassword'))) ? 'true' : 'false';                
    }

    public static function changePassword() {
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        $user->setPassword(filter_input(INPUT_POST, 'newPassword'));
        $user->save();
    }

    public static function getNomeUsuario() {

        $usuario = unserialize($_SESSION['MyCookie_SESSAO_USUARIO']);

        if (is_object($usuario))
            return $usuario->getName();
    }

    public static function getSobrenomeUsuario() {

        $usuario = unserialize($_SESSION['MyCookie_SESSAO_USUARIO']);

        if (is_object($usuario))
            return $usuario->getSobrenome();
    }

    public function Login() {
        $users = User::select('u')
                ->where('u.login = :login')
                ->setParameter('login', filter_input(INPUT_POST, 'login'))
                ->getQuery()
                ->execute();
        $_SESSION[MyCookie::MessageSession] = _e('Invalid login or password. Please, try again.', 'user');
        if (count($users) == 1) {
            if ($users[0]->getPassword() == md5(filter_input(INPUT_POST, 'password'))) {
                if ($users[0]->getStatus()) {
                    $_SESSION[MyCookie::UserIdSession] = $users[0]->getId();
                    $_SESSION[MyCookie::MessageSession] = _e('Success!', 'user');
                } else {
                    $_SESSION[MyCookie::MessageSession] = _('Your username was deactivated. Please, contact administration.', 'user');
                }
            }
        }
        header('location:' . $_SERVER['HTTP_REFERER']);
    }

    public static function LoadSessionUser() {
        global $_MyCookieUser;
        if (array_key_exists(MyCookie::UserIdSession, $_SESSION) & !empty($_SESSION[MyCookie::UserIdSession])) {
            $_MyCookieUser = User::select('u')
                            ->where('u.id = :id')
                            ->setParameter('id', $_SESSION[MyCookie::UserIdSession])
                            ->getQuery()
                            ->execute()[0];
        }
    }

    public function Logout() {
        global $_MyCookie;
        unset($_SESSION[MyCookie::UserIdSession]);
        unset($_SESSION[MyCookie::MessageSession]);
        header('location:' . $_MyCookie->getSite());
    }

    public static function isAdministratorLoggedIn() {
        if (UserController::isUserLoggedIn()) {
            global $_MyCookieUser;
            return ($_MyCookieUser->getAccountType()->getFlag() == 'ADMINISTRATOR');
        }
        return false;
    }

    public static function isUserLoggedIn() {
        return isset($_SESSION[MyCookie::UserIdSession]);
    }

    public static function VerifyAccessLevel($accessLevel, $_ = null) {
        global $_MyCookie;
        global $_MyCookieUser;
        $accessLevel = func_get_args();
        if (!in_array($_MyCookieUser->getAccountType()->getFlag(), $accessLevel))
            header('location: ' . $_MyCookie->getSite() . 'administrator/');
    }

    public static function ShowUserTableByType($accid) {
        global $_MyCookie;
        $data = User::select('u')->join('u.accountType', 'a')->where("a.id = ?1")->add('orderBy', 'u.name ASC, u.status DESC')
                        ->setParameter(1, $accid)->getQuery()->execute();
        $_MyCookie->LoadView('user', 'Manage.table', $data);
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
