<?php
    namespace Library;

    class Validation
    {
        private $rule = array();

        public function __construct()
        {
            $this->addRule('username', '^[A-Za-z0-9_.]{5,16}$');
            $this->addRule('password', '^[A-Za-z0-9@#!$*&~;:,?_.-]{6,32}$');
            $this->addRule('email', '^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$');
        }

        public function addRule($name, $rule)
        {
            $this->rule[strtolower($name)] = $rule;
        }

        public function check($name, $subject)
        {
            if (!isset($this->rule[strtolower($name)]))
            {
                return false;
            }

            $regex = '/'.$this->rule[strtolower($name)].'/';
            return (bool)preg_match($regex, $subject);
        }

        public function username($subject)
        {
            return $this->check('username', $subject);
        }

        public function password($subject)
        {
            return $this->check('password', $subject);
        }

        public function email($subject)
        {
            return $this->check('email', $subject);
        }
    }

?>
