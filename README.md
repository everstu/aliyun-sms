# aliyun-sms
## 阿里云短信API扩展包

##### 简易使用实例
###### 安装扩展
`composer require everstu/aliyun-sms=^v1.0`
###### 使用扩展
```
use everstu\aliyun\aliSms;
$config = [
        'AccessKeyId'=>'AccessKeyId',//阿里accesskeyid 必传
        'AccessSecret'=>'AccessSecret',//阿里secret 必传
        'SignName'=>'短信签名', //短信签名 选填 如未传需调用方法动态设置
        'TemplateCode'=>'TemplateCode',//模板CODE 选填 如未传需调用方法动态设置
];
//实例化发送对象
$aliyunSms = new aliSms($cofing);
$aliyunSms = $aliyunSms->setSignName('短信签名')->setTemplateCode('模板code');
$response  = $aliyunSms->SendSms(
    '1388888888', //手机号，可以传入数组，发送同一通知可以传入数组 上限为1000个
    [
        'code'=>1133
    ]
);

//response 返回请求类实例
支持方法
$response->isSuccess();//如果请求响应code为200-300之间返回true否则返回false
$response->response;//接口返回原始响应体
更多详细使用方法请查看源码。
```
