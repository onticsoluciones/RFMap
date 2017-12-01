<?php

namespace Ontic\RFMap\Repository;

use Ontic\RFMap\Entity\DataPoint;

class DataPointRepository extends Repository
{
    /**
     * Insert a new datapoint into the database
     * @param DataPoint $dataPoint
     */
    public function add(DataPoint $dataPoint)
    {
        $sql = '
          INSERT INTO datapoints(plugin, entityid, name, frequency, bandwidth, rssi, extra)
          VALUES(:plugin, :entityid, :name, :frequency, :bandwidth, :rssi, :extra)';
        
        $command = $this->connection->prepare($sql);
        $command->execute([
            'plugin' => $dataPoint->plugin,
            'entityid' => $dataPoint->entityid,
            'name' => $dataPoint->name,
            'frequency' => $dataPoint->frequency,
            'bandwidth' => $dataPoint->bandwidth,
            'rssi' => $dataPoint->rssi,
            'extra' => $dataPoint->extra
        ]);
    }
}