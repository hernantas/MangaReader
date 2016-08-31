<?php
    namespace Library;

    class Auth
    {
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
                $table->string('id_session');
                $table->int('last_access');
            });
        }

        /**
         * Uninstall database table
         */
        public function uninstall()
        {

        }
    }

?>
