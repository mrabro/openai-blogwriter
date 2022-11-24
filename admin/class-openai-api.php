
<?php
require OPENAI_DIR . '/lib/vendor/autoload.php';
use Curl\Curl;
class OpenAI_BlogWriter{

    private static $completionEndpoint = 'https://api.openai.com/v1/completions';

    public static function getOutlines($topic){
        $options = get_option( 'openai_settings' );
        if(!isset($options['openai_text_field_0'])){
            return "Invalid API KEY";
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => self::$completionEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "model": "text-davinci-002",
            "prompt": "Generate blog sections for following topic: '.$topic.'",
            "temperature": 0.7,
            "max_tokens": 256,
            "top_p": 1,
            "frequency_penalty": 0,
            "presence_penalty": 0
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$options['openai_text_field_0'],
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);

    }

}