<?php

namespace Lexing\VehicleBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

class ChoicePopulator // implements ChoicePopulatorInterface
{
    use ContainerAwareTrait;

    public function populate($from, $to, $fromVal)
    {
        if ($from == 'brand' && $to == 'serie') {
            return $this->populateBrandSeries($fromVal);
        } else if ($from == 'serie' && $to == 'model') {
            return $this->populateSerieModels($fromVal);
        }
        // not supported exception
        return [];
    }

    public function populateBrandSeries($brandId)
    {
        $series = $this->container->get('doctrine.orm.entity_manager')
            ->createQuery('SELECT s FROM LexingVehicleBundle:VehicleSerie s WHERE s.brand = :brand')
            ->setParameter('brand', $brandId)
            ->getResult();
        $data = [];
        foreach ($series as $serie) {
            $data[$serie->getMakeName()][] = new ChoiceView(null, $serie->getId(), $serie->getName());
        }

        return $data;
    }

    public function populateSerieModels($serieId)
    {
        $models = $this->container->get('doctrine.orm.entity_manager')
            ->createQuery('SELECT m FROM LexingVehicleBundle:VehicleModel m WHERE m.serie = :serie')
            ->setParameter('serie', $serieId)
            ->getResult();
        $data = [];
        foreach ($models as $model) {
            $name = substr($model->getName(), 8);
            $data[$model->getYear().'款'][] = new ChoiceView(null, $model->getId(), $name);;
        }

        return $data;
    }
}