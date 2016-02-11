<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Carbon\Carbon;

if (!function_exists('htmlify')) {
    function htmlify($data)
    {
        // replace html links
        $data = preg_replace('/(http[s]?:\/\/[a-zA-Z\.\/\d#-_]+)/', '<a target="_blank" href="$1">$1</a>', $data);

        // replace hashtags
        $data = preg_replace('/([\\n| ])#([a-zA-z-]+)/', '$1<a target="_blank" href="https://twitter.com/search?q=%23$2">#$2</a>', $data);

        return $data;
    }
}

if (!function_exists('secureEmail')) {
    function secureEmail($email, $text = null, $subject = null)
    {
        $secureEmail = '';
        for ($i = 0; $i < strlen($email); ++$i) {
            $secureEmail .= '&#'.ord($email[ $i ]);
        }

        if ($subject) {
            $subject = '?subject='.$subject;
        }

        if ($text) {
            $secureText = '';
            for ($i = 0; $i < strlen($text); ++$i) {
                $secureText .= '&#'.ord($text[ $i ]);
            }

            return '<a target="_new" href="mailto:'.$secureEmail.$subject.'"><i class="fa fa-fw fa-envelope"></i> '.$secureText.'</a>';
        } else {
            return '<a target="_new" href="mailto:'.$secureEmail.$subject.'"><i class="fa fa-fw fa-envelope"></i> '.$secureEmail.'</a>';
        }
    }
}

if (!function_exists('getError')) {
    function getError($field, $type = 'Message')
    {
        if ($errors = Session::get('errors')) {
            if ($errors->has($field)) {
                return ($type == 'class') ? ' has-error' : '<span class="label label-danger">'.$errors->first($field).'</span>';
            }
        }

        return;
    }
}

if (!function_exists('covnertToJpg')) {
    function covnertToJpg($image)
    {
        if (($info = getimagesize($image)) === false) {
            return;
        }

        $sourceData = null;
        switch ($info[ 2 ]) {
            case IMAGETYPE_GIF:
                $sourceData = imagecreatefromgif($image);
                break;
            case IMAGETYPE_PNG:
                $sourceData = imagecreatefromgif($image);
                break;
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                $sourceData = imagecreatefromjpeg($image);
                break;
            default:
                return;
        }

        $tmp = imagecreatetruecolor(250, 250);
        imagecopyresized($tmp, $sourceData, 0, 0, 0, 0, 250, 250, $info[ 0 ], $info[ 1 ]);

        imagejpeg($tmp, $image);

        return $image;
    }
}

if (!function_exists('convertDate')) {
    function convertDate($dateData, $format = 'Y-m-d H:i:s')
    {
        $date = new Carbon($dateData);
        if (get_class($date) == 'Carbon\Carbon') {
            return $date->format($format);
        }

        return;
    }
}

if (!function_exists('facebookLike')) {
    function facebookLike($url)
    {
        $url = urlencode($url);

        return '<iframe src="//www.facebook.com/plugins/like.php?href='.$url.'&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=35&amp;appId=275600865936167" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:35px;" allowTransparency="true"></iframe>';
    }
}

if (!function_exists('displayLeagueBars')) {
    function displayLeagueBars($limits, $counts)
    {
        $output = '<br/>';
        foreach (array('male', 'female', 'total', 'teams') as $type) {
            if ($limits->$type) {
                $max = ($limits->$type >= $counts->$type) ? $limits->$type : $counts->$type;
                $output .= displayBar(ucfirst($type), $counts->$type, $max);
            }
        }

        return $output;
    }
}

if (!function_exists('displayBar')) {
    function displayBar($name, $value, $max)
    {
        $size = number_format(($value / $max) * 100, 2);
        if ($size < 65) {
            $class = 'success';
        } elseif ($size < 85) {
            $class = 'warning';
        } else {
            $class = 'danger';
        }

        return '<strong>'.$name.':</strong> '.($max - $value).' of '.$max.' spots left
        <div class="progress">
            <div class="progress-bar progress-bar-'.$class.'" role="progressbar" aria-valuenow="'.$size.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$size.'%;">
              <span class="sr-only">'.$size.'% Complete</span>
            </div>
        </div>
        ';
    }
}

if (!function_exists('displayHeight')) {
    function displayHeight($value)
    {
        if (is_numeric($value)) {
            return floor($value / 12)."'".' '.($value % 12).'"';
        } else {
            return '??';
        }
    }
}

