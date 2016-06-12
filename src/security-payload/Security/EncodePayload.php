<?php

namespace Symsonte\Security;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;
use Exception;
use LogicException;
use Symsonte\Security\Payload\Claim;

/**
 * @di\service()
 */
class EncodePayload
{
    private string $secret;

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
    ): string {
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        try {
            $signingKey = InMemory::plainText(random_bytes(32));
        } catch (Exception) {
            throw new LogicException();
        }

        $tokenBuilder = $tokenBuilder
            ->identifiedBy($this->secret);

        foreach ($claims as $claim) {
            $tokenBuilder = $tokenBuilder->withClaim($claim->getKey(), $claim->getValue());
        }

        return $tokenBuilder
            ->getToken($algorithm, $signingKey)
            ->toString();
    }
}
