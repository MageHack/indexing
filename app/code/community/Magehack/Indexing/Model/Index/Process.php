<?php
/**
 * Rewrite of the index/process model
 *
 * @category Magehack
 * @package  Magehack_Indexing
 * @author   Alex Bejan <contact@bejanalex.com>
 * @link     http://bejanalex.com
 */
class Magehack_Indexing_Model_Index_Process extends Mage_Index_Model_Process{

    const CONFIG_PATH_USE_QUEUE = 'catalog/magehackindexing/use_queue';

    /**
     * Reindex all data what this process responsible is
     * Check and using depends processes
     *
     * @return Mage_Index_Model_Process
     */
    public function reindexEverything()
    {
        if ($this->getData('runed_reindexall')) {
            return $this;
        }

        /** @var $eventResource Mage_Index_Model_Resource_Event */
        $eventResource = Mage::getResourceSingleton('index/event');
        $unprocessedEvents = $eventResource->getUnprocessedEvents($this);
        $this->setForcePartialReindex(count($unprocessedEvents) > 0 && $this->getStatus() == self::STATUS_PENDING);

        if ($this->getDepends()) {
            /** @var $indexer Mage_Index_Model_Indexer */
            $indexer = Mage::getSingleton('index/indexer');
            foreach ($this->getDepends() as $code) {
                $process = $indexer->getProcessByCode($code);
                if ($process) {
                    $process->reindexEverything();
                }
            }
        }

        $this->setData('runed_reindexall', true);

        // Try to send the reindexing process to the queue
        if (Mage::getStoreConfig(self::CONFIG_PATH_USE_QUEUE)) {
            Mage::helper('magehackindexing')->addToQueue(array(
                                                      'indexprocess'=>$this->getData(),
                                                      'processname'=>$this->getIndexer()->getName()
                                                 ));
            return $this;
        }
        return $this->reindexAll();
    }
}