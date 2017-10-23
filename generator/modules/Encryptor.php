<?php
/**
 * Created by PhpStorm.
 * User: DominikP
 * Date: 24.05.2017
 * Time: 15:01
 */

namespace Modules;


class Encryptor
{
    private $private_key = '-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQBeOSwwG9LRe2SrWUW+EEG/+bkm1UWIPV9gPoiKblzI5O8eH00q
NyycgIpiA8KwLq54XrGwwoTKFTRKgT8eHURXJjPad4NnkTEiPptAd9TO+cX0ulzS
nYIpbQWu82TK1Piu0BN03lIOi3cLHbEB2eCJKYqqKIwk1GD7UUVd7kkA8BkztnZI
6MN7+Ejsi/P06GBCU+uooCQaJeZ8uE4Pju59Ml5oKVbaMo3f37d0zqs3vSND/gYF
aDMyOLqqhhfh/o1a/sjn9zjEBfu6RVSMnMA359+hUf//RBXxoNXTO9wAA8HXb+rw
l07ufwHhIyjvdwGQh3Worv5LAY9A0TRvKlDNAgMBAAECggEAQ6/+tnDGegaSwyVW
nLtBAJB9GhCL6ttr8M/7drbhoNAVlyYVYQZR3fvZbaAV9EsUuxQLwld63VFuRzei
CBguSA0BoyfIFlaPXXJsRVvQXP/B3ZUjqYnNP8U6F/xxo+Rq4epIj+RGPnyiADJM
PnAuhVp6atLGGo8XPHOdyWSsvSbcLRrtyH4nlQaLE9FcE/6K2vprFjMYs/K62kn9
LSlwblWu6I1OxwaYDrXLQ0+QEGmQRa2QbhaK0b5Jdr+h9ARrWCT2RQ6k9k6Kj/IV
0F5qntYknvZMXWGc/s0WOnSkzcsoX5lahS/QcC8hKDr+iigrgb3VCRxbuVY0kBFl
KvudAQKBgQC3ms1kl7dHtA6BSE9R7nvsO5c8i4enC6r1qgGJ1DPHykpP3QQfrcVu
PE9euaVQkMcInmea0ERmN4e+qBxzHfyff/kVHfHL0uN9ryK1+L4cSNnkyvA25yhd
nJeBsa7Vv34T1gPWAIW0SYsh6UAiIlcyyGQwmwWta+33fjC4om3eSQKBgQCDYCWx
aDi/aCKth0jxsFJyPAo2WmXLcxP7Uqp1dQuO+HlR2SmGlO23QKfTe7j5YqglyXy4
ZqYff338BooMMJfsDBbOfMf98gd5T2fXPs2osLbfiO9yxPJLcseHVWNEnk8vA0iH
yW19aX1qeiXktmFrSTI9k7jc3kh1DmptdtyuZQKBgAUFNpiJjXlocv0V/RVAcCMF
fh0ps4vdxc7x1xjttHzCC0YjxPkYx008WO8+JEcPrmGN80dIapZg7lVIQ37iUwYJ
ecYaC4KHQbEuGMThCFG9rDeVVPwrVe85WOsy8JTnp4rjTzeKzB0h5awHtLk6Nygs
UpEBrCYRzW/3A14JEvgBAoGBAIGQO/w3defHEpQinJzHMLg6YDP0ePnpdyZgA34t
PYX1pOhEjECO9dXJbPoTxXwlgQErMdZGXcUonh4EcPbOobWIwqFHzJC0tgw8dIOC
C583EFNqIAQHGZGxE/hzDKBqpFsQGOLZkyKg+7Ab4O0rtO3y1RqHakRg8AKJggqM
s91FAoGAbiHw7qPphKX2uUR/xO+Mq7d90Av33H6p5sauS/bH4SjLmMeyQbTPpLfk
tyV6V/lpeZbTwD1OWtLa4NMH/OtQqZCa5ie88yo58OfZJe9PTLwXzrKoFvefu3Pn
Hj2D2gL++MQhRMwn6EBu/oTM2LiPnOPEv1zIzsJTtipdWY9Md6U=
-----END RSA PRIVATE KEY-----';
    private $public_key = '-----BEGIN PUBLIC KEY-----
MIIBITANBgkqhkiG9w0BAQEFAAOCAQ4AMIIBCQKCAQBeOSwwG9LRe2SrWUW+EEG/
+bkm1UWIPV9gPoiKblzI5O8eH00qNyycgIpiA8KwLq54XrGwwoTKFTRKgT8eHURX
JjPad4NnkTEiPptAd9TO+cX0ulzSnYIpbQWu82TK1Piu0BN03lIOi3cLHbEB2eCJ
KYqqKIwk1GD7UUVd7kkA8BkztnZI6MN7+Ejsi/P06GBCU+uooCQaJeZ8uE4Pju59
Ml5oKVbaMo3f37d0zqs3vSND/gYFaDMyOLqqhhfh/o1a/sjn9zjEBfu6RVSMnMA3
59+hUf//RBXxoNXTO9wAA8HXb+rwl07ufwHhIyjvdwGQh3Worv5LAY9A0TRvKlDN
AgMBAAE=
-----END PUBLIC KEY-----';

    public function encrypt($string)
    {
        if (openssl_public_encrypt($string, $encrypted, $this->public_key)) {
            $data = $this->base64_url_encode($encrypted);
        } else {
            throw new \Exception('Nie można zaszyfrować danych. Prawdopodobnie klucz publiczny jest zbyt krótki');
        }

        return $data;
    }

    public function decrypt($string)
    {
        if (openssl_private_decrypt($this->base64_url_decode($string), $decrypted, $this->private_key)) {
            $data = $decrypted;
        } else {
            throw new \Exception('Nie można zdeszyfrować danych. Prawdopodobnie dane są uszkodzone');
        }

        return $data;
    }

    function base64_url_encode($input)
    {
        return strtr(base64_encode($input), '+/=', '-_,');
    }

    function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_,', '+/='));
    }
}