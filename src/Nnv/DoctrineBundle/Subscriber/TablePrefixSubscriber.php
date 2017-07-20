<?php

namespace Nnv\DoctrineBundle\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class TablePrefixSubscriber.
 *
 * Doctrine event to prefix tables
 */
class TablePrefixSubscriber implements EventSubscriber
{
    /**
     * Prefixes.
     * namespace=>prefix
     *
     * @var array
     */
    protected $prefixes = [];

    /**
     * Constructor.
     *
     * @param string $prefix
     */
    public function __construct($prefixes)
    {
        $this->prefixes = $prefixes;
    }

    /**
     * Returns subscribed events.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['loadClassMetadata'];
    }

    /**
     * Loads class metadata and updates table prefix name.
     *
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();
        
        if ($classMetadata->isInheritanceTypeSingleTable() && !$classMetadata->isRootEntity()) {
            return;
        }

        if ($classMetadata->getReflectionClass()) {
            $namespaceName = $classMetadata->getReflectionClass()->getNamespaceName();
            foreach ($this->prefixes as $namespace=>$prefix) {
                if (strpos($namespaceName, $namespace) !== 0) {
                    continue;
                }
                
                $classMetadata->setPrimaryTable(['name' => $prefix.$classMetadata->getTableName()]);
                foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
                    if ($mapping['type'] == ClassMetadataInfo::MANY_TO_MANY) {
                        $mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
                        $classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $prefix.$mappedTableName;
                    }
                }
            }
        }
    }
}
