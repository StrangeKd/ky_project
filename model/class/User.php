<?php
class User extends Form
{
    private $userData;

    public function __construct(array $data, array $input, ?string $currentPage = null, ?string $currentAction = null)
    {
        parent::__construct($data, $input);
        $this->currentPage = $currentPage;
        $this->currentAction = $currentAction;
    }

    public function init()
    {
        parent::init();
        if ($this->currentPage === 'register' && $this->success() === true) {
            echo($this->logMsg('Your account has been registered successfully'));
            header('Refresh:1; url=https://ryandeprez.sites.3wa.io/final_project/ky/index.php');
            exit();
        } else if ($this->currentPage === 'login' && $this->success() === true) {
            echo($this->logMsg("You're now logged in " . $_SESSION['username']));
            header('Refresh:1; url=https://ryandeprez.sites.3wa.io/final_project/ky/index.php');
            exit();
        } else if ($this->currentPage === 'account' && $this->currentAction === 'modify' && $this->success() === true) {
            $this->logMsg('Your password has been changed successfully');
        } else if ($this->currentPage === 'account' && $this->currentAction === 'delete' && isset($this->input['delete']['value'])) {
            $this->deleteAccount();
            $this->input['delete']['value'] = '';
            $this->clearData();
            $form = $this->generateForm();
            return $form;
        } else {
            if (isset($this->input['password']['value'])) {
                $this->input['password']['value'] = '';
            }
            $this->clearData();
            $form = $this->generateForm();
            return $form;
        }
    }

    private function success()
    {
        if ($this->currentPage === 'register') {
            if ($this->register() === true) {
                $this->clearData();
                return true;
            }
        } else if ($this->currentPage === 'login') {
            if ($this->login() === true) {
                $this->clearData();
                return true;
            }
        } else if ($this->currentPage === 'account') {
            if ($this->modifyPassword() === true) {
                $this->clearData();
                return true;
            }
        }
    }

    private function isLogsAvailable()
    {
        foreach ($this->input as $key => $value) {
            if ($key === 'username' || $key === 'email') {
                $userLog = isset($this->input[$key]['value']) ? $this->input[$key]['value'] : null;
                if ($userLog !== null) {
                    $params = [
                        'username' => $userLog
                    ];
                    $query = $this->prepare('SELECT username FROM users WHERE username = :username', $params, true);
                    if ($query !== false) {
                        $this->logMsg('Username already taken');
                    } else {
                        $params = [
                            'email' => $userLog
                        ];
                        $query = $this->prepare('SELECT email FROM users WHERE email = :email', $params, true);
                        if ($query !== false) {
                            $this->logMsg('Email already taken');
                        }
                    }
                }
            }
        }
        if (isset($this->input['username']['value']) && isset($this->input['email']['value']) && isset($this->input['password']['value'])) {
            if (!empty($this->msg)) {
                return false;
            } else {
                return true;
            }
        }
    }

    private function getUserData(?string $login = null)
    {
        if (isset($login)) {
            $params = [
                'username' => $login
            ];
            $query = $this->prepare('SELECT username, email, password, role_id FROM users WHERE username = :username', $params, true);
            if ($query !== false) {
                $this->userData = $query;
                return true;
            } else {
                $params = [
                    'email' => $login
                ];
                $query = $this->prepare('SELECT username, email, password, role_id FROM users WHERE email = :email', $params, true);
                if ($query !== false) {
                    $this->userData = $query;
                    return true;
                }
            }
            return false;
        }
    }

    private function isPasswordCorrect()
    {
        $log = isset($this->input['log']['value']) ? $this->input['log']['value'] : null;
        $this->getUserData($log);
        if ($this->userData !== null) {
            if (isset($this->input['password']['value'])) {
                if (password_verify($this->input['password']['value'], $this->userData['password'])) {
                    return true;
                }
            }
        }
    }

    private function register()
    {
        if ($this->isLogsAvailable() === true) {
            $username = isset($this->input['username']['value']) ? htmlentities($this->input['username']['value']) : null;
            $password = isset($this->input['password']['value']) ? htmlentities(password_hash($this->input['password']['value'], PASSWORD_DEFAULT)) : null;
            $email = isset($this->input['email']['value']) ? htmlentities($this->input['email']['value']) : null;
            $register_date = $this->currentDatetime;
            $params = [
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'register_date' => $register_date
            ];
            $this->prepare('INSERT INTO users (username, password, email, register_date) VALUE (:username, :password, :email, :register_date)', $params, true);
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['access_admin'] = false;
            return true;
        }
    }

    private function login()
    {
        if (isset($this->input['log']['value'])) {
            if ($this->isPasswordCorrect() === true) {
                $_SESSION['access_admin'] = ($this->userData['role_id'] > 1) ? true : false;
                $_SESSION['username'] = $this->userData['username'];
                return true;
            } else {
                $this->logMsg('Wrong login or password');
            }
        }
    }

    private function modifyPassword()
    {
        $this->getUserData($_SESSION['username']);
        if ($this->userData !== null) {
            $currentPass = isset($this->input['current_password']['value']) ? $this->input['current_password']['value'] : null;
            $newPass1 = isset($this->input['new_password1']['value']) ? $this->input['new_password1']['value'] : null;
            $newPass2 = isset($this->input['new_password2']['value']) ? $this->input['new_password2']['value'] : null;
            if ($currentPass !== null && $newPass1 !== null && $newPass2 !== null) {
                if (password_verify($currentPass, $this->userData['password'])) {
                    if ($newPass1 === $newPass2) {
                        $newPass2 = password_hash($newPass2, PASSWORD_DEFAULT);
                        $params = [
                            'username' => $_SESSION['username'],
                            'password' => $newPass2
                        ];
                        $this->prepare('UPDATE users SET password = :password WHERE username = :username', $params, true);
                        return true;
                    } else {
                        $this->logMsg('Please retype the same password');
                    }
                } else {
                    $this->logMsg('Wrong password');
                }
            }
        }
    }

    private function deleteAccount()
    {
        $delete = isset($this->input['delete']['value']) ? $this->input['delete']['value'] : null;
        if (isset($delete)) {
            if ($delete === 'DELETE') {
                $this->getUserData($_SESSION['username']);
                if ($this->userData !== null) {
                    $params = [
                        'username' => $this->userData['username']
                    ];
                    $this->prepare('DELETE FROM users WHERE username = :username', $params, true);
                    $_SESSION = array();
                    session_unset();
                    session_destroy();
                    header('Location: index.php');
                    exit();
                }
            } else {
                $this->logMsg('Please type DELETE');
            }
        }
    }
}
