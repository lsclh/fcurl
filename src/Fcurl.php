<?php
// +----------------------------------------------------------------------
// | Created by PhpStorm.©️
// +----------------------------------------------------------------------
// | User: 程立弘©️
// +----------------------------------------------------------------------
// | Date: 2019-03-03 22:58
// +----------------------------------------------------------------------
// | Author: 程立弘 <1019759208@qq.com>©️
// +----------------------------------------------------------------------

namespace Lsclh\Fcurl;



use EasySwoole\Curl\Field;
use EasySwoole\Curl\Request;
use EasySwoole\Curl\Response;

/**
 * Class Curl
 * @package Utility\Curl
 *
 * $request = new Curl();
    $params = [
        'get' => [
            'nobase64' => 1,
            'musicid' => '109332150',
            'inCharset' => 'utf8',
            'outCharset' => 'utf-8'
        ],
        'opt' => [
            CURLOPT_REFERER => 'https://y.qq.com/n/yqq/song/001xiJdl0t4NgO.html'
        ]
    ];
    $content = $request->request('GET','https://c.y.qq.com/lyric/fcgi-bin/fcg_query_lyric.fcg', $params);
 */

class Fcurl
{
    public function __construct()
    {

    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $params ['get'=>'get数据','post'=>'post数据','header'=>'请求头','opt'=>['其他的setopt数据'=>'1243']]
     * @return Response
     */
    public function request(string $method, string $url, array $params = null): Response
    {
        $request = new Request( $url );


        switch( $method ){
            case 'GET' :
                if( $params && isset( $params['get'] ) ){
                    foreach( $params['query'] as $key => $value ){
                        $request->addGet( new Field( $key, $value ) );
                    }
                }
                break;
            case 'POST' :
                if( $params && isset( $params['form_params'] ) ){
                    foreach( $params['form_params'] as $key => $value ){
                        $request->addPost( new Field( $key, $value ) );
                    }
                }elseif($params && isset( $params['post'] )){
                    if(!isset($params['header']['Content-Type']) ){
                        $params['header']['Content-Type'] = 'application/json; charset=utf-8';
                    }
                    $request->setUserOpt( [CURLOPT_POSTFIELDS => $params['body']] );
                }
                break;
            default:
                throw new \InvalidArgumentException( "method eroor" );
                break;
        }
        $request->setUserOpt(['CURLOPT_HTTPHEADER'=>['Expect:']]);

        if( isset( $params['header'] ) && !empty( $params['header'] ) && is_array( $params['header'] ) ){
            foreach( $params['header'] as $key => $value ){
                $string   = "{$key}:$value";
                $header[] = $string;
            }

            $request->setUserOpt( [CURLOPT_HTTPHEADER => $header] );
        }

        if( isset( $params['opt'] ) && !empty( $params['opt'] ) && is_array( $params['opt'] ) ){

            $request->setUserOpt($params['opt']);
        }
        return $request->exec();
    }

}