<?php

namespace AppBundle;

class Curl {

    /**
     * @param $url
     * @return mixed
     */
    public function getData($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    /**
     * @param $url
     * @return bool|int|string
     */
    function getFileSize($url) {
        $result = false;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_NOBODY, true );
        curl_setopt($curl, CURLOPT_HEADER, true );
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );

        $data = curl_exec($curl);
        curl_close($curl);

        if (!$data) {
            return $result;
        }

        $content_length = "unknown";
        $status = "unknown";

        if (preg_match("/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches)) {
            $status = (int) $matches[1];
        }

        if (preg_match("/Content-Length: (\d+)/", $data, $matches )) {
            $content_length = (int) $matches[1];
        }

        if ($status == 200 || ($status > 300 && $status <= 308)) {
            $result = $content_length;
        }

        return $result;
    }
} 