<?php

function d($data)
{
    if (is_null($data)) {
        $str = "<i>NULL</i>";
    } elseif ($data == "") {
        $str = "<i>Empty</i>";
    } elseif (is_array($data)) {
        if (count($data) == 0) {
            $str = "<i>Empty array.</i>";
        } else {
            $str = "<table style=\"border-bottom:0px solid #000;\" cellpadding=\"0\" cellspacing=\"0\">";
            $str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">Total records</td><td style=\"border:1px solid #000;\">" . count($data) . "</td></tr>";
            foreach ($data as $key => $value) {
                $str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">" . $key . "</td><td style=\"border:1px solid #000;\">" . d($value) . "</td></tr>";
            }
            $str .= "</table>";
        }
    } elseif (is_resource($data)) {
        while ($arr = mysql_fetch_array($data)) {
            $data_array[] = $arr;
        }
        $str = d($data_array);
    } elseif (is_object($data)) {
        $str = d(get_object_vars($data));
    } elseif (is_bool($data)) {
        $str = "<i>" . ($data ? "True" : "False") . "</i>";
    } else {
        $str = $data;
        $str = preg_replace("/\n/", "<br>\n", $str);
    }
    return $str;
}

function dnl($data)
{
    echo d($data) . "<br>\n";
    die;
}

function dd($data)
{
    echo dnl($data);
    die;
}

function ddt($message = "")
{
    echo "[" . date("Y/m/d H:i:s") . "]" . $message . "<br>\n";
    die;
}
function altTrCol($key)
{
    if (fmod($key, 2) == 0) {
        return 'bg-info';
    } else {
        return 'bg-primary';
    }
}
function altTdCol($key)
{
    if (fmod($key, 2) == 0) {
        return 'bg-danger';
    } else {
        return 'bg-warning';
    }
}
function altTdCol2($key)
{
    if (fmod($key, 2) == 0) {
        return 'bg-success';
    } else {
        return 'bg-secondary';
    }
}
function getRelationName($code)
{
    switch ($code) {
        case 'f':
            $relation = 'পিতৃ';
            break;
        case 'm':
            $relation = 'মাতৃ';
            break;
        case 'h':
            $relation = 'পতি';
            break;
        case 'w':
            $relation = 'পত্নী';
            break;
        case 'a':
            $relation = 'অধ্যক্ষ মাতা';
            break;
        case '':
            $relation = 'অভিভাৱক';
            break;
        case 'n':
            $relation = 'নাই';
            break;
        default:
            $relation = '';
            break;
    }
    return $relation;
}
