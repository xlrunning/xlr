<?php
namespace Lexing\VehicleBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ArrayToStringTransformer implements DataTransformerInterface
{
    public function transform($string)
    {
        return ['color' => $string];
    }

    public function reverseTransform($array)
    {
        return $array['color'];
    }
}