<?php

namespace spec\Roquie\Pipeline;

use Roquie\Pipeline\CallableStage;
use Roquie\Pipeline\PipelineBuilder;
use Roquie\Pipeline\PipelineInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PipelineBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(PipelineBuilder::class);
    }

    function it_should_build_a_pipeline()
    {
        $this->build()->shouldHaveType(PipelineInterface::class);
    }

    function it_should_collect_operations_for_a_pipeline()
    {
        $this->add(CallableStage::forCallable(function ($p) {
            return $p * 2;
        }));

        $this->build()->process(4)->shouldBe(8);
    }

    function it_should_have_a_fluent_build_interface()
    {
        $operation = CallableStage::forCallable(function () {});
        $this->add($operation)->shouldBe($this);
    }
}
