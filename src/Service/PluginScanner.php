<?php

namespace Ontic\RFMap\Service;

class PluginScanner
{
    /**
     * Find all valid plugins in the specified path
     * @param string $dir
     * @return string[]
     */
    public function scan($dir)
    {
        $fsIterator = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS);
        
        $pluginNames = [];
        
        foreach($fsIterator as $entry)
        {
            if(!is_dir($entry))
            {
                // Non-directory entry, skip
                continue;
            }
            
            $executablePath = sprintf('%s/execute', $entry);
            if(!file_exists($executablePath) || !is_executable($executablePath))
            {
                // Plugin directory doesn't contain the required executable, skip
                continue;
            }
            
            // Everything is ok, add its name to plugin list
            $pluginNames[] = basename($entry);
        }
        
        return $pluginNames;
    }
}