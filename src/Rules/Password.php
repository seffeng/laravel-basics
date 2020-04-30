<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * 密码验证
 * @author zxf
 * @date   2020年4月30日
 */
class Password implements Rule
{
    /**
     * 必须包含字母和数字
     * @var string
     */
    protected $regex = '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/';

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
        return boolval(preg_match($this->regex, $value));
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
