<?php

namespace Kanboard\Plugin\CRProject\Model;

use Kanboard\Core\Base;
use Kanboard\Plugin\CRProject\Helper\Arr;

class ProjectStatusModel extends Base
{
    const TABLE = 'crproject_status';

    /**
     * Get by id.
     *
     * @param integer $id
     * @return array
     */
    public function getById($id)
    {
        return $this->db->table(self::TABLE)->eq('id', $id)->findOne();
    }

    /**
     * Get all.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->db->table(self::TABLE)->asc('position')->asc('title')->findAll();
    }

    /**
     * Get all options.
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = array();
        $rows = $this->getAll();
        foreach ($rows as $row) {
            $options[$row['id']] = $row['title'];
        }
        return $options;
    }

    /**
     * Save.
     *
     * @param array $values
     * @return integer
     */
    public function save($values)
    {
        // Extract id.
        $id = Arr::getInt($values, 'id');
        Arr::remove($values, 'id');

        // Prepare values.
        if (!Arr::has($values, 'is_visible')) {
            $values['is_visible'] = 0;
        }

        // Make sure position is set.
        if (Arr::getInt($values, 'position') == 0) {
            $maxPosition = intval($this->db->table(self::TABLE)->desc('position')->findOneColumn('position'));
            $values['position'] = $maxPosition + 1;
        }

        if ($id > 0) {
            $this->db->table(self::TABLE)->eq('id', $id)->save($values);
        } else {
            $this->db->table(self::TABLE)->save($values);
        }
        if ($id == 0) {
            $id = $this->db->getLastId();
        }

        return $id;
    }

    /**
     * Remove.
     *
     * @param integer $id
     * @return boolean
     */
    public function remove($id)
    {
        return $this->db->table(self::TABLE)->eq('id', $id)->remove();
    }

    /**
     * Change position.
     *
     * @param integer $id
     * @param integer $position
     * @return boolean
     */
    public function changePosition($id, $position)
    {
        if ($position < 1 || $position > $this->db->table(self::TABLE)->count()) {
            return false;
        }
        $currentIds = $this->db->table(self::TABLE)->neq('id', $id)->asc('position')->findAllByColumn('id');
        $results = array();
        $offset = 1;
        foreach ($currentIds as $currentId) {
            if ($offset == $position) {
                $results[] = $this->db->table(self::TABLE)->eq('id', $id)->update(array('position' => $offset));
                $offset++;
            }
            $results[] = $this->db->table(self::TABLE)->eq('id', $currentId)->update(array('position' => $offset));
            $offset++;
        }
        return !in_array(false, $results, true);
    }
}
