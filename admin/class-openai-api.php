<?php
class OpenAI_BlogWriter{

    private static $completionEndpoint = 'https://api.openai.com/v1/completions';
    private static $imageGeneration = 'https://api.openai.com/v1/images/generations';

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
    
    
    public static function generateBlog($data){
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
            "model": '.$data['model'].',
            "prompt": "Generate a blog on Topic: '.$data['topic'].'",
            "temperature": '.$data['temperature'].',
            "max_tokens": '.$data['tokens'].',
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

    public static function generateImages($data){
        $options = get_option( 'openai_settings' );
        if(!isset($options['openai_text_field_0'])){
            return "Invalid API KEY";
        }
        $curl = curl_init();
        $postFields = '{
            "prompt": "'.$data['prompt'].'",
            "n": '.$data['n'].',
            "size": "'.$data['size'].'"
        }';
        curl_setopt_array($curl, array(
        CURLOPT_URL => self::$imageGeneration,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$options['openai_text_field_0'],
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

    public static function generateBlogTags($data){
        $options = get_option( 'openai_settings' );
        if(!isset($options['openai_text_field_0'])){
            return "Invalid API KEY";
        }
        $curl = curl_init();
        $data = str_replace("

","\\n",$data);
        $postFields = '{
            "model": "text-davinci-002",
            "prompt": "Generate the SEO tags for below blog: \\n\\n'.$data.'\\n\\ntags:\\n\\n",
            "temperature": 0.7,
            "max_tokens": 150,
            "top_p": 1,
            "frequency_penalty": 0,
            "presence_penalty": 0
        }';
        curl_setopt_array($curl, array(
        CURLOPT_URL => self::$completionEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postFields,
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