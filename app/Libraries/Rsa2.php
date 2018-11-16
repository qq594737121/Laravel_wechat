<?php

namespace App\Libraries;

class Rsa2
{
    private $PRIVATE_KEY = '';
    private $PUBLIC_KEY = '';

    public function __construct($privateKeyPath = '', $PublicKeyPath = '')
    {
        if (is_file($privateKeyPath) && is_readable($privateKeyPath)) {
            $this->PRIVATE_KEY = file_get_contents($privateKeyPath);
        }

        if (is_file($PublicKeyPath) && is_readable($PublicKeyPath)) {
            $this->PUBLIC_KEY = file_get_contents($PublicKeyPath);
        }

        return $this;
    }

    /**
     * 创建签名
     * @param string $data 数据
     * @return null|string
     */
    public function createSign($data = '')
    {
        if (!is_string($data)) {
            return null;
        }
        return openssl_sign(
            $data,
            $sign,
            $this->getPrivateKey(),
            OPENSSL_ALGO_SHA256
        ) ? base64_encode($sign) : null;
    }

    /**
     * 获取私钥
     * @return bool|resource
     */
    private function getPrivateKey()
    {
        return openssl_pkey_get_private($this->PRIVATE_KEY);
    }

    /**
     * 验证签名
     * @param string $data 数据
     * @param string $sign 签名
     * @return bool
     */
    public function verifySign($data = '', $sign = '')
    {
        if (!is_string($sign) || !is_string($sign)) {
            return false;
        }
        return (bool)openssl_verify(
            $data,
            base64_decode($sign),
            $this->getPublicKey(),
            OPENSSL_ALGO_SHA256
        );
    }

    /**
     * 获取公钥
     * @return bool|resource
     */
    private function getPublicKey()
    {
        return openssl_pkey_get_public($this->PUBLIC_KEY);
    }

}
