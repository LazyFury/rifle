<?php

namespace Common\Model;

trait UseSearchable
{
    /**
     * service 已经实现了基础的 column，这里仅定义外键
     */
    public function get_searchable()
    {
        return [];
    }

    /**
     *  定义不需要搜索的字段
     */
    public function get_searchable_exclude()
    {
        return [];
    }

}