if (!function_exists('displayExperience')) {
    function displayExperience($year)
    {
        if (is_numeric($year)) {
            if ($year < 1950) {
                return $year.' years';
            } else {
                $years = floor((new DateTime())->format('Y') - $year);
                $text = ($years == 1) ? 'year' : 'years';

                return $years.' '.$text;
            }
        } else {
            return '??';
        }
    }
}

if (!function_exists('displayLevel')) {
    function displayLevel($level)
    {
        return (empty($level)) ? '??' : $level;
    }
}

if (!function_exists('displayAge')) {
    function displayAge($birthday)
    {
        if (empty($birthday) || strtotime($birthday) === false) {
            return '??';
        } else {
            return (new DateTime($birthday))->diff(new DateTime('now'))->y;
        }
    }
}

if (!function_exists('radioOptions')) {
    function radioOptions($name, array $data)
    {
        $options = [];
        if (count($data)) {
            foreach ($data as $key => $value) {
                $value = ($value === '0') ? ' 0 ' : $value;
                $options[$value] = ['name' => $name, 'value' => $key];
            }
        }

        return $options;
    }
}

if (!function_exists('paypalApiContext')) {
    function paypalApiContext()
    {
        $apiContext = new ApiContext(new OAuthTokenCredential(Config::get('cupa.paypal.client_id'), Config::get('cupa.paypal.secret')));

        $apiContext->setConfig(
            array(
                'mode' => (App::environment() == 'prod') ? 'live' : 'sandbox',
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => storage_path().'/PayPal.log',
                'log.LogLevel' => 'DEBUG',
            )
        );

        return $apiContext;
    }
}

if (!function_exists('paypalAuth')) {
    function paypalAuth()
    {
        $host = (App::environment() == 'prod') ? 'https://api.paypal.com' : 'https://api.sandbox.paypal.com';

        $result = json_decode(shell_exec('curl '.$host.'/v1/oauth2/token -H "Accept: application/json" -H "Accept-Language: en_US" -u "'.Config::get('cupa.paypal.client_id').':'.Config::get('cupa.paypal.secret').'" -d "grant_type=client_credentials"'));

        return $result->access_token;
    }
}

if (!function_exists('paypalExecutePayment')) {
    function paypalExecutePayment($paymentId, $payerId)
    {
        $host = (App::environment() == 'prod') ? 'https://api.paypal.com' : 'https://api.sandbox.paypal.com';

        $accessToken = paypalAuth();
        $result = json_decode(shell_exec("curl {$host}/v1/payments/payment/{$paymentId}/execute -H \"Content-Type:application/json\" -H \"Authorization: Bearer {$accessToken}\" -d '{ \"payer_id\" : \"{$payerId}\" }'"));

        return $result;
    }
}

if (!function_exists('getSelect2Data')) {
    function getSelect2Data($model, $id, $text = null)
    {
        if (is_array($id) && !empty($id[0])) {
            $initial = '[{';
            foreach ($id as $cnt => $i) {
                if ($cnt > 0) {
                    $initial .= ',{ ';
                }

                //var_dump($i);
                $data = $model::find($i);
                //var_dump($data);

                if ($model == 'User') {
                    $text = $data->fullname();
                } else {
                    $text = $data->$text;
                }

                $initial .= 'id: '.$i.', text: \''.$text.'\' }';
            }

            $initial .= ']';
        } elseif (is_numeric($id)) {
            $data = $model::find($id);
            if ($model == 'User') {
                $text = $data->fullname();
            } else {
                $text = $data->$text;
            }

            $initial = '{ id: '.$id.', text: \''.$text.'\'}';
        } else {
            $initial = '{}';
        }

        return $initial;
    }
}

if (!function_exists('fetchBanner')) {
    function fetchBanner($route)
    {
        if (starts_with($route, 'yuc_')) {
            $base = 'youth';
        } else {
            $base = (strpos($route, '_') === false) ? $route : substr($route, 0, strpos($route, '_'));
        }

        return (file_exists(public_path().'/data/banners/'.$base.'.jpg')) ? asset('/data/banners/'.$base.'.jpg') : asset('img/1200x300.gif');
    }
}

if (!function_exists('displayFilesize')) {
    function displayFilesize($bytes)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;

        return number_format($bytes / pow(1024, $power), 2, '.', ',').' '.$units[$power];
    }
}
