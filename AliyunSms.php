<?php
/**
 * User: Listen
 * Date: 2018/7/30
 * Time: 下午6:21
 * Description: 阿里云短信发送
 * version: 0.0.1
 */

namespace Aliyun;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

// 加载区域结点配置
Config::load();

class AliyunSms
{
    static  $acsClient = null;
    private $accessKeyid;
    private $accessKeySecre;
    private $SignName;
    private $TemplateCode;
    private $TemplateParam;
    private $OutId;
    private $SmsUpExtendCode;


    /**
     * @return mixed
     */
    public function getAccessKeyid()
    {
        return $this->accessKeyid;
    }

    /**
     * @param mixed $accessKeyid
     */
    public function setAccessKeyid($accessKeyid)
    {
        $this->accessKeyid = $accessKeyid;
    }

    /**
     * @return mixed
     */
    public function getAccessKeySecre()
    {
        return $this->accessKeySecre;
    }

    /**
     * @param mixed $accessKeySecre
     */
    public function setAccessKeySecre($accessKeySecre)
    {
        $this->accessKeySecre = $accessKeySecre;
    }

    /**
     * @return mixed
     */
    public function getSignName()
    {
        return $this->SignName;
    }

    /**
     * @param mixed $SignName
     */
    public function setSignName($SignName)
    {
        $this->SignName = $SignName;
    }

    /**
     * @return array
     */
    public function getTemplateParam()
    {
        return $this->TemplateParam;
    }

    /**
     * @param array $TemplateParam
     */
    public function setTemplateParam($TemplateParam)
    {
        $this->TemplateParam = $TemplateParam;
    }

    /**
     * @return mixed
     */
    public function getTemplateCode()
    {
        return $this->TemplateCode;
    }

    /**
     * @param mixed $TemplateCode
     */
    public function setTemplateCode($TemplateCode)
    {
        $this->TemplateCode = $TemplateCode;
    }

    /**
     * @return string
     */
    public function getOutId()
    {
        return $this->OutId;
    }

    /**
     * @param string $OutId
     */
    public function setOutId($OutId)
    {
        $this->OutId = $OutId;
    }

    /**
     * @return mixed
     */
    public function getSmsUpExtendCode()
    {
        return $this->SmsUpExtendCode;
    }

    /**
     * @param mixed $SmsUpExtendCode
     */
    public function setSmsUpExtendCode($SmsUpExtendCode)
    {
        $this->SmsUpExtendCode = $SmsUpExtendCode;
    }


    public function __construct($accessKeyid,$accessKeySecre,$SignName,$TemplateCode,$OutId='',$SmsUpExtendCode)
    {
        $this->accessKeyid = $accessKeyid;
        $this->accessKeySecre = $accessKeySecre;
        $this->SignName = $SignName;
        $this->TemplateCode = $TemplateCode;
        $this->OutId = $OutId;
        $this->SmsUpExtendCode = $SmsUpExtendCode;
    }


    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public  function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = $this->accessKeyid; // AccessKeyId

        $accessKeySecret = $this->accessKeySecre; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public function sendSms($tel,$TemplateParam) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        //可选-启用https协议
        //$request->setProtocol("https");

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($tel);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($this->SignName);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($this->TemplateCode);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        //判断是否为有效值 数组格式为 "array("key"=>"value")"
        if(is_array($TemplateParam) && isset($TemplateParam))
        {
            $request->setTemplateParam(json_encode($TemplateParam, JSON_UNESCAPED_UNICODE));
        }

        // 可选，设置流水号
        $request->setOutId($this->OutId);

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode($this->SmsUpExtendCode);

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    }

}