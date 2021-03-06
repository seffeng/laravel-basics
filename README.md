## Laravel Basics

### 安装

```shell
# laravel7、laravel8
$ composer require seffeng/laravel-basics

# laravel6
$ composer require seffeng/laravel-basics=^2.*
```

### 目录说明

```
├─Base                      基础类
│   Controller.php              控制器
│   FormRequest.php             表单验证
│   Model.php                   数据库
│   Service.php                 服务
├─Constants                 常量定义
│   DeleteConst.php             删除
│   ErrorConst.php              错误
│   StatusConst.php             状态
│   TypeConst.php               类型
├─Exceptions                异常
│   BaseException.php           异常
│   Handler.php                 异常处理器
├─Helpers                   帮助类
│   Arr.php                     数组处理
│   Json.php                    Json处理
│   ReplaceArrayValue.php
│   TimeHelper.php              时间处理
│   UnsetArrayValue.php
└─Rules                     验证规则
    Password.php                密码
    Phone.php                   手机号
```

### 示例

```php
/**
 * TestRequest.php
 * 表单验证示例
 */
namespace App\Http\Requests;

use Seffeng\Basics\Base\FormRequest;
use Seffeng\Basics\Rules\Phone;

class TestRequest extends FormRequest
{
    protected $fillable = ['phone', 'password'];

    public function rules()
    {
        return [
            'phone' => [
                'required',
                new Phone()
            ],
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [

        ]);
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'phone' => '手机号',
            'password' => '密码',
        ]);
    }
}

/**
 * TestController.php
 * 表单验证示例 - 控制器
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Seffeng\Basics\Base\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $form = new TestRequest();
        $data = $form->load($request->all());
        $validator = Validator::make($data, $form->rules(), $form->messages(), $form->attributes());
        $errors = $form->getErrorItems($validator);
        if ($form->getIsPass()) {
            return $this->responseSuccess($form->getFillItems());
        }
        return $this->responseError($errors['message'], $errors['data']);
    }
}
```

```json
// 验证成功
{
    "status": "success",
    "data": {
        "phone": "13800138000",
        "password": "123456"
    },
    "message": "success"
}
// 验证失败
{
    "status": "error",
    "code": 1,
    "data": {
        "phone": [
            "手机号格式错误！"
        ],
        "password": [
            "密码不能为空！"
        ]
    },
    "message": "手机号格式错误！ 密码不能为空！"
}
```
