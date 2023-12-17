<?php

namespace App\Utils;

class Util
{
    const FORMAT_DATE_ID_SHORT = 1;

    const FORMAT_DATE_ID_LONG = 2;

    const FORMAT_DATE_ID_LONG_DAY = 3;

    const FORMAT_DATE_EN_SHORT = 4;

    const FORMAT_DATETIME_ID_LONG_DAY = 5;

    const FORMAT_DATETIME_ID_LONG = 6;

    const FORMAT_DATE_EN_SHORTEST = 7;

    const FORMAT_DATE_ID_SHORT_SLASH = 8;

    const FORMAT_DATE_EN_SHORT_SLASH = 9;

    public $FORMAT_DATE_ID_SHORT = self::FORMAT_DATE_ID_SHORT;

    public $FORMAT_DATE_ID_LONG = self::FORMAT_DATE_ID_LONG;

    public $FORMAT_DATE_ID_LONG_DAY = self::FORMAT_DATE_ID_LONG_DAY;

    public $FORMAT_DATE_EN_SHORT = self::FORMAT_DATE_EN_SHORT;

    public $FORMAT_DATETIME_ID_LONG_DAY = self::FORMAT_DATETIME_ID_LONG_DAY;

    public $FORMAT_DATE_EN_SHORTEST = self::FORMAT_DATE_EN_SHORTEST;

    public $FORMAT_DATETIME_ID_LONG = self::FORMAT_DATETIME_ID_LONG;

    /*
    |--------------------------------------------------------------------------
    | DEBUGING
    |--------------------------------------------------------------------------
    |@params $array : array / object data
    |@params $die : boolean
    */
    public static function echoPre($array, $die = false)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';

