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
     * @param int $totalCount
     * @param int $pageSize
     * @return array ['totalCount' => '剩余总数(含当前页)', 'currentPage' => '当前页(无意义)', 'pageCount' => '剩余页数', 'perPage' => '每次加载数']
     */
    public function calculatePaginate(int $totalCount, int $pageSize)
    {
        return [
            'totalCount' => $totalCount,
            'currentPage' => 0,
            'pageCount' => $totalCount > $pageSize ? floor($totalCount / $pageSize) : 0,
            'perPage' => $pageSize,
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

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  int $length
     * @param  bool $diff   区分大小写
     * @return string
     */
    public function generateChatCode(int $length, bool $diff = false)
    {
        $letters = 'bcdfghjklmnpqrstvwxyz';
        $letterUpper = 'BCDFGHJKLMNPQRSTVWXYZ';
        $vowels = 'aeiou';
        $vowelUpper = 'AEIOU';

        if ($diff) {
            $letters .= $letterUpper;
            $vowels .= $vowelUpper;
        }

        return $this->generateAlgorithm($letters, $vowels, $length);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  int $length
     * @return string
     */
    public function generateNumberCode(int $length)
    {
        $letters = '24680';
        $vowels = '13579';

        return $this->generateAlgorithm($letters, $vowels, $length);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  int $length
     * @param  bool $diff   区分大小写
     * @return string
     */
    public function generateStringCode(int $length, bool $diff = false)
    {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $letterUpper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $vowels = '1234567890';

        $diff && $letters .= $letterUpper;

        return $this->generateAlgorithm($letters, $vowels, $length);
    }

    /**
     *
     * @author zxf
     * @date   2020年4月30日
     * @param  int $length
     * @return string
     */
    protected function generateAlgorithm(string $letters, string $vowels, int $length)
    {
        $vowelsLength = strlen($vowels) - 1;
        $lettersLength = strlen($letters) - 1;
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            if ($i % 2 && mt_rand(0, 10) > 2 || !($i % 2) && mt_rand(0, 10) > 9) {
                $code .= $vowels[mt_rand(0, $vowelsLength)];
            } else {
                $code .= $letters[mt_rand(0, $lettersLength)];
            }
        }

        return $code;
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
     * @return \Seffeng\Basics\Base\Service
     */
    public function setFillable(array $fillable = [])
    {
        $this->fillable = $fillable;
        return $this;
    }
}
