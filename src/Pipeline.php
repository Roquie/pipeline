<?php

namespace Roquie\Pipeline;

use InvalidArgumentException;

class Pipeline implements PipelineInterface
{
    /**
     * @var StageInterface[]
     */
    private $stages = [];

    /**
     * Constructor.
     *
     * @param StageInterface[] $stages
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $stages = [])
    {
        foreach ($stages as $stage) {
            if ( ! $stage instanceof StageInterface ||  ! is_callable([$stage, 'process'])) {
                throw new InvalidArgumentException('All stages should implement the ' . StageInterface::class
                    . ' and exists callable process($payload, ...$args) method');
            }
        }

        $this->stages = $stages;
    }

    /**
     * {@inheritdoc}
     */
    public function pipe(StageInterface $stage)
    {
        $stages = $this->stages;
        $stages[] = $stage;

        return new static($stages);
    }

    /**
     * {@inheritdoc}
     */
    public function process($payload, ...$params)
    {
        $reducer   = function ($payload, StageInterface $stage) use ($params) {
            return $stage->process($payload, ...$params);
        };

        return array_reduce($this->stages, $reducer, $payload);
    }
}
