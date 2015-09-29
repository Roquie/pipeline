<?php

namespace Roquie\Pipeline;

interface StageInterface
{
    /**
     * Process the payload.
     *
     * @param mixed $payload
     *
     * @return mixed
     */
    public function process($payload);
}
