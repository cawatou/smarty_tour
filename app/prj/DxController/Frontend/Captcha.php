<?php
DxFactory::import('DxController_Frontend');

class DxController_Frontend_Captcha extends DxController_Frontend
{
    /** @var array */
    protected $cmd_method = array(
        '.callback.captcha' => 'callback',
        '.feedback.captcha' => 'feedback',
        '.faq.captcha'      => 'faq',
        '.comment.captcha'  => 'comment',
    );

    /**
     * @return null
     *
     * @protected
     */
    protected function callback()
    {
        $this->captcha(
            '__CALLBACK_CAPTCHA__',

            array(
                'allowed_symbols' => '1234567890',
            )
        );
    }

    /**
     * @return null
     *
     * @protected
     */
    protected function feedback()
    {
        $this->captcha(
            '__FEEDBACK_CAPTCHA__',

            array(
                'allowed_symbols' => '1234567890',
            )
        );
    }

    /**
     * @return null
     *
     * @protected
     */
    protected function faq()
    {
        $this->captcha(
            '__FAQ_CAPTCHA__',

            array(
                'allowed_symbols' => '1234567890',
            )
        );
    }

    /**
     * @return null
     *
     * @protected
     */
    protected function comment()
    {
        $this->captcha(
            '__COMMENT_CAPTCHA__',

            array(
                'allowed_symbols' => '1234567890',
            )
        );
    }

    /**
     * @param string $key
     * @param array $custom
     * @return null
     *
     * @protected
     */
    protected function captcha($key = '__CAPTCHA__', array $custom = array())
    {
        /** @var $captcha Utils_Captcha */
        $captcha = DxFactory::getInstance('Utils_Captcha', array($custom));

        $_SESSION[$key] = $captcha->getKeyString();
    }
}