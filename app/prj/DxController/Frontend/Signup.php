<?php
define('EOL', "\r\n");

DxFactory::import('DxController_Frontend');

class DxController_Frontend_Signup extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.signup.sms' => 'smsSubscribe',

    );

    public function smsSubscribe()
    {
        $signup = DxApp::config('signup');

        $this->validate(array('phone'));

        if (empty($_GET['sms_code'])) {
            $sms_code = $this->smsGetKey();

            return $this->response(
                array(
                    'status'  => 'OK',
                    'message' => $sms_code,
                )
            );
        }

        $this->validate(array('sms_code'));

        $curl = curl_init();

//        $add_part = '';
        $add_part = '&group='. $this->getContext()->getCity()->getSmsSubscriptionId();

//        if (!empty($_GET['unsub'])) {
//            $add_part = '&group='. $this->getContext()->getCity()->getSmsSubscriptionId();
//        }

        $phone = $_GET['phone'];
        if ($phone[0] == 8) {
            $phone[0] = 7;
        }

        $url = 'http://cab.websms.ru/api/subscription/back_add.asp?id='. $signup['sms']['id'] .'&smskey='. $_GET['sms_code'] .'&AvtoPass='. $signup['sms']['auto_pass'] .'&NumTel='. $phone . '&group=' . $add_part;

        curl_setopt($curl, CURLOPT_URL,            $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        curl_close($curl);

        return $this->response(
            array(
                'status'  => 'OK',
                'message' => $response,
            )
        );
    }

    public function smsUnsubscribe()
    {
        $signup = DxApp::config('signup');

        $this->validate(array('phone', 'sms_code'));

        $phone    = $_GET['phone'];
        if ($phone[0] == 8) {
            $phone[0] = 7;
        }

        $sms_code = $_GET['sms_code'];

        $url = 'http://cab.websms.ru/api/subscription/back_add.asp?id='. $signup['sms']['id'] .'&smskey='. $sms_code .'&AvtoPass='. $signup['sms']['auto_pass'] .'&NumTel='. $phone;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,            $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = trim(curl_exec($curl));

        curl_close($curl);

        return $this->response(
            array(
                'status'  => 'OK',
                'message' => $response,
            )
        );
    }

    public function smsGetKey()
    {
        $signup = DxApp::config('signup');

        $curl = curl_init();

        $this->validate(array('phone'));
        $phone = $_GET['phone'];
        if ($phone[0] == 8) {
            $phone[0] = 7;
        }

        $url = 'http://cab.websms.ru/api/subscription/back_verify.asp?websms_id='. $signup['sms']['id'] .'&avtopass='. $signup['sms']['auto_pass'] .'&NumTel='. $phone;

        curl_setopt($curl, CURLOPT_URL,            $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $sms_key = trim(curl_exec($curl));

        curl_close($curl);

        return $sms_key;
    }

    public function response(array $array = array())
    {
        $json = json_encode($array);

        $this->getContext()->addHeader('Content-Type: application/json');

        echo $json;

        DxApp::terminate();
    }

    /**
     * Validates fields
     * If field got error, it will be outputted and script will be terminated
     *
     * @param array $fields Fields to validate
     * @return null|boolean
     */
    public function validate(array $fields)
    {
        foreach ($fields as $field) {
            switch ($field) {
                case 'phone':
                        if (empty($_GET['phone'])) {
                            return $this->response(
                                array(
                                    'status'     => 'ERROR',
                                    'error_code' => 'ERR_PHONE_EMPTY',
                                )
                            );
                        }

                        if (!is_numeric($_GET['phone']) || mb_strlen($_GET['phone']) != 11) {
                            return $this->response(
                                array(
                                    'status'     => 'ERROR',
                                    'error_code' => 'ERR_PHONE_INVALID',
                                )
                            );
                        }
                    break;
                case 'sms_code':
                        if (empty($_GET['sms_code'])) {
                            return $this->response(
                                array(
                                    'status'     => 'ERROR',
                                    'error_code' => 'ERR_SMSCODE_EMPTY',
                                )
                            );
                        }
                    break;
                default:
                    break;
            }
        }

        return true;
    }
}