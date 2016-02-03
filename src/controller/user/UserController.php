<?php

namespace controller\user;

use lib\MyCookie;
use model\user\accountType\AccountType;
use model\user\User;
use lib\util\Pagination;

class UserController
{

    public function __construct()
    {
        if (!isset($_SESSION[MyCookie::MESSAGE_SESSION])) {
            $_SESSION[MyCookie::MESSAGE_SESSION] = '';
        }
    }

    public static function firstRun()
    {
        $acAdmin = new AccountType();
        $acAdmin->setFlag('ADMINISTRATOR');
        $acAdmin->setName('Administrator');
        $acAdmin->save();
        $acUser = new AccountType();
        $acUser->setFlag('USER');
        $acUser->setName('User');
        $acUser->save();
        $uAdmin = new User();
        $uAdmin->setName('Administrator Default');
        $uAdmin->setEmail('admin');
        $uAdmin->setPassword('admin');
        $uAdmin->setAccountType($acAdmin);
        $uAdmin->save();
    }

    public static function manage()
    {
        global $_MyCookie;
        UserController::checkAccessLevel('ADMINISTRATOR');
        $urlPage = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
        $urlPage = ($urlPage) ? $urlPage : 1;
        $data = Pagination::paginate(User::select('u'), $urlPage);
        $_MyCookie->loadView('user', 'manage'
                , array(
            'users' => $data,
            'currentPage' => $urlPage,
            'pages' => Pagination::getPages($data)));
    }

    public static function search()
    {
        global $_MyCookie;
        UserController::checkAccessLevel('ADMINISTRATOR');
        $urlPage = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
        $urlPage = ($urlPage) ? $urlPage : 1;
        $q = filter_input(INPUT_GET, 'q');
        $data = Pagination::paginate(User::select('u')
                                ->where(User::expr()->like('u.name', '?1'))
                                ->setParameter(1, sprintf('%%%s%%', $q))
                        , $urlPage);
        $_MyCookie->loadView('user', 'manage'
                , array(
            'users' => $data,
            'currentPage' => $urlPage,
            'pages' => Pagination::getPages($data),
            'searchTerm' => $q));
    }

    public static function add()
    {
        global $_MyCookie;
        UserController::checkAccessLevel('ADMINISTRATOR');
        $_MyCookie->loadView('user', 'edit', array('action' => 'add', 'user' => new User));
    }

    public static function edit()
    {
        global $_MyCookie;
        global $_User;
        $user = User::select('u')->where('u.id =  ?1')
                        ->setParameter(1, $_MyCookie->getURLVariables(2))->getQuery()->getSingleResult();
        if ($_User->getId() != $user->getId()) {
            UserController::checkAccessLevel('ADMINISTRATOR');
        }
        $_MyCookie->loadView('user', 'edit', array('action' => 'edit', 'user' => $user));
    }

    public static function verifyEmail()
    {
        global $_MyCookie;
        $user = User::select('u')->where('u.email = ?1')->setParameter(1, filter_input(INPUT_POST, 'email'))->getQuery()->getResult();
        if (count($user) > 0) {
            echo $_MyCookie->getTranslation('user', 'email.exists');
            return false;
        }
        return true;
    }

    public static function resend()
    {
        global $_MyCookie;
        $user = User::select('u')->where('u.email = ?1')->setParameter(1, filter_input(INPUT_POST, 'email'))->getQuery()->getResult();
        if (count($user) > 0) {
            self::sendEmailPublic($user[0]);
        } else {
            echo $_MyCookie->getTranslation('user', 'email.no_account');
        }
    }

    public static function forgot()
    {
        global $_MyCookie;
        $user = User::select('u')->where('u.email = ?1')->setParameter(1, filter_input(INPUT_POST, 'email'))->getQuery()->getResult();
        if (count($user) > 0) {
            $user[0]->setCode(uniqid('forgot_', true))->save();
            self::sendEmailForgot($user[0]);
        } else {
            echo $_MyCookie->getTranslation('user', 'email.no_account');
        }
    }

    public static function savePublic()
    {
        $user = new User();
        if (self::verifyEmail()) {
            $user
                    ->setName(filter_input(INPUT_POST, 'name'))
                    ->setEmail(filter_input(INPUT_POST, 'email'))
                    ->setPassword(filter_input(INPUT_POST, 'newPassword'))
                    ->setAccountType(AccountType::select('a')->where('a.flag = ?1')
                            ->setParameter(1, 'USER')->getQuery()->getSingleResult())
                    ->setStatus(0)
                    ->save();
            $user->setCode(md5($user->getId()))->save();
            self::sendEmailPublic($user);
        }
    }

