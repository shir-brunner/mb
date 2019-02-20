<?php

class Security extends CApplicationComponent
{
    public $passwordHashStrategy = 'password_hash';
    public $passwordHashCost = 13;

    public function generatePasswordHash($password, $cost = null)
    {
        if ($cost === null) {
            $cost = $this->passwordHashCost;
        }

        switch ($this->passwordHashStrategy) {
            case 'password_hash':
                if (!function_exists('password_hash')) {
                    throw new Exception('Password hash key strategy "password_hash" requires PHP >= 5.5.0, either upgrade your environment or use another strategy.');
                }
                /** @noinspection PhpUndefinedConstantInspection */
                return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
            case 'crypt':
                $salt = $this->generateSalt($cost);
                $hash = crypt($password, $salt);
                // strlen() is safe since crypt() returns only ascii
                if (!is_string($hash) || strlen($hash) !== 60) {
                    throw new Exception('Unknown error occurred while generating hash.');
                }
                return $hash;
            default:
                throw new Exception("Unknown password hash strategy '{$this->passwordHashStrategy}'");
        }
    }

    public function validatePassword($password, $hash)
    {
        if (!is_string($password) || $password === '') {
            return false;
            //throw new Exception('Password must be a string and cannot be empty.');
        }

        if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches) || $matches[1] < 4 || $matches[1] > 30) {
            throw new Exception('Hash is invalid.');
        }

        switch ($this->passwordHashStrategy) {
            case 'password_hash':
                if (!function_exists('password_verify')) {
                    throw new Exception('Password hash key strategy "password_hash" requires PHP >= 5.5.0, either upgrade your environment or use another strategy.');
                }
                return password_verify($password, $hash);
            case 'crypt':
                $test = crypt($password, $hash);
                $n = strlen($test);
                if ($n !== 60) {
                    return false;
                }
                return $this->compareString($test, $hash);
            default:
                throw new Exception("Unknown password hash strategy '{$this->passwordHashStrategy}'");
        }
    }

    public function compareString($expected, $actual)
    {
        $expected .= "\0";
        $actual .= "\0";
        $expectedLength = StringHelper::byteLength($expected);
        $actualLength = StringHelper::byteLength($actual);
        $diff = $expectedLength - $actualLength;
        for ($i = 0; $i < $actualLength; $i++) {
            $diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
        }
        return $diff === 0;
    }

    protected function generateSalt($cost = 13)
    {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            throw new Exception('Cost must be between 4 and 31.');
        }

        // Get a 20-byte random string
        $rand = $this->generateRandomKey(20);
        // Form the prefix that specifies Blowfish (bcrypt) algorithm and cost parameter.
        $salt = sprintf("$2y$%02d$", $cost);
        // Append the random salt data in the required base64 format.
        $salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));

        return $salt;
    }

    public function generateRandomKey($length = 32)
    {
        /*
         * Strategy
         *
         * The most common platform is Linux, on which /dev/urandom is the best choice. Many other OSs
         * implement a device called /dev/urandom for Linux compat and it is good too. So if there is
         * a /dev/urandom then it is our first choice regardless of OS.
         *
         * Nearly all other modern Unix-like systems (the BSDs, Unixes and OS X) have a /dev/random
         * that is a good choice. If we didn't get bytes from /dev/urandom then we try this next but
         * only if the system is not Linux. Do not try to read /dev/random on Linux.
         *
         * Finally, OpenSSL can supply CSPR bytes. It is our last resort. On Windows this reads from
         * CryptGenRandom, which is the right thing to do. On other systems that don't have a Unix-like
         * /dev/urandom, it will deliver bytes from its own CSPRNG that is seeded from kernel sources
         * of randomness. Even though it is fast, we don't generally prefer OpenSSL over /dev/urandom
         * because an RNG in user space memory is undesirable.
         *
         * For background, see http://sockpuppet.org/blog/2014/02/25/safely-generate-random-numbers/
         */

        $bytes = '';

        // If we are on Linux or any OS that mimics the Linux /dev/urandom device, e.g. FreeBSD or OS X,
        // then read from /dev/urandom.
        if (@file_exists('/dev/urandom')) {
            $handle = fopen('/dev/urandom', 'r');
            if ($handle !== false) {
                $bytes .= fread($handle, $length);
                fclose($handle);
            }
        }

        if (StringHelper::byteLength($bytes) >= $length) {
            return StringHelper::byteSubstr($bytes, 0, $length);
        }

        // If we are not on Linux and there is a /dev/random device then we have a BSD or Unix device
        // that won't block. It's not safe to read from /dev/random on Linux.
        if (PHP_OS !== 'Linux' && @file_exists('/dev/random')) {
            $handle = fopen('/dev/random', 'r');
            if ($handle !== false) {
                $bytes .= fread($handle, $length);
                fclose($handle);
            }
        }

        if (StringHelper::byteLength($bytes) >= $length) {
            return StringHelper::byteSubstr($bytes, 0, $length);
        }

        if (!extension_loaded('openssl')) {
            throw new InvalidConfigException('The OpenSSL PHP extension is not installed.');
        }

        $bytes .= openssl_random_pseudo_bytes($length, $cryptoStrong);

        if (StringHelper::byteLength($bytes) < $length || !$cryptoStrong) {
            throw new Exception('Unable to generate random bytes.');
        }

        return StringHelper::byteSubstr($bytes, 0, $length);
    }

    public function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(64));
    }

    public function validateToken($string,$hash)
    {
        return md5($string) == $hash;
    }

    public function isPasswordStrong($password)
    {
        if (preg_match("#.*^(?=.{8,20})(?=.*[A-Za-z])(?=.*[0-9]).*$#", $password))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function verifyReCaptcha()
    {
        if(!empty($_REQUEST['captcha']))
        {
            $fields = array(
                'secret' => '6LcjkiUTAAAAAJ7uVzUCYPV-9j3Hh-Z8ywj4GRFY',
                'response' => $_REQUEST['captcha'],
                'remoteip' => $_SERVER['REMOTE_ADDR'],
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);

            curl_close($ch);

            return json_decode($result);
        }

        return (object) array('success' => false);
    }

    function randomStr($minLength = 8)
    {
        if($minLength < 8)
        {
            $minLength = 8; //$minLength can't be lowe than 8. this is why it is allowed to pass a min length and not length.
        }
        $string = bin2hex(random_bytes($minLength * 2));
        $string = substr($string, 0, $minLength - 4);

        $uppercase = range('A', 'Z');
        $lowerCase = range('a', 'z');
        $numbers = random_int(10,99);

        return str_shuffle($string . $numbers . $uppercase[mt_rand(0, count($uppercase) - 1)] . $lowerCase[mt_rand(0, count($uppercase) - 1)]);
    }
}

