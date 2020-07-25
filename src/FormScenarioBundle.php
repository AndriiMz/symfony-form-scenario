<?php

namespace AndriiMz\FormScenario;

use AndriiMz\FormScenario\DependencyInjection\FormScenarioExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FormScenarioBundle extends Bundle
{
    /**
     * @return ExtensionInterface
     */
    public function getContainerExtension(): ExtensionInterface
    {
        return new FormScenarioExtension();
    }
}
