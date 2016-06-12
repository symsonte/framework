<?php

namespace Symsonte\Security;

use Lcobucci\JWT;

/**
 * @di\service()
 */
class DecodePayload
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
     * @param string $token
     * @param string $key The claim key
     *
     * @return mixed The claim
     *
     * @throws InvalidPayloadException
     */
    public function decode(
        string $token,
        string $key
    ) {
        $token = (new JWT\Parser())->parse($token);

        if (!$token->verify(
            (new JWT\Signer\Hmac\Sha256()),
            $this->secret
        )) {
            throw new InvalidPayloadException();
        }

        return $token->getClaim($key);
    }
}
