<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DatatableViewPass
 *
 * @package Sg\DatatablesBundle\DependencyInjection\Compiler
 */
class DatatableViewPass implements CompilerPassInterface
{
    /**
     * Process.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $datatablesContainer = $container->findDefinition('sg_datatables.view.container');
        $taggedServices = $container->findTaggedServiceIds('sg.datatable.view');

        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);
            $def->addArgument(new Reference('security.context'));
            $def->addArgument(new Reference('twig'));
            $def->addArgument(new Reference('translator.default'));
            $def->addArgument(new Reference('router'));
            $def->addArgument(new Reference('doctrine.orm.entity_manager'));
            $def->addArgument('%sg_datatables.datatable.templates%');

            $datatablesContainer->addMethodCall('addDatatable', array(new Reference($id)));
        }
    }
}
