<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Seffeng\Basics\Constants\ErrorConst;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  array $data
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data = [], string $message = 'success')
    {
        return response()->json(ErrorConst::responseSuccess($data, $message));
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  string $message
     * @param  array $data
     * @param  int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError(string $message, $data = [], int $code = null)
    {
        return response()->json(ErrorConst::responseError($message, $data, $code));
    }

    /**
     * 下载
     * @author zxf
     * @date    2019年11月06日
     * @param  mixed $data
     * @param  string $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function responseDownload($data, string $fileName)
    {
        return response()->streamDownload(function() use ($data) { echo $data; }, $fileName);
    }

    /**
     *
     * @author zxf
     * @date    2019年11月07日
     * @param  \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseException($e)
    {
        return response()->json(ErrorConst::responseError(ErrorConst::getError(),
            config('app.debug') ? [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : [])
        );
    }
}
