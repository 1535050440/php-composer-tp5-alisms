<?php
/**
 * Created by PhpStorm.
 * User: 12155
 * Date: 2019/7/21
 * Time: 22:44
 */

namespace DengTp5;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliSms
{
    /**
     * 发送短信sendSms
     * $accessKeyId                           阿里云key
     * $accessSecret                          阿里云Secret
     * $mobile                                发送指定的手机号
     * $signName                              阿里云短信签名
     * $templateCode                          阿里云短信模板code
     * $paramArray['code']                    验证码：code
     * @param $paramArray
     * @return array
     * @throws ClientException
     * @author:  deng    (2019/7/21 23:11)
     */
    public static function sendSms($paramArray)
    {
        $accessKeyId = $paramArray['accessKeyId'];
        $accessSecret = $paramArray['accessSecret'];
        $mobile = $paramArray['mobile'];
        $signName = $paramArray['signName'];
        $templateCode = $paramArray['templateCode'];
        $templateParam['code'] = $paramArray['code'];

        AlibabaCloud::accessKeyClient($accessKeyId, $accessSecret)
            ->regionId('cn-hangzhou') // replace regionId as you need
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $mobile,
                        'SignName' => $signName,
                        'TemplateCode' => $templateCode,
                        'TemplateParam' => json_encode($templateParam)
                    ],
                ])
                ->request();

        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }

        return $result->toArray();
    }

}