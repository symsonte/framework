<?php

namespace Symsonte;

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
class AuthorizationChecker
{
    /**
     * @var Authorization[]
     */
    private $authorizations;

    /**
     * @param Authorization[] $authorizations
     */
    public function __construct($authorizations = [])
    {
        $this->authorizations = $authorizations;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->authorizations[$key]);
    }

    /**
     * @param string   $key
     * @param string[] $roles
     *
     * @return bool|string
     */
    public function check($key, $roles)
    {
        $allowed = $this->authorizations[$key]->getRoles();

        if (empty($allowed)) {
            return true;
        }

        foreach ($roles as $role) {
            if (in_array($role, $allowed)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Authorization[]
     */
    public function merge(array $authorizations)
    {
        $this->authorizations = array_merge(
            $this->authorizations,
            $authorizations
        );
    }
}
