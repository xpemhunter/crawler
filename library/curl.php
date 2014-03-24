<?php
/**
 * Curl
 *
 * Curl module used for operations with choosen URL via CURL extension
 * 
 * @author Igor
 */
class Curl 
{
    /**
    *  query
    *         
    *  Make query to remote URL with choosen params
    *  
    *  @param string       $url Url of site
    *  @param string       $post Post data for sendind to remotre url
    *  @param string       $basicAuth Basic auth
    *  @param string       $userAgent Data about user agent
    *  @param boolean       $headers Mark for return headers from remore url
    *  @return string     result data
    */
    public function query($url, $post = '', $basicAuth = ':', $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.4', $headers = FALSE) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_ENCODING, 'utf-8');
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 999);
        
        if($headers)
        {
            curl_setopt($ch, CURLOPT_HEADER, $headers);
            curl_setopt($ch, CURLOPT_NOBODY, true);
        }

        if($post) 
        {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        
        if(defined('COOKIES')) 
        {
            curl_setopt($ch, CURLOPT_COOKIEFILE, COOKIES);
            curl_setopt($ch, CURLOPT_COOKIEJAR, COOKIES);
        }
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, $basicAuth);

        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);

        $data = curl_exec($ch);
        curl_close($ch);
        
        return $data;
    }

}