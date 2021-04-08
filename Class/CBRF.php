<?php


class CBRF
{

    public const DAILY_RATE_URL = "http://www.cbr.ru/scripts/XML_daily.asp";
    public const DATE_FORMAT = 'd/m/Y';

    /**
     * @param $url
     * @param array $getData
     * @return array
     */
    public function getData($url, $getData = [])
    {
        $url = $url . '?' . http_build_query($getData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        curl_close($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status' => $http_status,
            'result' => $result
        ];
    }

    /**
     * @param $dataStr
     * @param $dataType
     * @return false|string|null
     */
    public function strToJson($dataStr, $dataType)
    {
        $jsonStr = null;

        switch ($dataType) {
            //xml string
            case 'xml':
                $xml = simplexml_load_string($dataStr);
                $jsonStr = json_encode($xml);
                break;
        }

        return $jsonStr;
    }

    /**
     * @param string|null $date
     * @return string|null
     */
    public function getCurrencyRates($date = null)
    {

        $result = null;

        //if dont isset date => today
        if (!isset($date)) {
            $now = new DateTime();
            $date = $now->format(self::DATE_FORMAT);
        }

        $data = $this->getData(self::DAILY_RATE_URL, ['date_req' => $date]);

        if ($data['status'] == 200)
            //result json
            $result = $this->strToJson($data['result'], 'xml');


        return $result;
    }

}