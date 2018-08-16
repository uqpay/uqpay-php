<?php
namespace core;
use Whoops\Exception\ErrorException;

class core{
    public $assign=Array();
    public $config = array(
        "paygateConfig" => "https://paygate.uqpay.com/"
    );
    public static function run(){
        if(!isset($_SERVER['PATH_INFO'])||$_SERVER['PATH_INFO']=='/'||$_SERVER['PATH_INFO']==''){
            $class_name="\\app\\home";
            $fn='index';
        }else{
            $path_info=explode('/',substr($_SERVER['PATH_INFO'],1));
            $class_name="\\app\\".$path_info[0];
            $fn=isset($path_info[1])&&$path_info[1]?$path_info[1]:'index';
        }
                $pages=new $class_name();
                    $pages->$fn();

    }

    static function autoload($path){
        $file=str_replace("\\","/",$path).".php";
        if($file){
            include $file;
        }else{
            include 'app/view/index.html';
        }
    }

    function display($file){
       if(count($this->assign)){
           extract($this->assign);
       }
       include VIEW_PATH.$file.'.html';
   }
    function assign($k,$v){
        $this->assign[$k]=$v;
    }
    function redirect($url){
        header("Location:$url");
    }
    function json($data)
    {
        header('Content-Type:text/json');
        echo json_encode($data);
    }
    function httpGet(){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config["paygateConfig"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
    function httpArrayPost($url,$data){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS =>  http_build_query($data),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            throw new ErrorException("cURL Error #:" . $err);
        } else {
            return $response;
        }
    }
    function httpArrayJson($data){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config["paygateConfig"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS =>  http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', 'Content-Length:' . strlen($data)
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}