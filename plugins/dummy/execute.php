#!/usr/bin/env php
<?php
echo json_encode([
        'entity_id' => '1',
        'name' => 'Entity 1',
        'frequency' => rand(0, 9999) / 100,
        'bandwidth' => rand(0, 9999) / 100,
        'rssi' => rand(0, 9999) / 100,
        'extra' => [
            'key1' => 'value1',
            'key2' => 'value2'
        ]
    ]
);
