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
     * @var ErrorConst
     */
    protected $errorClass = ErrorConst::class;

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  array $data
     * @param  string $message
     * @param  array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data = [], string $message = 'success', array $headers = [])
    {
        return response()->json($this->errorClass::responseSuccess($data, $message), 200, $headers);
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  string $message
     * @param  array $data
     * @param  int $code
     * @param  array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError(string $message, $data = [], int $code = null, array $headers = [])
    {
        return response()->json($this->errorClass::responseError($message, $data, $code), 200, $headers);
    }

    /**
     * 下载
     * @author zxf
     * @date    2019年11月06日
     * @param  mixed $data
     * @param  string $fileName
     * @param  array $headers
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function responseDownload($data, string $fileName, array $headers)
    {
        return response()->streamDownload(function() use ($data) { echo $data; }, $fileName, $headers);
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
        return response()->json($this->errorClass::responseError($this->errorClass::getError(),
            config('app.debug') ? [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : [])
        );
    }
}
