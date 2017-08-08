<?php
    if (isset($_GET['phpinfo'])) {
        phpinfo();
        exit();
    } elseif (isset($_GET['email'])) {
        $headers = 'From: support@rosapp.ru' . "\r\n" .
        'Reply-To: support@rosapp.ru' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    
        if (mail($_GET['email'], 'DxInstall email test', 'This is a test. Do not reply.', $headers, "-fsupport@rosapp.ru")) {
            print "Mail Sent Successfully";
        } else {
            print "Mail Not Sent";
        }
        exit();
    }

    defined('DS') or define('DS', DIRECTORY_SEPARATOR);
    defined('APP_DIR') or define('APP_DIR', __DIR__);

    defined('APP_PROJECT_DIR') or define('APP_PROJECT_DIR',  realpath(APP_DIR . DS . 'app' . DS . 'project'));
    defined('APP_CONFIG_DIR') or define('APP_CONFIG_DIR',  realpath(APP_DIR . DS . 'app' . DS . 'config'));
    defined('APP_VENDOR_DIR') or define('APP_VENDOR_DIR',  realpath(APP_DIR . DS . 'app' . DS . 'vendor'));
    defined('APP_VAR_DIR') or define('APP_VAR_DIR',  realpath(APP_DIR . DS . 'app' . DS . 'var'));
    defined('APP_VIEWS_DIR') or define('APP_VIEWS_DIR',  realpath(APP_DIR . DS . 'app' . DS . 'views'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title>Installation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css">
        html, body, h1, h2 { margin: 0; padding: 0; }
        body { font-family: Arial; font-size: 12px; color: #000; background: #FFF; }
        h1 { background: #000; color: #FFF; padding: 10px; }
        h2 { padding: 20px 0 15px 0px; }
        code { font-family: monaco, monospace; }

        .params { margin: 0 0 10px 0; padding: 10px; background: #FFFFC0; }
            .params table td, th { padding: 0.4em; text-align: left; }

        .test { margin: 0 40px 40px 40px; }
            .test table { border-collapse: collapse; width: 100%; }
                .test table th,
                .test table td { padding: 0.4em; text-align: left; vertical-align: top; }
                .test table th { width: 12em; font-weight: normal; }
                .test table tr:nth-child(odd) { background: #eee; }
                .test table td.pass { color: #191; }
                .test table td.fail { color: #911; }
                .test tr.gray td,
                .test tr.gray th { color: #999; }
    </style>
</head>

<body>
    <h1>DX: Installation</h1>
    <div class="params">
        <table>
            <tr><th>PHP version</th><td><?php echo PHP_VERSION; ?>, see <a href="?phpinfo">phpinfo()</a>, check <a href="?email=support@rosapp.ru">email</a></td></tr>
            <tr><th>Project directory</th><td><?php echo APP_PROJECT_DIR; ?></td></tr>
            <tr><th>Configuration directory</th><td><?php echo APP_CONFIG_DIR; ?></td></tr>
            <tr><th>Templates directory</th><td><?php echo APP_VIEWS_DIR; ?></td></tr>
            <tr><th>Vendor directory</th><td><?php echo APP_VENDOR_DIR; ?></td></tr>
            <tr><th>Var directory</th><td><?php echo APP_VAR_DIR; ?></td></tr>
        </table>
    </div>
    <div class="test">
        <h2>Environment Tests</h2>
        <table>
        <?php if (version_compare(PHP_VERSION, '5.4.0', '<')): ?>
        <tr>
            <th>magic_quotes_gpc</th>
            <?php if (get_magic_quotes_gpc()): ?>
                <td class="fail"><a href="http://www.php.net/manual/en/info.configuration.php#ini.magic-quotes-gpc">magic_quotes_gpc</a> must be FALSE.</td>
            <?php else: ?>
                <td class="pass">Pass &mdash; is set to FALSE</td>
            <?php endif ?>
        </tr>
        <?php endif ?>
        <tr>
            <th>PCRE UTF-8</th>
            <?php if (!@preg_match('/^.$/u', 'n')): ?>
                <td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with UTF-8 support.</td>
            <?php elseif (!@preg_match('/^\pL$/u', 'n')): ?>
                <td class="fail"><a href="http://php.net/pcre">PCRE</a> has not been compiled with Unicode property support.</td>
            <?php else: ?>
                <td class="pass">Pass</td>
            <?php endif ?>
        </tr>
        <tr>
            <th>Filters Enabled</th>
            <?php if (function_exists('filter_list')): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">The <a href="http://www.php.net/filter">filter</a> extension is either not loaded or not compiled in.</td>
            <?php endif ?>
        </tr>
        <tr>
            <th>Iconv Extension Loaded</th>
            <?php if (extension_loaded('iconv')): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">The <a href="http://php.net/iconv">iconv</a> extension is not loaded.</td>
            <?php endif ?>
        </tr>

        <?php if (extension_loaded('mbstring')): ?>
        <tr>
            <th>Mbstring Not Overloaded</th>
            <?php if (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING): $failed = TRUE ?>
                <td class="fail">The <a href="http://php.net/mbstring">mbstring</a> extension is overloading PHP's native string functions.</td>
            <?php else: ?>
                <td class="pass">Pass</td>
            <?php endif ?>
        </tr>
        <?php endif ?>
        <tr>
            <th>URI Determination</th>
            <?php if (isset($_SERVER['REQUEST_URI']) || isset($_SERVER['PHP_SELF']) || isset($_SERVER['PATH_INFO'])): ?>
                <td class="pass">Pass</td>
            <?php else: $failed = TRUE ?>
                <td class="fail">Neither <code>$_SERVER['REQUEST_URI']</code>, <code>$_SERVER['PHP_SELF']</code>, or <code>$_SERVER['PATH_INFO']</code> is available.</td>
            <?php endif ?>
        </tr>
        <tr>
            <th>GD Enabled</th>
            <?php if (function_exists('gd_info')): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">Requires <a href="http://php.net/gd">GD</a> v2 for the Image class.</td>
            <?php endif ?>
        </tr>
        <tr>
            <th>Imagick Enabled</th>
            <?php if (extension_loaded('imagick')): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">Requires <a href="http://php.net/imagick">Imagick</a> for the Image class.</td>
            <?php endif ?>
        </tr>
        <tr>
            <th>PDO Enabled</th>
            <?php if (class_exists('PDO')): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">Requires <a href="http://php.net/pdo">PDO</a> to support databases.</td>
            <?php endif ?>
        </tr>
        <?php if (class_exists('PDO')): ?>
        <tr>
            <th>PDO MySQL Enabled</th>
            <?php if (extension_loaded('pdo_mysql')): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">Requires <a href="http://php.net/pdo">PDO</a> to support databases.</td>
            <?php endif ?>
        </tr>        
        <?php endif ?>
        <tr>
            <th>Zlib</th>
            <?php if (extension_loaded('zlib')): ?>
                <td class="pass">Pass &mdash; zlib.output_compression = <?php echo (int)ini_get('zlib.output_compression'); ?></td>
            <?php else: ?>
                <td class="fail">Requires <a href="http://php.net/zlib">zlib</a> to support compression of output data.</td>
            <?php endif ?>                        
        </tr>
        <tr>
            <th>Gettext</th>
            <?php if (extension_loaded('gettext')): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">Requires <a href="http://php.net/gettext">Gettext</a> to support internationalize.</td>
            <?php endif ?>
        </tr>
        <tr>
            <th>Apache mod_rewrite</th>
            <?php if (!function_exists('apache_get_modules')): ?>
                <td class="fail">n/a (function apache_get_modules not exists)</td>
            <?php elseif (in_array('mod_rewrite', apache_get_modules())): ?>
                <td class="pass">Pass</td>
            <?php else: ?>
                <td class="fail">Requires apache mod_rewrite load.</td>
            <?php endif ?>
        </tr>

        </table>
        <h2>File Uploading Limit</h2>
        <table>
        <tr>
            <th>max_file_uploads</th><td class="pass"><?php echo (int)ini_get('max_file_uploads')?></td><td>Maximum number of files that can be uploaded via a single request.</td>
        </tr>
        <tr class="gray">
            <th>upload_max_filesize</th><td><?php echo (int)ini_get('upload_max_filesize')?></td><td>Maximum allowed size for uploaded files.</td>
        </tr>
        <tr class="gray">
            <th>post_max_size</th><td><?php echo (int)ini_get('post_max_size')?></td><td>Maximum size of POST data that PHP will accept.</td>
        </tr>
        <tr class="gray">
            <th>memory_limit</th><td><?php echo (int)ini_get('memory_limit')?></td><td>Maximum amount of memory a script may consume.</td>
        </tr>
        <tr>
            <th>max upload</th><td class="pass"><?php echo min((int)ini_get('upload_max_filesize'), (int)ini_get('post_max_size'), (int)ini_get('memory_limit'))?></td><td>Max File Size Allowed to Upload.</td>
        </tr>
        </table>   
        
        <h2>Date &amp; Time</h2>
        <table>
        <tr>
            <th>Date</th><td><?php echo date('d F Y H:i:s'); ?></td>
        </tr>
        <tr>
            <th>TimeZone</th><td><?php echo ini_get('date.timezone'); ?></td>
        </tr>
        </table>          
    </div>
</body>
</html>
