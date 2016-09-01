<?php
    namespace Library;

    class Auth
    {
        private $key = '';

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
