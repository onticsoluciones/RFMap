#!/usr/bin/env php
<?php
echo json_encode([
        'entity_id' => '1',
        'name' => 'Entity 1',
        'frequency' => 99.99,
        'bandwidth' => 15.5,
        'rssi' => 3.45,
        'extra' => [
            'key1' => 'value1',
            'key2' => 'value2'
        ]
    ]
);
