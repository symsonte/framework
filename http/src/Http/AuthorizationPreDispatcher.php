<?php

namespace Symsonte\Http;

use Symsonte\Authorization\RoleCollector;
use Symsonte\AuthorizationChecker;
use Symsonte\Http\Authentication\CredentialProcessor;
use Exception;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.pre_dispatcher']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.pre_dispatcher']
 * })
 */
class AuthorizationPreDispatcher implements PreDispatcher
{
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var CredentialProcessor
     */
    private $credentialProcessor;

    /**
     * @var RoleCollector
     */
    private $roleCollector;

    /**
     * @param AuthorizationChecker $authorizationChecker
     * @param CredentialProcessor  $credentialProcessor
     * @param RoleCollector        $roleCollector
     */
    public function __construct(
        AuthorizationChecker $authorizationChecker,
        CredentialProcessor $credentialProcessor,
        RoleCollector $roleCollector
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->credentialProcessor = $credentialProcessor;
        $this->roleCollector = $roleCollector;
    }

    /**
     * @param string $controller
     *
     * @return OrdinaryResponse|null
     */
    public function dispatch(
        string $controller
    ) {
        // Does not the controller require authorization?
        if ($this->authorizationChecker->has($controller) === false) {
            return null;
        }

        // Process the credential
        try {
            $user = $this->credentialProcessor->process();
        } catch (Exception $e) {
            return new OrdinaryResponse([
                'code' => 'invalid-credential'
            ]);
        }

        // Get the user roles
        $roles = $this->roleCollector->collect($user);

        if (
            // Doesn't the user have the correct role for the current controller?
            $this->authorizationChecker->check($controller, $roles) === false
        ) {
            return new OrdinaryResponse(null, 401);
        }

        return null;
    }
}