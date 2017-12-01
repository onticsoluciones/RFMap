<?php

namespace Ontic\RFMap\Repository;

class PluginRepository extends Repository
{
    /**
     * Update the plugins table with a new set of plugins
     * @param string[] $pluginNames
     */
    public function updatePlugins($pluginNames)
    {
        // Add new plugins
        foreach($pluginNames as $name)
        {
            if(!$this->exists($name))
            {
                $this->insert($name);
            }
        }
        
        // Delete removed plugins
        foreach($this->findAllNames() as $name)
        {
            if(!in_array($name, $pluginNames))
            {
                $this->delete($name);
            }
        }
    }
    
    /**
     * Insert a plugin into the database
     * @param string $name
     */
    private function insert($name)
    {
        $sql = 'INSERT INTO plugins(name, enabled) VALUES(:name, 1);';
        $command = $this->connection->prepare($sql);
        $command->execute([ 'name' => $name ]);
    }

    /**
     * Remove a plugin from the database
     * @param $name
     */
    private function delete($name)
    {
        $sql = 'DELETE FROM plugins WHERE name = :name;';
        $command = $this->connection->prepare($sql);
        $command->execute([ 'name' => $name ]);
    }

    /**
     * Check whether there is a plugin with the specified name
     * in the plugins table
     * @param string $name
     * @return bool
     */
    private function exists($name)
    {
        $sql = 'SELECT COUNT(1) FROM plugins WHERE name = :name;';
        $command = $this->connection->prepare($sql);
        $command->execute([ 'name' => $name ]);
        return $command->fetchColumn() == 1;
    }

    /**
     * @return string[]
     */
    private function findAllNames()
    {
        $sql = 'SELECT name FROM plugins;';
        $command = $this->connection->prepare($sql);
        
        return array_map(function($row)
        {
            return $row['name'];
        }, $command->fetchAll());
    }
}