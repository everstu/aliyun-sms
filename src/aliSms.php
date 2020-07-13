<?php

namespace everstu\aliyun;

class aliSms extends Core
{
    /**
     * 接口请求域名
     * @see https://help.aliyun.com/document_detail/101511.html
     * @var string
     */
    public $baseApi = 'dysmsapi.aliyuncs.com';

    /**
     * Sms constructor.
     * @param $config
     * @throws \Exception
     */
    public function __construct($config)
    {
        parent::__construct($config);

        if (isset($config['SignName']))//配置中指定签名
        {
            $this->setQueryParam('SignName', $config['SignName']);
        }

        if (isset($config['TemplateCode']))//配置中指定模板ID
        {
            $this->setQueryParam('TemplateCode', $config['TemplateCode']);
        }

        $this->setQueryParam('RegionId', 'cn-hangzhou');
        $this->setQueryParam('Version', '2017-05-25');
    }

    /**
     * 调用SendSms发送短信。
     * @param string|array $phone 手机号码 传入数组为批量发送短信
     * @param array $data 模板变量
     * @return HttpRequest
     * @throws \Exception
     * @see https://help.aliyun.com/document_detail/101414.html
     */
    public function SendSms($phone, $data = [])
    {
        $checkParamArr = ['PhoneNumbers', 'SignName', 'TemplateCode'];
        $this->setCheckParamArr($checkParamArr);
        $this->setQueryParam('Action', __FUNCTION__);
        if (is_array($phone))
        {
            if (count($phone) > 1000)
            {
                throw new \Exception('Too Many Phone Numbers, The Max Is 1000');
            }

            array_walk($phone, [$this, 'validatePhoneNum']);

            $phone = implode(',', $phone);
        }
        else
        {
            $this->validatePhoneNum($phone);
        }

        $this->setQueryParam('PhoneNumbers', $phone);
        if (empty($data) == false)
        {
            $this->setQueryParam('TemplateParam', json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        return $this->exec();
    }

    /**
     * 调用QuerySendDetails接口查看短信发送记录和发送状态。
     * @param int|string $phone
     * @param string $SendDate
     * @param int $CurrentPage
     * @param int $PageSize
     * @param string $BizId
     * @return HttpRequest
     * @throws \Exception
     * @see https://help.aliyun.com/document_detail/102352.html
     */
    public function QuerySendDetails($phone, $SendDate, $CurrentPage = 1, $PageSize = 20, $BizId = '')
    {
        $this->validatePhoneNum($phone);
        $this->setQueryParam('Action', __FUNCTION__);
        $this->setQueryParam('PhoneNumber', $phone);
        $this->setQueryParam('CurrentPage', $CurrentPage);
        if ($PageSize < 1 || $PageSize > 50)
        {
            $PageSize = 10;
        }
        $this->setQueryParam('PageSize', $PageSize);
        $this->setQueryParam('SendDate', date('YmdHis', strtotime($SendDate)));
        if (empty($BizId) == false)
        {
            $this->setQueryParam('BizId', $BizId);
        }

        return $this->exec();
    }

    /**
     * 设置短信模板码
     * @param string $code 模板code
     * @return $this
     */
    public function setTemplateCode($code)
    {
        $this->setQueryParam('TemplateCode', $code);

        return $this;
    }

    /**
     * 设置短信
     * @param string $name 短信签名
     * @return $this
     */
    public function setSignName($name)
    {
        $this->setQueryParam('SignName', $name);

        return $this;
    }

    /**
     * 正则校验手机号是否正确
     * @param $phone
     * @return bool
     * @throws \Exception
     */
    protected function validatePhoneNum($phone)
    {

        $preg_phone = '/^1[23456789]\d{9}$/ims';
        if (preg_match($preg_phone, $phone))
        {
            $ret = true;
        }
        else
        {
            throw  new \Exception('手机号格式不正确');
        }

        return $ret;
    }
}