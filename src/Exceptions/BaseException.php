<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2023 seffeng
 */
namespace Seffeng\Basics\Exceptions;

use Symfony\Component\Routing\Exception\ExceptionInterface;

class BaseException extends \RuntimeException implements ExceptionInterface
{
    /**
     *
     * @author zxf
     * @date   2023-11-16
     * @param string $message
     * @param integer $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $message === '' && $message = $this->defaultMessage();
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     * @author zxf
     * @date   2023-11-16
     * @return string
     */
    protected function defaultMessage()
    {
        return '';
    }
}
