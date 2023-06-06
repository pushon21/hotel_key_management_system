<?php
/**
 * Created by PhpStorm.
 * User: CosMOs
 * Date: 9/27/2022
 * Time: 5:14 PM
 */


function func_get_content($myurl, $method = 'get', $posts = [], $headers = [],$encoding=0)
{

    sleep(rand(0,3));
    $host = parse_url(urldecode($myurl))['host'];
    //   /*
    if($headers == [])
    {
        $headers = [
            "Host: ".$host,
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko/20100101 Firefox/68.00".rand(0,999999),
            "Accept-Language: en-US,en;q=0.5",
            // "Accept-Encoding: gzip, deflate, br",
            "Connection: keep-alive",
            "Upgrade-Insecure-Requests: 1",
            "TE: Trailers",];
    }
    // */

    $myurl = str_replace(" ","%20",$myurl);
    // global $range;
    $ch = curl_init();

    //  $agent = 'tab mobile';
    // curl_setopt($ch, CURLOPT_PROXY, '85.26.146.169:80');
    curl_setopt($ch, CURLOPT_URL, $myurl);
    // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

    //  curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
    //  curl_setopt( $ch, CURLOPT_COOKIEFILE,dirname(__FILE__) . '/cookie.txt');
    //  curl_setopt($ch, CURLOPT_HEADER, true); // header
    // curl_setopt($ch, CURLOPT_NOBODY, true); // header
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    //  curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_RANGE, $range);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch,CURLOPT_TIMEOUT , 60);
    # sending manually set cookie
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if($method != 'get')
    {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($posts));
    }

    //  curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"serialno\":\"$code\"}");


    //   $error = curl_error($ch);
    $result = curl_exec($ch);
    curl_close($ch);
    if($encoding)
    {
        return mb_convert_encoding($result, 'utf-8','auto');
    }

    // debug
    //  file_put_contents($this->ROOT.'/webpage.txt',$result);

    return $result;
    //  return mb_convert_encoding($result, 'UTF-8','auto');
}

function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}