<?php

namespace application\common\library\test;

// 测试添加物流
class AddLogistics
{
    /**
     * @var string 域名
     */
    protected $apiDomain = 'http://tiandao.com';

    /**
     * @var string 接口URI
     */
    protected $apiUri = '/RoleLogistics/addLogisticsV2';

    /**
     * @var string Token
     */
    protected $token = '';

    /**
     * @var string 密钥
     */
    protected $signToken = '';

    /**
     * @var string 飞舟ID集合
     */
    protected $boatID = '';

    /**
     * @var string 飞舟运输配置
     */
    protected $onlyShippedOnce = '';

    /**
     * @var array 错误信息
     */
    protected $errors = [];

    /**
     * @var string 物流运输规则
     */
    protected $route = '';

    public function __construct($token, $signToken, $onlyShippedOnce, $route)
    {
        $this->token = $token;
        $this->signToken = $signToken;
        $this->onlyShippedOnce = $onlyShippedOnce;
        if (!empty($onlyShippedOnce)) {
            $onlyShippedOnce = explode('|', $onlyShippedOnce);
            $boatID = [];
            foreach ($onlyShippedOnce as $key => $value) {
                $value = explode('#', $value);
                $boatID[] = $value[0];
            }
            $this->boatID = implode('#', $boatID);
        }
        $this->route = $route;
    }

    /**
     * 测试添加物流
     */
    public function run()
    {
        echo "<h1>开始测试\"添加物流\"</h1><br />";
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->apiDomain,
        ]);
        $requestResult = $client->post($this->apiUri, [
            'form_params' => [
                'Token' => $this->token,
                'SignToken' => $this->signToken,
                'OnlyShippedOnce' => $this->onlyShippedOnce,
                'Route' => $this->route
            ],
            'timeout' => 20
        ]);
        $requestResult = json_decode($requestResult->getBody()->getContents(), true);

        if (empty($requestResult) || $requestResult['Status'] != 100) {
            $this->errors[] = "接口调用失败";
        } else {
            if (empty($requestResult['data']['Logistics']['ID'])) {
                $this->errors[] = "物流ID为空";
            }
            if ($requestResult['data']['Logistics']['BoatID'] != $this->boatID) {
                $this->errors[] = "飞舟ID错误";
            }
            if ($requestResult['data']['Logistics']['OnlyShippedOnce'] != $this->onlyShippedOnce) {
                $this->errors[] = "飞舟运输配置错误";
            }
        }

        if (count($this->errors) == 0) {
            echo "测试通过";
        } else {
            echo "<font color='red'>测试失败，错误信息如下：</font><br />";
            foreach ($this->errors as $error) {
                echo "<li>" . $error . "</li>";
            }
            echo "返回值：";
            print_r($requestResult);
        }
    }
}