        if ($die) {
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER GET
    |--------------------------------------------------------------------------
    |@params $key : string
    */
    public static function get($key)
    {
        $req = \Request::get($key);

        $req = strip_tags($req);

        $req = trim($req);

        return $req;
    }

    /*
    |--------------------------------------------------------------------------
    | Build Row Structured array key and string value
    |--------------------------------------------------------------------------
    |
    | parameter row, primary, and field as string output and conditions
    | note primary string or array, $name string or *
    | example string : id => value
    | 1 => Administrator
    | 2 => User
    |
    | example array : [id] => value
    | 0=>[
    |     1 => Administrator
    |     2 => User
    |    ]
    | filled $subKey to make field as key of sub array
    */
    public static function getRowArray($row, $primary, $name, $subKey = '')
    {
        $ret = [];

        if (! empty($row)) {
            if (is_array($primary)) {
                if ($subKey) {
                    foreach ($row as $v) {
                        if (is_array($v[$primary[0]])) {
                            foreach ($v[$primary[0]] as $vv) {
                                $ret[$vv[$primary[0]]][$vv[$subKey]] = ($name == '*' ? $v : $v[$name]);
                            }
                        } else {
                            $ret[$v[$primary[0]]][$v[$subKey]] = ($name == '*' ? $v : $v[$name]);
                        }
                    }
                } else {
                    foreach ($row as $v) {
                        if (is_array($v[$primary[0]])) {
                            foreach ($v[$primary[0]] as $vv) {
                                $ret[$vv][] = ($name == '*' ? $v : $v[$name]);
                            }
                        } else {
                            $ret[$v[$primary[0]]][] = ($name == '*' ? $v : $v[$name]);
                        }
                    }
                }
            } else {
                foreach ($row as $v) {
                    if (is_array($v[$primary])) {
                        foreach ($v[$primary] as $vv) {
                            $ret[$vv] = ($name == '*' ? $v : $v[$name]);
                        }
                    } else {
                        $ret[$v[$primary]] = ($name == '*' ? $v : $v[$name]);
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * nl2p change text with \r\n to paragraph
     *
     * @param  string  $string Text with \r\n change to <p>
     * @param  bool  $line_breaks Flag for 1 paragraph only
     * @param  bool  $xml if string xml
     * @return string
     */
    public static function nl2p($string, $line_breaks = false, $xml = false)
    {
        $string = str_replace(['<p>', '</p>', '<br>', '<br />'], '', $string);
        if ($line_breaks == true) {
            $string = '<p>'.preg_replace(["/([\n]{2,})/i", "/([^>])\n([^<])/i"], ["</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'], trim($string)).'</p>';
        } else {
            $string = '<p>'.preg_replace(
                ["/([\n]{2,})/i", "/([\r\n]{3,})/i", "/([^>])\n([^<])/i"],
                ["</p>\n<p>", "</p>\n<p>", '$1<br'.($xml == true ? ' /' : '').'>$2'],
                trim($string)
            ).'</p>';
        }

        return $string;
    }

    public static function convertToHoursMins($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return;
        }
        $result['hours'] = floor($time / 60);
        $result['minutes'] = ($time % 60);

        return sprintf($format, $result['hours'], $result['minutes']);
    }

    /**
     * Calculate difference 2 date, with result years, months, days, hours, minutes, seconds
     *
     * @param  string  $date1 Date 1
     * @param  string  $date2 Date 2
     * @return string
     */
    public static function diff_date($date1, $date2 = null)
    {
        if (is_null($date2)) {
            $date2 = date('Y-m-d H:i:s');
        }

        $date1 = date_create($date1);
        $date2 = date_create($date2);
        $diff = date_diff($date1, $date2);

        $result = [
            'year' => (int) $diff->format('%R%y'),
            'month' => (int) $diff->format('%R%m'),
            'day' => (int) $diff->format('%R%d'),
            'hour' => (int) $diff->format('%R%h'),
            'minute' => (int) $diff->format('%R%i'),
            'second' => (int) $diff->format('%R%s'),
            'minus' => false,
        ];

        if ($result['year'] < 0 || $result['year'] < 0 || $result['month'] < 0 || $result['day'] < 0
            || $result['hour'] < 0 || $result['minute'] < 0 || $result['second'] < 0) {
            $result['minus'] = true;
        }

        return $result;
    }

    /**
     * Format number to currency IDR
     *
     * @param  int  $number Number for format
     * @param  int  $decimal Count number after comma
     * @param  string  $prefix Prefix for format number ex: Rp.
     * @return string
     */
    public static function format_currency($number, $decimal = 0, $prefix = '')
    {
        $number_abs = $number;
        if ($number < 0) {
            $number_abs = abs($number);
        }
        $number_format = $prefix.number_format($number_abs, $decimal, ',', '.');

        if ($number < 0) {
            $number_format = '-'.$number_format;
        }

        return $number_format;
    }

    /**
     * Get list name models
     *
     * @return array
     */
    public static function get_models()
    {
        $path = app_path();
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') {
                continue;
            }
            $filename = $path.'/'.$result;
            if (! is_dir($filename)) {
                $filename = substr($filename, 0, -4);
                $filename = str_replace($path.'/', '', $filename);
                $out[$filename] = $filename;
            }
        }

        return $out;
    }

    /**
     * Function for detected mobile or desktop
     *
     * @return array
     */
    public static function isMobileDevice()
    {
        $aMobileUA = [
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile',
        ];

        //Return true if Mobile User Agent is detected
        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
            if (preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])) {
                return true;
            }
        }

        //Otherwise return false..
        return false;
    }

    public static function hari($hari = false, $lang = 'id')
    {
        $tmp = trans('global.array.days', [], $lang);

        return $hari ? $tmp[$hari] : $tmp;
    }

    public static function bulan($bulan = false, $lang = 'id')
    {
        $tmp = trans('global.array.months', [], $lang);

        return $bulan ? $tmp[$bulan] : $tmp;
    }

    public static function formatDate($date, $format = self::FORMAT_DATETIME_ID_LONG_DAY)
    {
        if ($date == null) {
            return null;
        }

        if ($date == '0000-00-00 00:00:00') {
            return null;
        }

        switch ($format) {
            case self::FORMAT_DATE_ID_SHORT:
                return date('d-m-Y', strtotime($date));
            case self::FORMAT_DATE_ID_SHORT_SLASH:
                return date('d/m/Y', strtotime($date));
            case self::FORMAT_DATETIME_ID_LONG_DAY:
            case self::FORMAT_DATETIME_ID_LONG:
            case self::FORMAT_DATE_ID_LONG_DAY:
            case self::FORMAT_DATE_ID_LONG:
                $res = [];
                preg_match('/(\d)\-(\d+)\-(\d+)\-(\d{4})\-(\d+)\-(\d+)\-(\d+)/', date('N-d-n-Y-H-i-s', strtotime($date)), $res);
                if ($format == self::FORMAT_DATE_ID_LONG) {
                    return sprintf('%d %s %s', $res[2], self::bulan($res[3]), $res[4]);
                } elseif ($format == self::FORMAT_DATE_ID_LONG_DAY) {
                    return sprintf('%s, %d %s %s', self::hari($res[1]), $res[2], self::bulan($res[3]), $res[4]);
                } elseif ($format == self::FORMAT_DATETIME_ID_LONG_DAY) {
                    return sprintf('%s, %d %s %s. %s:%s', self::hari($res[1]), $res[2], self::bulan($res[3]), $res[4], $res[5], $res[6]);
                } elseif ($format == self::FORMAT_DATETIME_ID_LONG) {
                    return sprintf('%d %s %s %s:%s', $res[2], self::bulan($res[3]), $res[4], $res[5], $res[6]);
                }
                break;
            case self::FORMAT_DATE_EN_SHORT:
                return date('Y-m-d', strtotime($date));
            case self::FORMAT_DATE_EN_SHORT_SLASH:
                return date('Y/m/d', strtotime($date));
            case self::FORMAT_DATE_EN_SHORTEST:
                return date('ymd', strtotime($date));
            default:
                return date($format, strtotime($date));
        }
    }

    public static function remove_format_currency($number)
    {
        $number = empty($number) ? 0 : $number;

        return preg_replace('/[^0-9.]/', '', $number);
    }

    private static function _private_terbilang_read($x)
    {
        $abil = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
        if ($x < 1000000000) {
            $x += 0;
        }
        if ($x < 12) {
            return ' '.$abil[$x];
        } elseif ($x < 20) {
            return self::_private_terbilang_read($x - 10).' belas';
        } elseif ($x < 100) {
            return self::_private_terbilang_read($x / 10).' puluh'.self::_private_terbilang_read($x % 10);
        } elseif ($x < 200) {
            return ' seratus'.self::_private_terbilang_read($x - 100);
        } elseif ($x < 1000) {
            return self::_private_terbilang_read($x / 100).' ratus'.self::_private_terbilang_read($x % 100);
        } elseif ($x < 2000) {
            return ' seribu'.self::_private_terbilang_read($x - 1000);
        } elseif ($x < 1000000) {
            return self::_private_terbilang_read($x / 1000).' ribu'.self::_private_terbilang_read($x % 1000);
        } elseif ($x < 1000000000) {
            return self::_private_terbilang_read($x / 1000000).' juta'.self::_private_terbilang_read($x % 1000000);
        } elseif (strlen($x) < 13) {
            return self::_private_terbilang_read(substr($x, 0, -9)).' milyar'.self::_private_terbilang_read(substr($x, strlen($x) - 9));
        } elseif (strlen($x) < 16) {
            return self::_private_terbilang_read(substr($x, 0, -12)).' triliun'.self::_private_terbilang_read(substr($x, strlen($x) - 12));
        } else {
            return '';
        }
    }

    public static function terbilang($x)
    {
        $x .= '';
        $x = self::_private_terbilang_read($x);

        return trim(ucwords(strtolower($x)));
    }

    /**
     * @param  int  $number
     * @return string
     */
    public static function numberToRoman($number)
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }

        return $returnValue;
    }

    public static function time_elapsed_string($datetime, $full = false)
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = [
            'y' => trans('notification.label.year'),
            'm' => trans('notification.label.month'),
            'w' => trans('notification.label.week'),
            'd' => trans('notification.label.day'),
            'h' => trans('notification.label.hour'),
            'i' => trans('notification.label.minute'),
            's' => trans('notification.label.second'),
        ];
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k.' '.$v.($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (! $full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string).' ago' : 'just now';
    }

    public static function roundedThousand($value)
    {
        $value = ceil($value);
        if (substr($value, -3) > 499) {
            $value = round($value, -3);
        } else {
            $value = round($value, -3) + 1000;
        }

        return $value;
    }
}