    public static function confirmRegistration()
    {
        global $_MyCookie;
        $user = User::select('u')->where('u.code = ?1')->setParameter(1, filter_input(INPUT_GET, 'key'))->getQuery()->getResult();
        if (count($user) > 0) {
            $user[0]->setStatus(1)->save();
            $_MyCookie->loadView('user', 'confirmed', filter_input(INPUT_GET, 'return'));
        } else {
            echo $_MyCookie->getTranslate('user', 'email.key_not_recog');
        }
    }

    public static function confirmForgot()
    {
        global $_MyCookie;
        $user = User::select('u')->where('u.code = ?1')->setParameter(1, filter_input(INPUT_GET, 'key'))->getQuery()->getResult();
        if (count($user) > 0) {
            $_MyCookie->loadView('user', 'reset', filter_input(INPUT_GET, 'return'));
        } else {
            echo $_MyCookie->getTranslation('user', 'email.key_not_recog');
        }
    }

    public static function reset()
    {
        global $_MyCookie;
        $user = User::select('u')->where('u.code = ?1')->setParameter(1, filter_input(INPUT_POST, 'key'))->getQuery()->getResult();
        if (count($user) > 0) {
            $user[0]->setPassword(filter_input(INPUT_POST, 'newPassword'))->save();
            echo $_MyCookie->getTranslation('user', 'message.reset_pwd_ok');
        } else {
            echo $_MyCookie->getTranslation('user', 'email.key_not_recog');
        }
    }

    public static function sendEmailPublic(User $user)
    {
        global $_MyCookie;
        global $_BaseURL;
        global $_Config;
        $mailConfig = $_Config->mail;
        $url = sprintf('%suser/confirmRegistration/?key=%s&return=%s'
                , $_BaseURL, $user->getCode(), $_SERVER['HTTP_REFERER']);
        require_once 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = $mailConfig->host;
        $mail->Port = $mailConfig->port;
        $mail->SMTPSecure = $mailConfig->security;
        $mail->Username = $mailConfig->username;
        $mail->Password = $mailConfig->password;
        $mail->setFrom($mailConfig->email, $_Config->name);
        $mail->Subject = utf8_decode(sprintf('%s %s', $_MyCookie->getTranslation('user', 'email.new_subject'), $_Config->name));
        $mail->msgHTML(utf8_decode($_MyCookie->loadView('user', 'email.public', array('user' => $user, 'confirmationLink' => $url), true)));
        $mail->addAddress($user->getEmail());
        $mail->send();
        echo $_MyCookie->getTranslation('user', 'email.check');
    }

    public static function sendEmailForgot(User $user)
    {
        global $_MyCookie;
        global $_BaseURL;
        global $_Config;
        $mailConfig = $_Config->mail;
        $url = sprintf('%suser/confirmForgot/?key=%s&return=%s'
                , $_BaseURL, $user->getCode(), $_SERVER['HTTP_REFERER']);
        require_once 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = $mailConfig->host;
        $mail->Port = $mailConfig->port;
        $mail->SMTPSecure = $mailConfig->security;
        $mail->Username = $mailConfig->username;
        $mail->Password = $mailConfig->password;
        $mail->setFrom($mailConfig->email, $_Config->name);
        $mail->Subject = utf8_decode(sprintf('%s %s', $_MyCookie->getTranslation('user', 'email.forgot_subject'), $_Config->name));
        $mail->msgHTML(utf8_decode($_MyCookie->loadView('user', 'email.forgot', array('user' => $user, 'confirmationLink' => $url), true)));
        $mail->addAddress($user->getEmail());
        $mail->send();
        echo $_MyCookie->getTranslation('user', 'email.check_forgot');
    }

