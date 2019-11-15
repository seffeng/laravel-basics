<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Constants;

use Illuminate\Support\Arr;

/**
 * 错误常量
 * @author zxf
 */
class ErrorConst
{
    /**
     * 无错误，正常返回
     * @var integer
     */
    const NOT = 0;

    /**
     * 默认错误
     * @var integer
     */
    const DEFAULT = 1;

    /**
     * 未登录
     * @var integer
     */
    const UNAUTHORIZED = 401;

    /**
     * 无权限
     * @var integer
     */
    const PERMISSION_DENIED = 403;

    /**
     * 资源不存在
     * @var integer
     */
    const NOT_FOUND = 404;

    /**
     * 请求方式不支持
     * @var integer
     */
    const METHOD_NOT_SUPPORTED = 405;

    /**
     * CSRF-TOKEN不匹配
     * @var integer
     */
    const CSRF_MISMATCH = 419;

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @param int $code
     * @return string
     */
    public static function getError(int $code = self::DEFAULT)
    {
        return Arr::get(self::fetchNameItems(), $code, '未定义错误代码：( '. $code .' )！');
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @return string[]
     */
    public static function fetchNameItems()
    {
        return [
            self::NOT => '操作成功！',
            self::DEFAULT => '服务器异常错误！',
            self::UNAUTHORIZED => '用户未登录！',
            self::PERMISSION_DENIED => '权限错误！',
            self::NOT_FOUND => '资源不存在！',
            self::METHOD_NOT_SUPPORTED => '请求方式不支持！',
            self::CSRF_MISMATCH => 'CSRF-TOKEN不匹配！',
        ];
    }

    /**
     *
     * @author zxf
     * @date   2019年10月19日
     * @return array
     */
    public static function fetchItems()
    {
        return array_keys(self::fetchNameItems());
    }

    /**
     *
     * @author zxf
     * @date    2019年10月28日
     * @param array $data
     * @param string $message
     * @return array
     */
    public static function responseSuccess($data = [], string $message = 'success')
    {
        return [
            'status' => 'success',
            'data' => (is_array($data) && count($data) === 0) ? new \stdClass() : $data,
            'message' => $message,
        ];
    }

    /**
     *
     * @author zxf
     * @date    2019年10月28日
     * @param  string $message
     * @param  array $data
     * @param  int $code
     * @return array
     */
    public static function responseError(string $message, $data = [], int $code = null)
    {
        return [
            'status' => 'error',
            'code' => is_null($code) ? self::DEFAULT : $code,
            'data' => (is_array($data) && count($data) === 0) ? new \stdClass() : $data,
            'message' => $message,
        ];
    }
}
