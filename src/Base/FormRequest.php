<?php
/**
 * @link http://github.com/seffeng/
 * @copyright Copyright (c) 2019 seffeng
 */
namespace Seffeng\Basics\Base;

use Seffeng\Basics\Constants\FormatConst;
use Seffeng\Basics\Constants\TypeConst;
use Seffeng\LaravelHelpers\Helpers\Str;
use Seffeng\LaravelHelpers\Helpers\Arr;

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
     *  过滤前后空格
     * @var boolean
     */
    protected $filter = true;

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
     * SQL Query Builder: $query->select($columns)...
     * @var array
     */
    protected $columns = ['*'];

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
     * 排序请求参数，如：$_GET['orderBy']
     * @var string
     */
    protected $orderByField = 'orderBy';

    /**
     * 数据表主键，非主键排序时追加主键排序
     * @var string
     */
    protected $orderByPrimaryKey = 'id';

    /**
     *
     * @var string|array
     */
    protected $groupBy;

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
     * 分页参数
     * @var string
     */
    protected $pageName = 'page';

    /**
     * 分页参数
     * @var string
     */
    protected $perPageName = 'perPage';

    /**
     * 每页默认数量
     * @var integer
     */
    protected $perPage = 10;

    /**
     *
     * @var integer
     */
    protected $page = 1;

    /**
     *
     * @var array
     */
    public $variables = [];

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
     * @date    2020年6月7日
     * @param  array $with
     * @return static
     */
    public function setWith(array $with)
    {
        $this->with = $with;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2023-11-06
     * @param  array $with
     * @return static
     */
    public function addWith(array $with)
    {
        if (is_array($this->with)) {
            $this->with = Arr::merge($this->with, $with);
        }
        return $this;
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
     * @date    2020年6月7日
     * @param  array|string $orderBy
     * @return static
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2023-11-06
     * @param  array $orderBy
     * @return static
     */
    public function addOrderBy(array $orderBy)
    {
        if (is_array($this->orderBy)) {
            $this->orderBy = Arr::merge($this->orderBy, $orderBy);
        }
        return $this;
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
     * @date   2020年7月22日
     * @param  array|string $orderBy
     * @return static
     */
    public function setGroupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2023-11-06
     * @param  array $groupBy
     * @return static
     */
    public function addGroupBy(array $groupBy)
    {
        if (is_array($this->groupBy)) {
            $this->groupBy = Arr::merge($this->groupBy, $groupBy);
        }
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2020年7月22日
     * @return string|array
     */
    public function getGroupBy()
    {
        return $this->groupBy;
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
            $value = Arr::get($params, $key);
            $this->filter && is_string($value) && $value = trim($value);
            if ($this->isCamel) {
                $this->fillItems[Str::snake($key)] = $value;
            }
            $this->fillItems[$key] = $value;
        }
        return $this->fillItems;
    }

    /**
     *
     * @author zxf
     * @date    2019年11月06日
     * @param  string|integer $key
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
            if ($this->isCamel) {
                $this->fillItems[Str::snake($key)] = $value;
            }
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

    /**
     *
     * @author zxf
     * @date    2020年05月04日
     * @return string
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     *
     * @author zxf
     * @date    2020年05月04日
     * @return string
     */
    public function getPerPageName()
    {
        return $this->perPageName;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @param  integer $perPage
     * @return static
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年05月04日
     * @return integer
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @param  integer $page
     * @return static
     */
    public function setPage(int $page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date    2020年6月7日
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * SQL 查询字段
     *
     * @author zxf
     * @date   2023-03-28
     * @param array $columns
     * @return static
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2023-11-06
     * @param  array $columns
     * @return static
     */
    public function addColumns(array $columns)
    {
        if (is_array($this->columns)) {
            $this->columns = Arr::merge($this->columns, $columns);
        }
        return $this;
    }

    /**
     * SQL 查询字段
     *
     * @author zxf
     * @date   2023-03-28
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     *
     * @author zxf
     * @date   2020年7月22日
     * @return static
     */
    public function loadVariables()
    {
        $this->variables = [
            'columns'   => $this->getColumns(),
            'fillItems' => $this->getFillItems(),
            'groupBy'   => $this->getGroupBy(),
            'orderBy'   => $this->getOrderBy(),
            'page'      => $this->getPage(),
            'perPage'   => $this->getPerPage(),
            'with'      => $this->getWith()
        ];
        return $this;
    }

    /**
     * 跳过验证
     *
     * @author zxf
     * @date   2023-03-28
     * @return static
     */
    public function skipValidator()
    {
        $this->isPass = true;
        return $this;
    }

    /**
     * orderBy=-num；代表 num 降序
     * orderBy=num；代表 num 升序
     * orderBy=-num,sort；代表 num 降序，sort 升序
     * @author zxf
     * @date   2021年6月21日
     * @return static
     */
    public function sortable()
    {
        $orderBy = $this->getFillItems($this->orderByField);
        if (is_string($orderBy) && $orderBy !== '') {
            $items = array_filter(array_unique(explode(',', str_replace(' ', '', $orderBy))));
            $orderBy = [];
            if ($items) {
                foreach ($items as $item) {
                    if ($item['0'] === '-') {
                        $key = $this->replaceSortKey(substr($item, 1));
                        $value = TypeConst::ORDERBY_DESC;
                    } else {
                        $key = $this->replaceSortKey($item);
                        $value = TypeConst::ORDERBY_ASC;
                    }
                    $key && $orderBy[$key] = $value;
                }
                if (!array_key_exists($this->orderByPrimaryKey, $orderBy)) {
                    $orderBy[$this->orderByPrimaryKey] = TypeConst::ORDERBY_DESC;
                }
            }
            count($orderBy) > 0 && $this->setOrderBy($orderBy);
        }
        return $this;
    }

    /**
     * 根据LastId分页
     *
     * @author zxf
     * @date   2023-11-06
     * @return void
     */
    public function pagingByLastId()
    {
        $lastId = $this->getFillItems('lastId');
        $this->setFillItem('lastId', null);
        $this->setIsLastId();
        if ($lastId) {
            $orderItems = Str::toArray($lastId, ',', ' ');
            if ($orderItems) foreach ($orderItems as $order) {
                $orderBy = explode(FormatConst::LASTID_DELIMITER, $order);
                $this->setFillItem('lastId', Arr::get($orderBy, '1'));
                break;
            }
        }
    }

    /**
     *
     * @author zxf
     * @date   2023-11-06
     * @param  boolean $isLastId
     * @return static
     */
    public function setIsLastId(bool $isLastId = true)
    {
        $this->setFillItem('isLastId', $isLastId);
        return $this;
    }

    /**
     *
     * @author zxf
     * @date   2023-11-06
     * @return boolean
     */
    public function getIsLastId()
    {
        return $this->getFillItems('isLastId') === true;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月7日
     * @param string $key
     * @return boolean
     */
    protected function replaceSortKey(string $key = null)
    {
        return (!is_null($key) && array_key_exists($key, $this->fetchSortKeyItems())) ? Arr::get($this->fetchSortKeyItems(), $key) : false;
    }

    /**
     *
     * @author zxf
     * @date   2020年12月7日
     * @return array
     */
    protected function fetchSortKeyItems()
    {
        return [
            // '接收字段' => '数据库字段',
            // 'userId' => 'id',
            // 'createDate' => 'created_at'
        ];
    }
}
