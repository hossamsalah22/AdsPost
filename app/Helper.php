<?php

if(!function_exists('responseJson')) {
    function responseJson($data, $token = null, $status = 200, $message='Success') {
        $response = [
            'message' => $message,
            'data' => !empty($data)?$data:null,
            'statusCode' => $status
        ];
        if(!is_null($token)) {
            $response['access_token'] = $token;
        }
        return response($response, $status);
    }
};
