<?php
    namespace Library;

    /**
     * The Encryption class will provide encryption related function including hash or
     * non-hash encryption. Require MCrypt and MCRYPT_DEV_URANDOM
     *
     * @package Library
     */
    class Encryption
    {
        private $key = '';

        private $hashAlgo = 'sha1';

        private $chiper = MCRYPT_RIJNDAEL_128;
        private $mode = MCRYPT_MODE_CBC;

        public function __construct()
        {
            $cfg = page()->config->load('Encryption');

            if ($cfg === false || !isset($cfg['key']))
            {
                $cfg['key'] = $this->createKey(32);
                if (!isset($cfg['chiper'])) $cfg['chiper'] = MCRYPT_RIJNDAEL_128;
                if (!isset($cfg['mode'])) $cfg['mode'] = MCRYPT_MODE_CBC;

                page()->config->save('Encryption', $cfg);
            }

            $this->key = $cfg['key'];
            $this->chiper = isset($cfg['chiper']) ? $cfg['chiper'] : MCRYPT_RIJNDAEL_128;
            $this->mode = isset($cfg['mode']) ? $cfg['mode'] : MCRYPT_MODE_CBC;;
        }

        /**
         * Create encryption key
         *
         * @param  int    $size Key size in byte
         * @param  string $raw  If set to true, output will return raw output (binary)
         *                      Otherwise will return base 64 string
         * @return string       Return key that has been parsed to base 64 if $raw is false,
         *                      return binary
         */
        public function createKey($size=16, $raw=false)
        {
            $iv = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
            if ($raw)
            {
                return $iv;
            }
            return base64_encode($iv);
        }

        /**
         * Perform 2 way encryption
         *
         * @param  string $message Message that want to be encrypted
         *
         * @return string          Encrypted message
         */
        public function encrypt($message)
        {
            $key = base64_decode($this->key);
            $iv = $this->createKey(mcrypt_get_iv_size($this->chiper, $this->mode), true);
            $chipper = mcrypt_encrypt($this->chiper, $key, $message, $this->mode, $iv);
            return base64_encode($iv.$chipper);
        }

        /**
         * Perform decryption
         *
         * @param  string $chipper Encrypted message
         *
         * @return string          Decrypted message if success
         */
        public function decrypt($chipper)
        {
            $chipper = base64_decode($chipper);
            $key = base64_decode($this->key);
            $iv = substr($chipper, 0, mcrypt_get_iv_size($this->chiper, $this->mode));
            $chipper = substr($chipper, mcrypt_get_iv_size($this->chiper, $this->mode));

            $message = mcrypt_decrypt($this->chiper, $key, $chipper, $this->mode, $iv);

            return $message;
        }
        /**
         * Generate a hash string from input data. Don't use this method to encrypt
         * password, instead use Encryption::hashPassword
         *
         * @param  string $message Message to be hashed
         *
         * @return string          Hashed message
         */
        public function hash($message)
        {
            return hash($this->hashAlgo, $message);
        }

        /**
         * Get all list of registered hashing algorithm
         *
         * @return array List of hashing algorithm
         */
        public function hashAlgo()
        {
            return hash_algos();
        }

        /**
         * Check if hash is equal to each other or not
         *
         * @param  string $str1 First hash to be compared
         * @param  string $str2 Second hash
         *
         * @return bool         Return false if not same, true otherwise.
         */
        public function hashEquals($str1, $str2)
        {
            if(strlen($str1) != strlen($str2))
            {
                return false;
            }
            else
            {
                $res = $str1 ^ $str2;
                $ret = 0;
                for($i = strlen($res) - 1; $i >= 0; $i--)
                {
                    $ret |= ord($res[$i]);
                }
                return !$ret;
            }
        }

        /**
         * Generate a hash string from input data. This more targeted to be used
         * for specific hashing like password or key. Algorithm to be used in hashing
         * is Blowfish. Use Encryption::verifyPassword for verifying password/key
         *
         * @param  string $password  String that want to be encrypted
         * @param  string $salt      Salt used to increase randomness
         * @return string            Encryption result
         */
        public function hashPassword($password)
        {
            return password_hash($password, PASSWORD_BCRYPT);
        }

        /**
         * Verify password matches a hash
         *
         * @param  string $password Password
         * @param  string $hash     A hash create by Encryption::hashPassword
         *
         * @return bool             True if password match, false otherwise
         */
        public function verifyPassword($password, $hash)
        {
            return password_verify($password, $hash);
        }
    }
?>
