<?php

namespace Kanboard\Plugin\UpdateNotifier\Model;

use Kanboard\Core\Base;

class PluginTimestampedModel extends Base
{

    const TABLE = 'plugin_dates';

    public function getAll()
    {
        return $this->db
            ->table(self::TABLE)
            ->desc('date_creation')
            ->findAll();
    }
    
    public function getTen()
    {
        return $this->db
            ->table(self::TABLE)
            ->desc('date_creation')
            ->neq('date_creation', 0)
            ->limit(10)
            ->findAllByColumn('name');
    }

    public function exists($name)
    {
        return $this->db
            ->table(self::TABLE)
            ->eq('name', $name)
            ->exists();
    }

    public function save($name)
    {
        $results = array();
        $timestamp = time();

        $this->db->startTransaction();
        
        if (!$this->exists($name)) {
                $results[] = $this->db->table(self::TABLE)->insert(array(
                    'name' => $name,
                    'date_creation' => $timestamp,
                ));
         }   

        $this->db->closeTransaction();
        return ! in_array(false, $results, true);
    }
    
    public function saveWithoutTimestamp($name)
    {        
        return $this->db
                    ->table(self::TABLE)
                    ->save(array(
                        'name' => $name,
                        'date_creation' => 0,
                    ));
    }
    
}
