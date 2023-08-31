<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2023 seffeng
 */
namespace Seffeng\Basics\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ComplexPrimaryKeyTrait
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     *
     * @author zxf
     * @date   2023-08-31
     * @param  Builder $query
     * @return Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        if (is_array($this->getKeyName())) {
            foreach ($this->getKeyName() as $key) {
                $query->where($key, '=', $this->$key);
            }
        } else {
            $query->where($this->getKeyName(), '=', $this->getKeyForSaveQuery());
        }

        return $query;
    }
}
