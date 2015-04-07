<?php
require_once(dirname(__FILE__).'/../config.php');
class dateTimeProvider
{
    public static function getCurrentDateTime(){
        date_default_timezone_set('America/Chicago');
        if (false===isset($_SESSION['test_tick'])){
            $_SESSION['test_tick'] = 0;
        }
        if (isset($_SESSION['test_timestamp'])){
            return getdate($_SESSION['test_timestamp'] + $_SESSION['test_tick']);
        }

        return getdate();
    }

    public static function getdate($str){
        date_default_timezone_set('America/Chicago');
        return getdate(strtotime($str));
    }

    /*
     * dateTime must be in format mm/dd/YYYY hh:mm
     */
    public static function setTestDateTime($dateTime){
        date_default_timezone_set('America/Chicago');
        $timestamp = strtotime($dateTime);
        $_SESSION['test_timestamp'] = $timestamp;
    }

    public static function testTimeTick($millis=1){
        if (!isset($_SESSION['test_tick'])){
            $_SESSION['test_tick'] = 0;
        }
        $_SESSION['test_tick'] += $millis;
    }

    public static function unsetTestDateTime(){
        unset($_SESSION['test_timestamp']);
    }

    public function getDateTimeFromStamp($timestamp){
        date_default_timezone_set('America/Chicago');
        return getdate($timestamp);
    }

    public static function readableTime($time = null){
        if ($time === null){
            $time = self::getCurrentDateTime();
        }

        $hrs = $time['hours'];
        $ap = 'am';
        if ($hrs > 12){
            $hrs -= 12;
            $ap = 'pm';
        }

        return sprintf('%02d:%02d:%02d %s', $hrs, $time['minutes'], $time['seconds'], $ap);
    }

    /*
     * Determines the minutes from midnight of a given time
     * $time string of format '2:30 am'
     *
     * returns minutes-from-midnight
     */
    public static function minutesFromMidnight($time){
        if(!isset($time) || $time==='' || $time==null) {
            return -1;
        }
        $hmap = preg_split("/[\s:]+/", $time);
        $hmap[0] %= 12;
        if ($hmap[2] === 'pm'){
            $hmap[0] += 12;
        }
        return $hmap[1] + $hmap[0] * 60;
    }

    public static function readableTimeOfDay($minutesFromMidnight){
        $readable = sprintf("%02d", $minutesFromMidnight / 60);
        $readable .= ':'.sprintf("%02d", $minutesFromMidnight % 60);
        return $readable;
    }
}
session_start();
if (isset(Config::$config['test_time'])){
    dateTimeProvider::setTestDateTime(Config::$config['test_time']);
}