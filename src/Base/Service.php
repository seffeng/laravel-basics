<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Illuminate\Pagination\LengthAwarePaginator;
use Seffeng\LaravelHelpers\Helpers\Arr;
use Seffeng\LaravelHelpers\Helpers\Str;

class Service
{
    /**
     *
     * @var array
     */
    protected $fillable = [];

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  LengthAwarePaginator $paginator
     * @return array
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
     * @date   2020年4月30日
     * @param integer $totalCount
     * @param integer $pageSize
     * @return array ['totalCount' => '剩余总数(含当前页)', 'currentPage' => '当前页(无意义)', 'pageCount' => '剩余页数', 'perPage' => '每次加载数']
     */
    public function calculatePaginate(int $totalCount, int $pageSize)
    {
        return [
            'totalCount' => $totalCount,
            'currentPage' => 1,
            'pageCount' => $totalCount > $pageSize ? (ceil($totalCount / $pageSize) - 1) : 0,
            'perPage' => $pageSize,
        ];
    }

    /**
     * 获取页码，返回分页页码列表
     *
     * @author zxf
     * @date   2024-05-31
     * @param integer $totalCount 总数量
     * @param integer $page       当前页
     * @param integer $perPage    每页数量
     * @param integer $limit      展示的页面数量
     * @return array
     */
    public function getPaginateOptions(int $totalCount, int $page = 1, int $perPage = 10, int $limit = 10)
    {
        $pageCount = ceil($totalCount / $perPage);
        $page > $pageCount && $page = $pageCount;
        $page < 1 && $page = 1;

        $ceil = ceil($limit / 2);
        $startPage = 1;
        if ($page - $ceil > 0) {
            if ($pageCount - $page > $ceil) {
                $startPage = $page - $ceil;
            } else {
                $startPage = $pageCount - $limit + 1;
                $startPage < 1 && $startPage = 1;
            }
        }

        $options = [];
        $endPage = $startPage + $limit - 1;
        $endPage > $pageCount && $endPage = $pageCount;
        for ($i = $startPage; $i <= $endPage; $i++) {
            $options[] = $i;
        }

        return $options;
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

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  integer $length
     * @param  bool $diff   区分大小写
     * @return string
     */
    public function generateChatCode(int $length, bool $diff = false)
    {
        return Str::generateChatCode($length, $diff);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  integer $length
     * @return string
     */
    public function generateNumberCode(int $length)
    {
        return Str::generateNumberCode($length);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  integer $length
     * @param  bool $diff   区分大小写
     * @return string
     */
    public function generateStringCode(int $length, bool $diff = false)
    {
        return Str::generateStringCode($length, $diff);
    }

    /**
     *
     * @author zxf
     * @date    2020年6月8日
     * @return array
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月8日
     * @param  array $fillable
     * @return static
     */
    public function setFillable(array $fillable = [])
    {
        $this->fillable = $fillable;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月8日
     * @param  array $item
     * @param  bool $isCamel
     * @return array
     */
    public function filterByFillable(array $item, bool $isCamel = false)
    {
        if ($this->getFillable()) foreach ($item as $key => $val) {
            if (!in_array($key, $this->getFillable())) {
                unset($item[$key], $val);
            }
        }
        if ($isCamel) {
            return $this->key2camel($item);
        }
        return $item;
    }

    /**
     *
     * @author zxf
     * @date   2021年12月1日
     * @param array $items
     * @return array
     */
    public function key2camel(array $items)
    {
        $data = [];
        foreach ($items as $key => $val) {
            $k = lcfirst(Str::studly($key));
            if (is_array($val)) {
                $data[$k] = $this->key2camel($val);
            } else {
                $data[$k] = $val;
            }
        }
        return $data;
    }

    /**
     * 流下载
     *
     * @author zxf
     * @date   2023-08-08
     * @param string $fileName
     * @return void
     */
    protected function streamDownload(string $fileName)
    {
        $fileName = urlencode($fileName);
        $pathinfo = pathinfo($fileName);
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        if (strtolower(Arr::get($pathinfo, 'extension', '')) === 'xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } else {
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        }
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Access-Control-Expose-Headers: Download-Filename');
        header('Download-Filename: ' . $fileName);
    }
}
