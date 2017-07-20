<?php

namespace Lexing\SouCheBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class LexingChooseModelCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lexing:choose-model')
            ->setDescription('set sou entity assocModel ')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souArr = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souArr as $sou){
            if (!empty($sou->getSerieId())&&empty($sou->getModelName())){
                $serieId = $sou->getSerieId();
                $year = $sou->getYear();
                $query = $em->getRepository('LexingVehicleBundle:VehicleModel')->createQueryBuilder('m')
                    ->where('m.serie = :serie AND m.year = :year')
                    ->setParameter('serie', $serieId)
                    ->setParameter('year', $year)
                    ->getQuery();
                if ($query->getResult()){
                    $modelObjs = $query->getResult();
                    foreach ($modelObjs as $model){
                        $modelArr[] = $model->getName().",".$model->getId();
                    }
                    dump($sou->getName());
                    dump($sou->getId());
                    $helper = $this->getHelper('question');
                    $question = new ChoiceQuestion(
                        'Please select model name (defaults to first)',
                        $modelArr,
                        0
                    );
                    $question->setErrorMessage('This model name %s is invalid.');

                    $name = $helper->ask($input, $output, $question);
                    $spArr = mb_split(",",$name);
                    $assoId = $spArr[count($spArr)-1];
                    $sou->setModelName($spArr[0]);
                    $sou->setModelId($assoId);
                    $em->flush();
                    $output->writeln('You have just selected: '.$name);
                    $modelArr = [];
                }else{
                    $failArr[]=$sou->getId();
                }

            }
        }

    }

}
