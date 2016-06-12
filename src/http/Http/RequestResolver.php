<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface RequestResolver
{
    /**
     * @return string
     */
    public function resolveMethod(): string;

    /**
     * @return string
     */
    public function resolvePath(): string;

    /**
     * @return string|null
     */
    public function resolveQuery(): ?string;

    /**
     * @return string|null
     */
    public function resolveVersion(): ?string;

    /**
     * @return array
     */
    public function resolveHeaders(): array;

    /**
     * @return mixed
     */
    public function resolveBody(): mixed;

    /**
     * @return mixed
     */
    public function resolveParsedBody(): mixed;

    /**
     * @return string|null
     */
    public function resolveIp(): ?string;
}
