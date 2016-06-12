<?php

namespace Symsonte\Security;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;

/**
 * @di\service()
 */
class DecodePayload
{
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
    ): mixed {
        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($token);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            throw new InvalidPayloadException(null, null, $e);
        }

        return $token->claims()->get($key);
    }
}
