<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class Service
{
    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  LengthAwarePaginator $paginator
     * @return number[]
     */
    public function getPaginate(LengthAwarePaginator $paginator)
    {
        return [
            'totalCount' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'pageCount' => $paginator->lastPage(),
            'perPage' => $paginator->perPage(),
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @param string $name
     */
    public function openQueryLog(string $name = null)
    {
        Model::openQueryLog($name);
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @return mixed
     */
    public function getQueryLog()
    {
        return Model::getQueryLog();
    }
}
