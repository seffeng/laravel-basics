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
use Illuminate\Support\Facades\Request;
use Seffeng\LaravelHelpers\Helpers\Xml;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 默认接口返回格式[json|xml]
     * @var string
     */
    protected $format = 'json';

    /**
     * 接口返回支持格式
     * @var array
     */
    protected $allowFormat = ['xml', 'json'];

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
        $data = $this->errorClass::responseSuccess($data, $message);
        if ($this->getIsResponseXml()) {
            return response(Xml::toXml($data), 200, $headers)->withHeaders(['Content-Type' => 'text/xml']);
        } else {
            return response()->json($data, 200, $headers);
        }
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
        $data = $this->errorClass::responseError($message, $data, $code);
        if ($this->getIsResponseXml()) {
            return response(Xml::toXml($data), 200, $headers)->withHeaders(['Content-Type' => 'text/xml']);
        } else {
            return response()->json($data, 200, $headers);
        }
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
        $data = $this->errorClass::responseError($this->errorClass::getError(),
            config('app.debug') ? [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : []);
        if ($this->getIsResponseXml()) {
            return response(Xml::toXml($data), 200, [])->withHeaders(['Content-Type' => 'text/xml']);
        } else {
            return response()->json($data);
        }
    }

    /**
     *
     * @author zxf
     * @date    2020年6月20日
     * @return string
     */
    protected function getResponseFormat()
    {
        $format = strtolower(Request::header('Format'));
        if (!in_array($format, $this->allowFormat)) {
            $format = $this->format;
        }
        return $format;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月20日
     * @return boolean
     */
    protected function getIsResponseXml()
    {
        return $this->getResponseFormat() === 'xml';
    }
}
