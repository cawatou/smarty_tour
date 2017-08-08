<?php

DxFactory::import('DxController_Backend');


class DxController_Backend_Master extends DxController_Backend
{
    /** @var array */
    protected $cmd_method = array(
        '.adm'           => 'signIn',
        '.adm.signInAs' => 'signInAs',
        '.adm.signOut'   => 'signOut',
        '.adm.restore'   => 'restore',
    );

    /**
     * @static
     * @return DxController_Backend_Master
     */
    public static function getInstance()
    {
        /** @var $ctx DxAppContext */
        $ctx = DxApp::getComponent(DxApp::ALIAS_APP_CONTEXT);

        return new DxController_Backend_Master($ctx);
    }

    /**
     * @return string
     */
    public function signIn()
    {
        $error_code = null;
        if (isset($_REQUEST['access_denied'])) {
            $error_code = 'USER_ACCESS_DENIED';
        }

        /** @var $auth DxAuthenticator_Project */
        $auth = DxApp::getComponent(DxApp::ALIAS_AUTHENTICATOR);
        $ctx  = $this->getContext();
        $url  = $this->getUrl();

        $default_cmd = DxApp::config('cms', 'default_cmd', true);
        $allow_roles = DxApp::config('cms', 'allow_roles', true);

        if ($error_code == 'USER_ACCESS_DENIED') {
            $auth->setSessId(null, DxAuthenticator_Project::AUTH_SESSION_BACKEND_ID);
        } elseif ($ctx->getCurrentUser()->isUserInRoles($allow_roles)) {
            if (empty($default_cmd[$ctx->getCurrentUser()->getRole()])) {
                throw new DxException('Not known default command');
            }
            DxUrl::redirect($url->url($default_cmd[$ctx->getCurrentUser()->getRole()], true));
        }

        if (isset($_POST['__auth'])) {
            $i = 0;
            while ($i < 1) {
                $i++;
                if (empty($_POST['__login'])) {
                    $error_code = 'USER_LOGIN_EMPTY';
                    break;
                }
                if (empty($_POST['__password'])) {
                    $error_code = 'USER_PASSWORD_EMPTY';
                    break;
                }

                /** @var $q DomainObjectQuery_User */
                $q = DxFactory::getInstance('DomainObjectQuery_User');
                $u = $q->findByLogin($_POST['__login']);

                if (!$u) {
                    $error_code = 'USER_NOT_FOUND';
                    break;
                }

                if ($u->getStatus() != 'ENABLED') {
                    $error_code = 'USER_DISABLED';
                    break;
                }
                $identifier = DxFactory::invoke('DomainObjectModel_User', 'createIdentifier', array($_POST['__login'], $_POST['__password']));

                if ($u->getIdentifier() != $identifier) {
                    $error_code = 'USER_PASSWORD_INVALID';
                    break;
                }

                $auth->setSessId($u->getId(), DxAuthenticator_Project::AUTH_SESSION_BACKEND_ID);

                if (empty($default_cmd[$u->getRole()])) {
                    throw new DxException('Not known default command');
                }

                DxUrl::redirect($url->url($default_cmd[$u->getRole()], true));
            }
        }

        /** @var $smarty Smarty */
        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'error_code' => $error_code,
        ));

        $html = $smarty->fetch('backend/master_signin.tpl.php');
        return $this->wrap($html, array(), 'SIGNIN');
    }

    /**
     * @return string
     */
    public function signInAs()
    {
        /** @var $auth DxAuthenticator_Project */
        $auth = DxApp::getComponent(DxApp::ALIAS_AUTHENTICATOR);
        $url  = $this->getUrl();

        $user_id = empty($_REQUEST['user_id']) ? null : $_REQUEST['user_id'];

        /** @var $q DomainObjectQuery_User */
        $q = DxFactory::getInstance('DomainObjectQuery_User');
        $u = $q->findById($user_id);

        if (empty($u)) {
            throw new DxException('Unknown user');
        }

        if ($u->getStatus() != 'ENABLED') {
            throw new DxException('User is disabled');
        }

        $auth->setSessId($u->getId(), DxAuthenticator_Project::AUTH_SESSION_BACKEND_ID);

        $default_cmd = DxApp::config('cms', 'default_cmd', true);

        if (empty($default_cmd[$u->getRole()])) {
            throw new DxException('Not known default command');
        }

        DxUrl::redirect($url->url($default_cmd[$u->getRole()], true));
    }

    /**
     * @return void
     */
    protected function signOut()
    {
        /** @var $auth DxAuthenticator_Project */
        $auth = DxApp::getComponent(DxApp::ALIAS_AUTHENTICATOR);
        $auth->setSessId(null, DxAuthenticator_Project::AUTH_SESSION_BACKEND_ID);

        DxURL::redirect($this->getUrl()->adm());
    }

    /**
     * @return string
     */
    public function restore()
    {
        /** @var $q DomainObjectQuery_User */
        $q = DxFactory::getInstance('DomainObjectQuery_User');

        $error_code = null;
        $success_code = null;
        if (isset($_REQUEST['success'])) {
            $success_code = 'CHECK_EMAIL';
        }

        if (!empty($_REQUEST['code'])) {
            $u = $q->findByIdentifier($_REQUEST['code']);
            if (is_null($u)) {
                $error_code = 'CODE_NOT_FOUND';
            } else {
                $password = rand(100000, 900000);
                $identifier = DxFactory::invoke('DomainObjectModel_User', 'createIdentifier', array($u->getLogin(), $password));
                $u->setIdentifier($identifier);
                $this->getDomainObjectManager()->flush();

                $success_code = 'OKAY';
                $mail = DxFactory::getInstance('Utils_Mail');
                $data = array(
                    'for' => 'RESTORE_OKAY',
                    'password' => $password,
                );

                $subject = Utils_Mail::textOfTemplate('backend/mail/subject.tpl.php', $data);
                $body = Utils_Mail::textOfTemplate('backend/mail/body.tpl.php', $data);

                try {
                    $mail->send($u->getLogin(), '', $subject, $body);
                } catch (DxException $e) {
                }
            }
        }

        if (!empty($_POST['__restore'])) {
            $i = 0;
            while ($i < 1) {
                $i++;
                if (empty($_POST['__login'])) {
                    $error_code = 'USER_LOGIN_EMPTY';
                    break;
                }

                $u = $q->findByLogin($_POST['__login']);

                if (!$u) {
                    $error_code = 'USER_NOT_FOUND';
                    break;
                }

                if ($u->getStatus() != 'ENABLED') {
                    $error_code = 'USER_DISABLED';
                    break;
                }

                /** @var $mail Utils_Mail */
                $mail = DxFactory::getInstance('Utils_Mail');
                $data = array(
                    'for' => 'RESTORE',
                    'code' => $u->getIdentifier(),
                );

                $subject = Utils_Mail::textOfTemplate('backend/mail/subject.tpl.php', $data);
                $body = Utils_Mail::textOfTemplate('backend/mail/body.tpl.php', $data);

                try {
                    $mail->send($u->getLogin(), '', $subject, $body);
                } catch (DxException $e) {
                }

                $this->getUrl()->redirect($this->getUrl()->adm('.restore', '?success'));
            }
        }

        $smarty = $this->getSmarty();
        $smarty->assign(array(
            'error_code' => $error_code,
            'success_code' => $success_code,
        ));

        $html = $smarty->fetch('backend/master_restore.tpl.php');
        return $this->wrap($html, array(), 'SIGNIN');
    }
}