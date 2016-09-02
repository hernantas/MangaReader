<?php
    namespace Library;

    class Auth
    {
        private $key = '';

        private $isLoginCheck = false;

        private $isLogin = false;

        private $user = array();

        private $userOption = false;

        public function __construct()
        {
            // Load Model Auth as User
            page()->load->library('Encryption');
            page()->load->library('Session');
            page()->load->model('AuthUser');

            $cfg = page()->config->load('Auth');
            if ($cfg === false)
            {
                $cfg['key'] = page()->encryption->createKey(32);
                page()->config->save('Auth', $cfg);
            }

            $this->key = $cfg['key'];
        }

        public function addUser($username, $password)
        {
            $user =& page()->authuser;

            if ($user->hasUser($username))
            {
                return "Username already exists";
            }

            $user->insert($username, $password);
            return true;
        }

        public function createSession($username, $password, $persist=false)
        {
            if (page()->authuser->verify($username, $password))
            {
                $token = page()->encryption->createKey();
                $mac = hash_hmac('sha256', "$username:$token", $this->key);
                page()->authuser->addSession($username, $mac);

                page()->session->set('username', $username, $persist);
                page()->session->set('token', $token, $persist);
                return true;
            }

            return false;
        }

        public function isLoggedIn()
        {
            if ($this->isLoginCheck)
            {
                return $this->isLogin;
            }

            $this->isLoginCheck = true;
            $username = page()->session->get('username');
            $token = page()->session->get('token');

            if ($username === '' || $token === '')
            {
                return false;
            }

            $mac = hash_hmac('sha256', "$username:$token", $this->key);

            if (!page()->authuser->verifySession($username, $mac))
            {
                return false;
            }

            $this->isLogin = true;
            return true;
        }

        public function getUser()
        {
            if (isset($this->user['username']))
            {
                return $this->user['username'];
            }
            if ($this->isLoggedIn())
            {
                $this->user['username'] = page()->session->get('username');
                return $this->user['username'];
            }
            return false;
        }

        public function getUserId()
        {
            if (isset($this->user['id']))
            {
                return $this->user['id'];
            }
            if ($this->isLoggedIn())
            {
                $this->user['id'] = page()->authuser->getId($this->getUser());
                return $this->user['id'];
            }
            return -1;
        }

        public function getUserOption($optionKey, $default='')
        {
            if ($this->userOption !== false)
            {
                return isset($this->userOption[$optionKey]) ? $this->userOption[$optionKey] :
                    $default;
            }

            if ($this->isLoggedIn())
            {
                $this->userOption = page()->authuser->getOption($this->getUserId());
            }

            return isset($this->userOption[$optionKey]) ? $this->userOption[$optionKey] :
                $default;
        }

        public function setUserOption($key, $value)
        {
            if ($this->isLoggedIn())
            {
                page()->authuser->setOption($this->getUserId(), $key, $value);
                $this->userOption[$key] = $value;
            }
        }

        public function removeSession()
        {
            $username = page()->session->get('username');
            $token = page()->session->get('token');

            if ($username === '' || $token === '')
            {
                return false;
            }

            $mac = hash_hmac('sha256', "$username:$token", $this->key);

            page()->authuser->removeSession($username, $mac);
            page()->session->remove('username');
            page()->session->remove('token');
        }

        public function requireLogin($redirect='')
        {
            if (!$this->isLoggedIn())
            {
                page()->router->redirect($redirect);
            }
        }

        public function requireNoLogin($redirect='')
        {
            if ($this->isLoggedIn())
            {
                page()->router->redirect($redirect);
            }
        }

        /**
         * Install database table to be used for this library
         */
        public function install()
        {
            page()->db->schema->create('user', function ($table)
            {
                $table->increment('id');
                $table->string('name', 16)->unique();
                $table->string('password', 64);
            });
            page()->db->schema->create('user_option', function ($table)
            {
                $table->increment('id');
                $table->int('id_user')->index();
                $table->string('option_key');
                $table->string('option_value');
            });
            page()->db->schema->create('user_session', function ($table)
            {
                $table->increment('id');
                $table->int('id_user')->index();
                $table->string('session_token');
                $table->int('last_access');
            });
        }

        /**
         * Uninstall database table
         */
        public function uninstall()
        {
            page()->db->schema->drop('user');
            page()->db->schema->drop('user_option');
            page()->db->schema->drop('user_session');
        }
    }

?>
