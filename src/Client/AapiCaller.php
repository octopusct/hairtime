<?php
/**
 * Created by PhpStorm.
 * User: nosov
 * Date: 19.02.2017
 * Time: 19:24
 */

namespace App\Client;


use MongoDB\Driver\Exception\Exception;

class ApiCaller
{
    /**
     *  main variable
     * $_api_auth - array User-ID and Token send in Header
     * $_api_url - url for API reques
     */
    private $_api_auth;
    private $_api_url;


    /**
     * Constructor
     */
    /* public function __construct($api_url, $api_auth = array())
     {
         $this->_api_url = $api_url;
         $this->_api_auth = $api_auth;
     }*/
}
/**
 * @param $request_params
 * @return array
 */
/* public function sendGetRequest($request_param){
     try {
         $ch = curl_init($this->_api_url.'/'.$request_param);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         //execute request
         $result = curl_exec($ch);

         // json decode the result
         $result = @json_decode($result);

         if ($result == false) {
             throw new \Exception('Request was not correct.');
         }
     }catch(Exception $ex){
         $result ['success'] = false;
         $result ['message'] = $ex->getMessage();
         return $result;
     }
     return $result;
 }

 /**
  * @param $request_params
  * @return array
  */
/* public function sendPostRequest($request_params)
 {
     $params = array();
     $params = $request_params;

     // prepare request
     // init and setup curl handler
     try {
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->_api_url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_HEADER, $this->_api_auth);
         curl_setopt($ch, CURLOPT_PORT, count($params));
         curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

         //execute request
         $result = curl_exec($ch);

         // json decode the result
         $result = @json_decode($result);

         if ($result == false) {
             throw new \Exception('Request was not correct.');
         }

     }catch (Exception $ex){
         $result ['success'] = false;
         $result ['error'] = $ex->getMessage();
         return $result;
     }

     return $result;
 }
}