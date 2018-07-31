# aliyun-sms
Aliyun SMS Sending

阿里云短信发送 API 整合进Yii2

#使用
在 common/config/params.php 中增加
```php
'aliyun'=>array(
        'sms'=>array(
            'accessKeyid'=>'',        //AccessKeyId  https://ak-console.aliyun.com/
            'accessKeySecre'=>'',     //AccessKeySecret  https://ak-console.aliyun.com/
            'SignName'=>'',           //必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
            'TemplateCode'=>'',       //必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
            'OutId'=>'',                // 可选，设置流水号
            'SmsUpExtendCode'=>''     // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        )
    )
```

在 common/config/models 中增加文件
AliyunSendSms.php
```php
namespace common\models;

use Aliyun\AliyunSms;
use Yii;
use yii\base\Model;

class AliyunSendSms extends Model
{
    private $model;

    public function __construct(array $config = [])
    {

        $this->model = new AliyunSms(
            Yii::$app->params['aliyun']['sms']['accessKeyid'],
            Yii::$app->params['aliyun']['sms']['accessKeySecre'],
            Yii::$app->params['aliyun']['sms']['SignName'],
            Yii::$app->params['aliyun']['sms']['TemplateCode'],
            Yii::$app->params['aliyun']['sms']['OutId'],
            Yii::$app->params['aliyun']['sms']['SmsUpExtendCode']
        );

        //如果有多个签名，可用set方法设置不同的签名和模板
        //$this->model->setSignName();
        //$this->model->setTemplateCode();
        parent::__construct($config);
    }

    /**
     * User: Listen
     * Description:发送短信，返回短信结果集
     * @param $tel
     * @param $temparam
     * @return \Aliyun\stdClass
     */
    public function SendSms($tel,$temparam)
    {
        $result = $this->model->sendSms($tel,$temparam);
        return $result;
    }

    
    /**
     * User: Listen
     * Description:用不同的模板发送短信，返回结果集
     * @param $tel
     * @param $temparam
     * @param $TemplateCode
     * @return \Aliyun\stdClass
     */
    public function SendSmsOtherTemp($tel,$temparam,$TemplateCode)
    {
        $this->model->setTemplateCode($TemplateCode);
        $result = $this->model->sendSms($tel,$temparam);
        return $result;
    }

}
```


#License
除 “版权所有（C）阿里云计算有限公司” 的代码文件外，遵循 [MIT license](http://opensource.org/licenses/MIT) 开源。
