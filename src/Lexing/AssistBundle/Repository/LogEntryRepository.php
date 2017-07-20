<?php

namespace Lexing\AssistBundle\Repository;

/**
 * LogEntryRepository
 *
 */
class LogEntryRepository extends \Gedmo\Loggable\Entity\Repository\LogEntryRepository
{
    /**
     *
     * @todo 修改了什么字段，可以进行特殊解释
     *
     * @param int $limit
     * @return array
     */
    public function getRecentLogEntries($limit = 40)
    {
        $dql = 'SELECT l FROM LexingAssistBundle:LogEntry l INDEX BY l.id ORDER BY l.loggedAt DESC';
        $logEntries = $this->_em->createQuery($dql)->setMaxResults(40)->getResult();

        $objectIds = [];
        foreach ($logEntries as $entry) {
            $objectIds[$entry->getObjectClass()][] = $entry->getObjectId();
        }
        foreach ($objectIds as $cls=>$ids) {
            $ids = array_unique($ids);
            $objects[$cls] = $this->_em->createQuery("SELECT o FROM $cls o INDEX BY o.id WHERE o.id IN (:ids)")
                ->setParameter('ids',$ids)
                ->getResult();
        }
        foreach ($logEntries as $entry) {
            $entryObjClass = $entry->getObjectClass();
            if (isset($objects[$entryObjClass], $objects[$entryObjClass][$entry->getObjectId()])) {
                $entry->setObject($objects[$entryObjClass][$entry->getObjectId()]);
            }
        }
        return $logEntries;
    }
}
