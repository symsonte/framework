<?php

namespace Symsonte\Authentication;

use Symsonte\Security;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class CredentialProcessor
{
    /**
     * @var Security\DecodePayload
     */
    private $decodePayload;

    /**
     * @param Security\DecodePayload $decodePayload
     */
    public function __construct(Security\DecodePayload $decodePayload)
    {
        $this->decodePayload = $decodePayload;
    }

    /**
     * @param Credential $credential
     * @param string     $claim
     *
     * @throws UnprocessableCredentialException
     *
     * @return mixed
     */
    public function process(Credential $credential, string $claim)
    {
        try {
            return $this->decodePayload->decode($credential->getToken(), $claim);
        } catch (Security\InvalidPayloadException $e) {
            throw new UnprocessableCredentialException();
        }
    }
}
