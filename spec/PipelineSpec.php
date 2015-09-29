<?php

namespace spec\Roquie\Pipeline;

use InvalidArgumentException;
use Roquie\Pipeline\CallableStage;
use Roquie\Pipeline\Pipeline;
use Roquie\Pipeline\PipelineInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PipelineSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Pipeline::class);
        $this->shouldHaveType(PipelineInterface::class);
    }

    function it_should_pipe_operation()
    {
        $operation = CallableStage::forCallable(function () {});
        $this->pipe($operation)->shouldHaveType(PipelineInterface::class);
        $this->pipe($operation)->shouldNotBe($this);
    }

    function it_should_compose_pipelines()
    {
        $pipeline = new Pipeline();
        $this->pipe($pipeline)->shouldHaveType(PipelineInterface::class);
        $this->pipe($pipeline)->shouldNotBe($this);
    }

    function it_should_process_a_payload()
    {
        $operation = CallableStage::forCallable(function ($payload) { return $payload + 1; });
        $this->pipe($operation)->process(1)->shouldBe(2);
    }

    function it_should_execute_operations_sequential()
    {
        $this->beConstructedWith([
            CallableStage::forCallable(function ($p) { return $p + 2; }),
            CallableStage::forCallable(function ($p) { return $p * 10; }),
        ]);

        $this->process(1)->shouldBe(30);
    }

    function it_should_only_allow_operations_as_constructor_arguments()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [['fooBar']]);
    }
}
