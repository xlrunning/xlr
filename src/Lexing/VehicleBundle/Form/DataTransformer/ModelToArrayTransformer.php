<?php

namespace Lexing\VehicleBundle\Form\DataTransformer;

use Lexing\VehicleBundle\Entity\VehicleModel;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Lexing\VehicleBundle\Entity\Vehicle;

/**
 * Class ModelToArrayTransformer
 * @package Lexing\VehicleBundle\Form\DataTransformer
 */
class ModelToArrayTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (vehicle model) to a string (number).
     *
     * @param  VehicleModel|null $model
     * @return Array
     */
    public function transform($model)
    {
        if (null === $model) {
            return [];
        }

        $serie = $model->getSerie();
        $brand = $serie->getBrand();
        return [
            'brand' => $brand->getId(),
            'serie' => $serie->getId(),
            'model' => $model->getId()
        ];
    }

    /**
     * Transforms a array to an object (model).
     *
     * @param  array $arr
     *
     * @return VehicleModel|null
     *
     * @throws TransformationFailedException if object (model) is not found.
     */
    public function reverseTransform($arr)
    {
        if (!$arr) {
            return null;
        }

        $model = $this->om
            ->getRepository('LexingVehicleBundle:VehicleModel')
            ->find($arr['model'])
        ;

        if (null === $model) {
            throw new TransformationFailedException(sprintf(
                'A model with id "%s" does not exist!',
                $arr['model']
            ));
        }

        return $model;
    }
}