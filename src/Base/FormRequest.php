<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Seffeng\Basics\Helpers\Arr;
use Illuminate\Support\Str;

/**
 *
 * @author zxf
 * @date    2019年11月6日
 * @property array $with
 * @property array $fillItems
 * @example
 *  $form = new FormRequest();
 *  $validator = Validator::make($form->load($request->all()), $form->rules(), $form->messages(), $form->attributes());
 *  $errors = $form->getErrorItems($validator);
 *  $form->getIsPass();
 *  $form->getFillItems();
 */
class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * fillable 参数格式
     * true-驼峰，false-下划线
     * 驼峰参数格式时$fillItems将同时存在驼峰和下划线两种值
     * @var boolean
     */
    protected $isCamel = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
    *
    * @var array
    */
    protected $fillItems = [];

    /**
     *
     * @var array
     */
    protected $with = [];

    /**
     *
     * @var string|array
     */
    protected $orderBy;

    /**
     *
     * @var array
     */
    protected $messageBag;

    /**
     *
     * @var string
     */
    protected $isPass = false;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \Illuminate\Foundation\Http\FormRequest::messages()
     */
    public function messages()
    {
        return [
            'required' => ':attribute不能为空！',
            //'min' => ':attribute至少:min位字符！',
            //'max' => ':attribute最多:max位字符！',
            //'between' => ':attribute必须:min~:max位字符！',
            'unique' => ':attribute已存在！',
            'integer' => ':attribute只能是数字！',
            'exists' => ':attribute不存在！',
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \Illuminate\Foundation\Http\FormRequest::attributes()
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     *  配置验证器实例。
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return \Illuminate\Validation\Validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (false) {
                $validator->errors()->add('field', 'Something is wrong with this field!');
            }
        });
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param array $with
     */
    public function setWith(array $with)
    {
        $this->with = $with;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @return array
     */
    public function getWith()
    {
        return $this->with;
    }

    /**
     *
     * @author zxf
     * @date   2020年3月23日
     * @param array|string $orderBy
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    /**
     *
     * @author zxf
     * @date   2020年3月23日
     * @return string|array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  \Illuminate\Validation\Validator $validator
     * @return array
     */
    public function getErrors($validator)
    {
        if (is_null($this->messageBag)) {
            if ($validator->passes()) {
                $this->isPass = true;
                $this->messageBag = [];
            } else {
                $this->messageBag = $validator->getMessageBag()->getMessages();
            }
        }
        return $this->messageBag;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  \Illuminate\Validation\Validator $validator
     * @param  boolean $isOne 仅返回一条错误
     * @param  boolean $cover $isOne为true时无效，$isOne为false时，存在相同错误是否只显示为一条
     * @return string|null
     */
    public function getErrorsToString($validator, bool $isOne = false, bool $cover = false)
    {
        $messages = $this->getErrors($validator);
        if ($messages) {
            if ($isOne) {
                foreach ($messages as $message) {
                    return Arr::get($message, '0', '');
                }
            } else {
                $errors = [];
                if ($cover) {
                    $tmpItems = [];
                    foreach ($messages as $message) {
                        $tmpItems = Arr::merge($tmpItems, $message);
                    }
                    $tmpItems = array_unique($tmpItems);
                    foreach ($tmpItems as $message) {
                        $errors[] = $message;
                    }
                } else {
                    foreach ($messages as $message) {
                        $errors[] = implode(' ', $message);
                    }
                }
                return implode(' ', $errors);
            }
        }
        return null;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  \Illuminate\Validation\Validator $validator
     * @param  boolean $isOne 仅返回一条错误
     * @param  boolean $cover $isOne为true时无效，$isOne为false时，存在相同错误是否只显示为一条
     * @return array
     */
    public function getErrorItems($validator, bool $isOne = false, bool $cover = false)
    {
        $messageItems = $this->getErrors($validator);
        if ($messageItems) {
            return [
                'data' => $messageItems,
                'message' => $this->getErrorsToString($validator, $isOne, $cover)
            ];
        }
        return [];
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  array $params
     * @return object
     */
    public function load(array $params)
    {
        if ($this->fillable) foreach ($this->fillable as $key) {
            if ($this->isCamel) {
                $this->fillItems[Str::snake($key)] = Arr::get($params, $key);
            }
            $this->fillItems[$key] = Arr::get($params, $key);
        }
        return $this->fillItems;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  string|int $key
     * @param  mixed $defalut
     * @return mixed
     */
    public function getFillItems($key = null, $defalut = null)
    {
        if (is_null($key)) {
            return $this->fillItems;
        }
        return Arr::get($this->fillItems, $key, $defalut);
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @param  string|integer $key
     * @param  mixed $value
     * @return boolean
     */
    public function setFillItem($key, $value)
    {
        if (array_search($key, $this->fillable) !== false) {
            $this->fillItems[$key] = $value;
            return true;
        }
        return false;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @return boolean
     */
    public function getIsPass()
    {
        return $this->isPass;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月15日
     * @return boolean
     */
    public function getIsExport()
    {
        return $this->getFillItems('export') == 1;
    }
}
