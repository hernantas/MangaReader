<?php
    namespace Library;

    /**
     * The Encryption class will provide encryption related function including hash or
     * non-hash encryption
     *
     * @package Library
     */
    class Encryption
    {
        /**
         * Hash cost. More will make attack more harder but will cost resource more
         *
         * @var int
         */
        private $hashCost = 10;

        /**
         * Algorithm that used for hash encryption
         *
         * @var int
         */
        private $hashAlgo = "$2a$%02d$";

        /**
         * Salt used as default. This shouldn't be used if possible. Use create_salt
         * instead and store it in the safe place
         *
         * @var string
         */
        private $defaultSalt = '$2a$10$PkQ46MhLcgk4moG0hCm2aA==';

        /**
         * Create some random salt, used for encryption
         *
         * @return string New random salt
         */
        public function createSalt()
        {
            $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
            $salt = sprintf($this->hashAlgo, $this->hashCost) . $salt;
            return $salt;
        }

        /**
         * Do an encryption using hash algorithm
         *
         * @param  string $raw  String that want to be encrypted
         * @param  string $salt Salt used to increase randomness
         * @return string       Encryption result
         */
        public function hash($raw, $salt = '')
        {
            if ($salt === '') $salt = $this->createSalt();
            return crypt($raw, $salt);
        }

        /**
         * Compare hash encryption with raw data.
         *
         * @param  string $hash Hash that want to be compared
         * @param  string $raw  Raw data that want to be compared
         * @return bool         True if raw data is equal to the hash function or false otherwise.
         */
        public function hashEquals($hash, $raw)
        {
            return hash_equals($hash, crypt($raw, $hash));
        }

        /**
         * Do 2-way encryption. It recommended to always generate salt for different
         * encryption process since.
         *
         * @param  string $raw      Raw data that want to be encrypted
         * @param  string $salt     Salt used in encryption. Will use $default_salt if empty
         * @param  string $password Password used to add defined salt
         * @return string           Encrypted string
         * @see Encryption::createSalt()
         */
        public function encrypt($raw, $salt='', $password='')
        {
            if ($salt === '') $salt = $this->defaultSalt;
            $key = hash('SHA256', $salt . $password, true);
            srand();
            // Create $iv and $iv_base64. Block size 128 bits (AES) and CBC mode.
            $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
            if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;

            $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $raw . md5($raw), MCRYPT_MODE_CBC, $iv));
            return $iv_base64 . $encrypted;
        }

        /**
         * Do decryption proccess and return the result. The result will look like
         * weird string if the salt used in an encryption is different.
         *
         * @param  string $encrypted Encrypted data that need to be decrypted
         * @param  string $salt      Salt used in encrypted data.
         * @param  string $password  Password used in encrypted data
         * @return string            Raw data if $salt and $password is the correct one.
         */
        public function decrypt($encrypted, $salt = '', $password = '')
        {
            if ($salt === '') $salt = $this->defaultSalt;
            $key = hash('SHA256', $salt . $password, true);
            // Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
            $iv = base64_decode(substr($encrypted, 0, 22) . '==');
            // Remove $iv from $encrypted.
            $encrypted = substr($encrypted, 22);
            // Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
            $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
            // Retrieve $hash which is the last 32 characters of $decrypted.
            $hash = substr($decrypted, -32);
            // Remove the last 32 characters from $decrypted.
            $decrypted = substr($decrypted, 0, -32);
            // Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
            if (md5($decrypted) != $hash) return false;
            // Yay!
            return $decrypted;
        }
    }
?>
