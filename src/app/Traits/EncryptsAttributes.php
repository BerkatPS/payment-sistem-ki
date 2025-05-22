<?php

namespace App\Traits;

use App\Helpers\EncryptionHelper;

trait EncryptsAttributes
{

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable ?? [])) {
            return EncryptionHelper::decrypt($value);
        }

        return $value;
    }


    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable ?? []) && !is_null($value)) {
            $value = EncryptionHelper::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }


    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        foreach ($this->encryptable ?? [] as $key) {
            if (isset($attributes[$key])) {
                $attributes[$key] = EncryptionHelper::decrypt($attributes[$key]);
            }
        }

        return $attributes;
    }
}
