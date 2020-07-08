<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Seffeng\Basics\Constants\ErrorConst;
use Seffeng\Basics\Base\Response;

class Handler extends ExceptionHandler
{
    /**
     *
     * @var string
     */
    protected $asJson = false;

    /**
     *
     * @var ErrorConst
     */
    protected $errorClass = ErrorConst::class;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        //
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return $this->renderException($request, $exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function renderException($request, $e)
    {
        $e = $this->prepareException($e);

        if ($this->asJson || $request->expectsJson()) {
            $exception = (config('app.debug') && $e->getMessage()) ? [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : [];
            if ($this->isHttpException($e) && in_array($e->getStatusCode(), $this->errorClass::fetchItems())) {
                $data = $this->errorClass::responseError($this->errorClass::getError($e->getStatusCode()), $exception, $e->getStatusCode());
            } else {
                $data = $this->errorClass::responseError($this->errorClass::getError($e->getCode() > 0 ? $e->getCode() : null), $exception);
            }
            $response = new Response();
            return $response->setContent($data)->send();
        } else {
            return parent::render($request, $e);
        }
    }
}
