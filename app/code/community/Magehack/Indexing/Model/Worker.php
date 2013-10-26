<?php
/**
 * The worker model
 *
 * @category Magehack
 * @package  Magehack_Indexing
 * @author   Alex Bejan <contact@bejanalex.com>
 * @link     http://bejanalex.com
 */
class Magehack_Indexing_Model_Worker extends Lilmuckers_Queue_Model_Worker_Abstract
{
    /**
     * Reindex
     *
     * @param Lilmuckers_Queue_Model_Queue_Task $task - the task
     *
     * @return void
     */
    public function reindex(Lilmuckers_Queue_Model_Queue_Task $task)
    {
        if ($task->getData('indexprocess')) {
            // Get the process data
            $processData = $task->getData('indexprocess');

            /**
             * @var Magehack_Indexing_Model_Index_Process $process
             */
            $process = Mage::getModel('index/process');
            $process->setData($processData);
            $process->reindexAll();

            // Mark task as successful
            $task->success();
        }
        // Mark task as errored to drop it from the queue for later examination
        $task->hold();
    }
}
