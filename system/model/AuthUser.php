<?php
    namespace Model;

    /**
     * This model is used as model for auth
     */
    class AuthUser
    {
        public function getId($username)
        {
            $result = $this->db->table('user')->where('name', $username)->get('id');
            if ($result->isError())
            {
                return -1;
            }
            return $result->first('id');
        }

        public function hasUser($username='')
        {
            if ($username === '')
            {
                $result = $this->db->table('user')->limit(0,1)->get();
            }
            else
            {
                $result = $this->db->table('user')->where('name', $username)->limit(0,1)->get();
            }
            return !$result->isEmpty();
        }

        public function insert($username, $password)
        {
            $hashPass = page()->encryption->hashPassword($password);
            $this->db->table('user')->insert(['',$username, $hashPass]);

            $id = $this->getId($username);
            if (!$this->hasUser())
            {
                $this->setOption($id, 'privilege', 'admin');
            }
            else
            {
                $this->setOption($id, 'privilege', 'user');
            }
        }

        public function setOption($idUser, $key, $val)
        {
            $result = $this->db->table('user_option')->where('id_user', $idUser)
                ->where('option_key', $key)->get();

            if ($result->isEmpty())
            {
                $this->db->table('user_option')->insert(['', $idUser, $key, $val]);
            }
            else
            {
                $this->db->table('user_option')->where('id_user', $idUser)
                    ->where('option_key', $key)->update();
            }
        }

        public function getOption($idUser, $key, $default='')
        {
            $result = $this->db->table('user_option')->where('id_user', $idUser)
                ->where('option_key', $key)->get();

            if ($result->isEmpty())
            {
                return $default;
            }
            return $result->first('option_value');
        }

        public function verify($username, $password)
        {
            $result = $this->db->table('user')->where('name', $username)->get('password');

            if ($result->isEmpty())
            {
                return false;
            }

            $hashPass = $result->first('password');
            return (page()->encryption->verifyPassword($password, $hashPass));
        }

        public function addSession($username, $hashedToken)
        {
            $id = $this->getId($username);
            $result = $this->db->table('user_session')->insert(['', $id, $hashedToken, time()]);
            return !$result->isError();
        }

        public function verifySession($username, $hashedToken)
        {
            $id = $this->getId($username);
            $result = $this->db->table('user_session')->where('id', $id)
                ->where('id_session', $hashedToken)->get();
            return ($result->isEmpty());
        }
    }

?>