    /**
     * 
     * @return User
     */
    public static function save()
    {
        global $_MyCookie;
        global $_User;
        $user = (empty(filter_input(INPUT_POST, 'id'))) ? new User() :
                User::select('u')->where('u.id =  ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))
                        ->getQuery()->getSingleResult();
        if ($_User->getId() != $user->getId()) {
            UserController::checkAccessLevel('ADMINISTRATOR');
        }
        $user->setName(filter_input(INPUT_POST, 'name'));
        if (!$user->getId()) {
            $user->setEmail(filter_input(INPUT_POST, 'email'));
        }
        if (!empty(filter_input(INPUT_POST, 'newPassword'))) {
            $user->setPassword(filter_input(INPUT_POST, 'newPassword'));
        }
        if (UserController::isAdministratorLoggedIn()) {
            $user->setAccountType(AccountType::select('a')->where('a.id = ?1')
                            ->setParameter(1, filter_input(INPUT_POST, 'accountTypeId'))->getQuery()->getSingleResult());
        }
        $user->save();
        if (filter_input(INPUT_POST, 'emailDetails')) {
            self::sendEmailInternal($user, filter_input(INPUT_POST, 'newPassword'));
        }
        return $user;
    }

    public static function sendEmailInternal(User $user, $newPassword)
    {
        global $_MyCookie;
        global $_Config;
        $mailConfig = $_Config->mail;
        require_once 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->Host = $mailConfig->host;
        $mail->Port = $mailConfig->port;
        $mail->SMTPSecure = $mailConfig->security;
        $mail->Username = $mailConfig->username;
        $mail->Password = $mailConfig->password;
        $mail->setFrom($mailConfig->email, $_Config->name);
        $mail->Subject = utf8_decode(sprintf('%s %s', $_MyCookie->getTranslation('user', 'email.new_subject'), $_Config->name));
        $mail->msgHTML(utf8_decode($_MyCookie->loadView('user', 'email.internal', array('user' => $user, 'password' => $newPassword), true)));
        $mail->addAddress($user->getEmail());
        $mail->send();
    }

    public static function deactivate()
    {
        global $_User;
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        if (self::isAdministratorLoggedIn() || $_User->getId() == $user->getId()) {
            $user->setStatus(0);
            $user->save();
        }
        if ($_User->getId() == $user->getId()) {
            self::logout(false);
        }
    }

    public static function reactivate()
    {
        self::checkAccessLevel('ADMINISTRATOR');
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        $user->setStatus(1);
        $user->save();
    }

    public static function delete()
    {
        self::checkAccessLevel('ADMINISTRATOR');
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        $user->delete();
    }

    public static function checkActualPassword()
    {
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        echo (password_verify(filter_input(INPUT_POST, 'actualPassword'), $user->getPassword())) ? 'true' : 'false';
    }

    public static function changePassword()
    {
        global $_User;
        $user = User::select('u')->where('u.id = ?1')
                        ->setParameter(1, filter_input(INPUT_POST, 'id'))->getQuery()->getSingleResult();
        if ($_User->getId() != $user->getId()) {
            UserController::checkAccessLevel('ADMINISTRATOR');
        }
        $user->setPassword(filter_input(INPUT_POST, 'newPassword'));
        $user->save();
    }

    public function login()
    {
        global $_MyCookie;
        global $_EntityManager;
        $users = User::select('u')
                ->where('u.email = :email')
                ->setParameter('email', filter_input(INPUT_POST, 'email'))
                ->getQuery()
                ->getResult();
        $_SESSION[MyCookie::MESSAGE_SESSION] = $_MyCookie->getTranslation('user', 'message.bad_login');
        if (count($users) == 1) {
            $uPass = filter_input(INPUT_POST, 'password');
            $bPass = $users[0]->getPassword();
            if (password_verify($uPass, $bPass)) {
                if ($users[0]->getStatus()) {
                    $_EntityManager->detach($users[0]);
                    $_SESSION[MyCookie::USER_ID_SESSION] = $users[0]->getId();
                    $_SESSION[MyCookie::MESSAGE_SESSION] = 'OK_LOGIN';
                } else {
                    $_SESSION[MyCookie::MESSAGE_SESSION] = $_MyCookie->getTranslation('user', 'message.deactivated_login');
                }
            }
        }
        header('location:' . $_SERVER['HTTP_REFERER']);
    }

    public static function loadSessionUser()
    {
        global $_User;
        if (isset($_SESSION[MyCookie::USER_ID_SESSION]) &
                !empty($_SESSION[MyCookie::USER_ID_SESSION])) {
            $_User = User::select('u')
                            ->where('u.id = :id')
                            ->setParameter('id', $_SESSION[MyCookie::USER_ID_SESSION])
                            ->getQuery()
                            ->execute()[0];
        }
    }

    public static function logout($redirect = true)
    {
        global $_BaseURL;
        unset($_SESSION[MyCookie::USER_ID_SESSION]);
        unset($_SESSION[MyCookie::MESSAGE_SESSION]);
        if ($redirect) {
            header('location:' . $_BaseURL);
        }
    }

    public static function isAdministratorLoggedIn()
    {
        if (UserController::isUserLoggedIn()) {
            global $_User;
            return ($_User->getAccountType()->getFlag() == 'ADMINISTRATOR');
        }
        return false;
    }

    public static function isUserLoggedIn()
    {
        return isset($_SESSION[MyCookie::USER_ID_SESSION]);
    }

    public static function checkAccessLevel($accessLevel, $_ = null)
    {
        global $_BaseURL;
        global $_User;
        $accessLevel = func_get_args();
        if (!isset($_User) || !in_array($_User->getAccountType()->getFlag(), $accessLevel))
            header('location: ' . $_BaseURL . 'administrator/');
    }

    public static function selectWithFlag($flag)
    {
        global $_MyCookie;
        $users = User::select('u')->join('u.accountType', 'a')->where('a.flag = ?1')->orderBy('u.name')
                        ->setParameter(1, $flag)->getQuery()->getResult();
        $_MyCookie->loadView('user', 'Select', $users);
    }
}
?>
