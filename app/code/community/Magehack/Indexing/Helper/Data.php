<?php
/**
 * Helper
 *
 * @category Magehack
 * @package  Magehack_Indexing
 * @author   Alex Bejan <contact@bejanalex.com>
 * @link     http://bejanalex.com
 */
class Magehack_Indexing_Helper_Data extends Mage_Core_Helper_Abstract
{
    const QUEUE_NAME        = 'magehackindexing';
    const QUEUE_WORKER_NAME = 'reindex';

    /**
     * Send data to the queue worker
     *
     * @param array $data
     *
     * @return void
     */
    public function addToQueue($data)
    {
        // Get the Queue
        $_queue = Mage::helper('lilqueue')->getQueue(self::QUEUE_NAME);

        // Create a task in the queue for this job
        $_task = Mage::helper('lilqueue')->createTask(
            self::QUEUE_WORKER_NAME,
            $data
        );

        if (isset($data['processname'])) {
            Mage::getModel('adminhtml/session')
                ->addWarning('Indexing process <em>'.$data['processname'].'</em> added to queue');
        }
        // Add the task to the Queue
        $_queue->addTask($_task);
    }
}
