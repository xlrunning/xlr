<?php

namespace Nnv\FormBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ChoicePopulator
{
    protected $populators = [];

    public function addPopulator($from, $to, $service)
    {
        $this->populators[$from][$to] = $service;

        return $this;
    }

    public function populate($from, $to, $fromVal)
    {
        if (!isset($this->populators[$from], $this->populators[$from][$to])) {
            throw new ServiceNotFoundException('Cannot find service tagged as nnv.form.choice_populator with from:' . $from . ', to:' . $to);
        }
        $populator = $this->populators[$from][$to];
        return $populator->populate($from, $to, $fromVal);
    }
}