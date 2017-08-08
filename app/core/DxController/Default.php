<?php

DxFactory::import('DxController');

class DxController_Default extends DxController
{
    protected $cmd_method = array(
        DxCommand::CMD_DEFAULT    => 'main',
        DxCommand::CMD_NOT_FOUND  => 'notFound',
        DxCommand::CMD_AUTH_ERROR => 'authorizationError'
    );

    protected function getCommandMethod(DxCommand $command)
    {
        return $this->cmd_method[$command->getCmd()];
    }

    protected function notFound()
    {
        $this->getContext()->addHeader('HTTP/1.0 404 Not Found');
        $html =
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
            <head>
                <title>404 Page not found</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <link href="http://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet" type="text/css" />
                <style type="text/css">
                    html, body, h1, h2, div { margin: 0; padding: 0; }
                    body { font-family: "Fjalla One", sans-serif; font-size: 14px; color: #fff; background: #0272A1; }
                    .container { position:absolute; top:50%; height: 150px; margin-top:-75px; width: 100%;}
                    .container-center { width: 400px; margin: 0 auto; }
                    .container h2 { font-size: 70px; text-transform: uppercase; }
                    .container div { font-size: 30px; }
                </style>
            </head>
            <body>
                <div class="container">
                        <div class="container-center">
                            <div>Page not found</div>
                            <h2>404 error</h2>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
        return $this->wrap($html);
    }

    protected function authorizationError()
    {
        $this->getContext()->addHeaders('Status: 401 Unauthorized');
        $html =
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
            <head>
                <title>404 Page not found</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <link href="http://fonts.googleapis.com/css?family=Fjalla+One" rel="stylesheet" type="text/css" />
                <style type="text/css">
                    html, body, h1, h2, div { margin: 0; padding: 0; }
                    body { font-family: "Fjalla One", sans-serif; font-size: 14px; color: #fff; background: #0272A1; }
                    .container { position:absolute; top:50%; height: 150px; margin-top:-75px; width: 100%;}
                    .container-center { width: 400px; margin: 0 auto; }
                    .container h2 { font-size: 70px; text-transform: uppercase; }
                    .container div { font-size: 30px; }
                </style>
            </head>
            <body>
                <div class="container">
                        <div class="container-center">
                            <div>Unauthorized</div>
                            <h2>401 error</h2>
                        </div>
                    </div>
                </div>
            </body>
            </html>';
        return $this->wrap($html);
    }

    protected function main()
    {
        $html =
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
            <head>
                <title>dxCMS v.4.x</title>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <link href="http://fonts.googleapis.com/css?family=Amatic+SC:400,700" rel="stylesheet" type="text/css" />
                <style type="text/css">
                    html, body, h1, h2, div { margin: 0; padding: 0; }
                    body { font-family: "Amatic SC", cursive; font-size: 14px; color: #000; background: #FFDD00; }
                    a { color: #000; text-decoration: none; }
                    .container { height: 400px; margin: 80px auto 0; position: relative; width: 900px; }
                    .content { overflow: hidden; }
                    .content div { float: left; font-size: 60px; height: 100%; line-height: 80px; overflow: hidden; padding: 0 25px; position: relative; text-align: right; width: 400px; text-transform: uppercase; font-weight: bold; }
                    div.content-right { text-align: left; border-left: 3px solid #000; width: 397px; color: #fff; }
                    .full { overflow: hidden; margin: 40px auto; width: 650px; }
                    .full h2 { font-size: 55px; float: left; padding: 0px 50px 0 0; }
                    .full a { background: #000; color: #fff; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; padding: 10px 40px; display: inline-block; font-size: 40px; -webkit-animation: zoomIn 0.7s ease-in-out 7s backwards; -moz-animation: zoomIn 0.7s ease-in-out 7s backwards; -ms-animation: zoomIn 0.7s ease-in-out 7s backwards; animation: zoomIn 0.7s ease-in-out 7s backwards; text-transform: uppercase; }
                    .full a:hover { -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)"; filter: alpha(opacity=80); opacity: 0.8; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="content">
                        <div class="content-left">
                            <h2><a href="http://www.rosapp.ru">rosapp</a></h2>
                        </div>
                        <div class="content-right">
                            <h2>great!</h2>
                        </div>
                    </div>
                    <div class="full">
                        <h2>We will help you win</h2>
                        <a href="/adm">Control Panel</a>
                    </div>
                </div>
            </body>
            </html>';
        return $this->wrap($html);
    }

    public function wrap($html)
    {
        return $html;
    }
}