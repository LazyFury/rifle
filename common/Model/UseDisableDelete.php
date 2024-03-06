<?php

namespace Common\Model;

trait UseDisableDelete
{
    /**
     * Determine if the model is deletable.
     * @return bool
     */
    public function get_deleteable()
    {
        return true;
    }
}
