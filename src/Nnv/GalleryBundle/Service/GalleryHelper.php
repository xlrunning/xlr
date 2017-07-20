<?php

namespace Nnv\GalleryBundle\Service;

use Nnv\GalleryBundle\Entity\GalleryItem;

class GalleryHelper
{
    private $types;
    
    private $typeChoices;
    
    private $typeEntities;
    
    public function __construct($types)
    {
        $this->types = $types;
        // ['key', 'label', 'entity']
        $this->typeChoices  = [GalleryItem::META_TYPE_NOTE => '说明'];
        $this->typeEntities = [];
        foreach ($this->types as $def) {
            $this->typeChoices[$def['key']] = $def['label'];
            if (isset($def['entity'])) {
                $this->typeEntities[$def['key']] = $def['entity'];
            }
        }
    }
    
    public function getTypeChoices()
    {
        return $this->typeChoices;
    }
    
    public function checkType($type)
    {
        return isset($this->typeChoices[$type]);
    }
    
    public function getTypeLabel($type)
    {
        return isset($this->typeChoices[$type]) ? $this->typeChoices[$type] : '';
    }
    
    public function getTypeEntity($type)
    {
        return isset($this->typeEntities[$type]) ? $this->typeEntities[$type] : null;
    }
}