<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * 手机号验证
 * @author zxf
 * @date    2019年11月15日
 */
class Phone implements Rule
{
    /**
     *
     * @var string
     */
    protected $regex = '/^1\d{10}$/';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $regex = null)
    {
        if (!is_null($regex)) {
            $this->regex = $regex;
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        if (is_numeric($value)) {
            return boolval(preg_match($this->regex, $value));
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute格式错误！';
    }
}
