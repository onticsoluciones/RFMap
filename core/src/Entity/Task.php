<?php

namespace Ontic\RFMap\Entity;

class Task
{
    /** @var integer */
    public $id;
    /** @var string */
    public $plugin;
    /** @var \DateTime */
    public $runAt;
    /** @var int */
    public $rescheduleAfter;
    /** @var string */
    public $entityId;

    /**
     * @return Task
     */
    public function getRescheduledTask()
    {
        $newTask = new Task();
        $newTask->plugin = $this->plugin;
        $newTask->rescheduleAfter = $this->rescheduleAfter;
        $newTask->entityId = $this->entityId;
        
        $interval = sprintf('PT%sS', $this->rescheduleAfter);
        $now = (new \DateTime())->setTimezone(new \DateTimeZone('UTC'));
        $newTask->runAt = $now->add(new \DateInterval($interval));
        return $newTask;
    }
}