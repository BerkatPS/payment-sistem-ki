<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class EncryptionHelper
{
    /**
     * Encrypt a value if it's not already encrypted
     *
     * @param string|null $value
     * @return string|null
     */
    public static function encrypt(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (static::isEncrypted($value)) {
            return $value;
        }

        return Crypt::encryptString($value);
    }

    /**
     * Decrypt a value if it's encrypted
     *
     * @param string|null $value
     * @return string|null
     */
    public static function decrypt(?string $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        if (!static::isEncrypted($value)) {
            return $value;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return $value; // Return original value if decryption fails
        }
    }

    /**
     * Check if a value is already encrypted
     *
     * @param string $value
     * @return bool
     */
    public static function isEncrypted(string $value): bool
    {
        if (empty($value)) {
            return false;
        }

        try {
            $decoded = json_decode(base64_decode($value), true);
            return is_array($decoded) && 
                   isset($decoded['iv'], $decoded['value'], $decoded['mac']) && 
                   strlen($decoded['iv']) === 16 && 
                   strlen($decoded['mac']) === 64;
        } catch (\Exception $e) {
            return false;
        }
    }
}
