<?php

namespace Symsonte\Security;

use Lcobucci\JWT;
use Symsonte\Security\Payload\Claim;

/**
 * @di\service()
 */
class EncodePayload
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @di\arguments({
     *     secret: "%jwt_secret%",
     * })
     *
     * @param string $secret
     */
    public function __construct(
        string $secret
    ) {
        $this->secret = $secret;
    }

    /**
     * @param Claim[] $claims
     *
     * @return string
     */
    public function encode(
        array $claims
    ) {
        $builder = new JWT\Builder();

        foreach ($claims as $claim) {
            $builder = $builder->set($claim->getKey(), $claim->getValue());
        }

        $token = (string) $builder
            ->sign(new JWT\Signer\Hmac\Sha256(), $this->secret)
            ->getToken();

        return $token;
    }
}
