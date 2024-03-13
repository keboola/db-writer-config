<?php

declare(strict_types=1);

namespace Keboola\DbWriterConfig\Configuration\NodeDefinition;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class TableNodesDecorator implements DecoratorInterface
{
    public function addNodes(NodeBuilder $nodeBuilder): void
    {
        $this->addTableIdNode($nodeBuilder);
        $this->addDbNameNode($nodeBuilder);
        $this->addIncrementalNode($nodeBuilder);
        $this->addExportNode($nodeBuilder);
        $this->addPrimaryKeyNode($nodeBuilder);
        $this->addItemsNode($nodeBuilder);
    }

    protected function addTableIdNode(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder->scalarNode('tableId')->isRequired()->cannotBeEmpty();
    }

    protected function addDbNameNode(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder->scalarNode('dbName')->isRequired()->cannotBeEmpty();
    }

    protected function addIncrementalNode(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder->booleanNode('incremental')->defaultFalse();
    }

    protected function addExportNode(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder->booleanNode('export')->defaultTrue();
    }

    protected function addPrimaryKeyNode(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder->arrayNode('primaryKey')->scalarPrototype()->cannotBeEmpty()->end();
    }

    protected function addItemsNode(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->arrayNode('items')
            ->validate()->always(function ($v) {
                $validItem = false;
                foreach ($v as $item) {
                    if ($item['type'] !== 'ignore') {
                        $validItem = true;
                        break;
                    }
                }
                if (!$validItem) {
                    throw new InvalidConfigurationException(
                        'At least one item must be defined and cannot be ignored.',
                    );
                }
                return $v;
            })->end()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('name')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('dbName')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('size')
                        ->beforeNormalization()->always(fn($v) => (string) $v)->end()
                    ->end()
                    ->scalarNode('nullable')->end()
                    ->scalarNode('default')->end()
                ->end()
            ->end();
    }
}
