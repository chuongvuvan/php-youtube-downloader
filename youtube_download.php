<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/20/18
 * Time: 17:09
 */
/**
 * Get Data use cURL
 * @param string $url
 * @return mixed|string
 */
function curl($url = '')
{
    $ch = @curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $head[] = "Connection: keep-alive";
    $head[] = "Keep-Alive: 300";
    $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $head[] = "Accept-Language: en-us,en;q=0.5";
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Expect:'
    ));
    // Response
    $response = curl_exec($ch);
    $err      = curl_error($ch);
    curl_close($ch);
    if ($err) {
        $result = "cURL Error #:" . $err;
    } else {
        $result = $response;
    }
    return $result;
}
/**
 * Get link download from Youtube
 * @param string $videoId
 * @return array|null
 */
function get_link_download_from_youtube($videoId = '')
{
    $youtubeUrl = 'http://www.youtube.com/watch?v=' . $videoId;
    if ($contentHtml = curl($youtubeUrl)) {
        if (preg_match('/;ytplayer\.config\s*=\s*({.*?});/', $contentHtml, $matches)) {
            $jsonData  = json_decode($matches[1], true);
            $streamMap = $jsonData['args']['url_encoded_fmt_stream_map'];
            $videoUrls = array();
            foreach (explode(',', $streamMap) as $url) {
                $url = str_replace('\u0026', '&', $url);
                $url = urldecode($url);
                parse_str($url, $data);
                $dataURL = $data['url'];
                unset($data['url']);
                $videoUrls[] = array(
                    'itag' => $data['itag'],
                    'quality' => $data['quality'],
                    'url' => $dataURL . '&' . urldecode(http_build_query($data))
                );
            }
            return $videoUrls;
        }
    }
    return null;
}
