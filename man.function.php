<?php
/**
 * MeaWords framework
 * https://www.meayair.com
 * https://github.com/Meayair/MeaWords
 * Version 1.0.1
 *
 * Copyright 2019, Meayair <meayair@gmail.com>
 * Released under the MIT license
 */

namespace Meayair;

class MeaWords
{
    const VERSION = '1.0.1';
    public $words_json_path = "./data/words.json";

    /**
     * 安全读取文件，避免并发下读取数据为空
     *
     * @param $file 要读取的文件路径
     * @param $timeout 读取超时时间
     * @return 读取到的文件内容 | false - 读取失败
     */
    public function file_read_safe($file, $timeout = 5) {
        if (!$file || !file_exists($file)) return false;
        $fp = @fopen($file, 'r');
        if (!$fp) return false;
        $startTime = microtime(true);

        // 在指定时间内完成对文件的独占锁定
        do {
            $locked = flock($fp, LOCK_EX | LOCK_NB);
            if (!$locked) {
                usleep(mt_rand(1, 50) * 1000);
                // 随机等待1~50ms再试
            }
        }
        while ((!$locked) && ((microtime(true) - $startTime) < $timeout));

        if ($locked && filesize($file) >= 0) {
            $result = @fread($fp, filesize($file));
            flock($fp, LOCK_UN);
            fclose($fp);
            if (filesize($file) == 0) {
                return '';
            }
            return $result;
        } else {
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }
    }

    /**
     * 安全写文件，避免并发下写入数据为空
     *
     * @param $file 要写入的文件路径
     * @param $buffer 要写入的文件二进制流（文件内容）
     * @param $timeout 写入超时时间
     * @return 写入的字符数 | false - 写入失败
     */
    public function file_write_safe($file, $buffer, $timeout = 5) {
        clearstatcache();
        if (strlen($file) == 0 || !$file) return false;

        // 文件不存在则创建
        if (!file_exists($file)) {
            @file_put_contents($file, '');
        }
        if (!is_writeable($file)) return false;
        // 不可写

        // 在指定时间内完成对文件的独占锁定
        $fp = fopen($file, 'r+');
        $startTime = microtime(true);
        do {
            $locked = flock($fp, LOCK_EX);
            if (!$locked) {
                usleep(mt_rand(1, 50) * 1000);
                // 随机等待1~50ms再试
            }
        }
        while ((!$locked) && ((microtime(true) - $startTime) < $timeout));

        if ($locked) {
            $tempFile = $file.'.temp';
            $result = file_put_contents($tempFile, $buffer, LOCK_EX);

            if (!$result || !file_exists($tempFile)) {
                flock($fp, LOCK_UN);
                fclose($fp);
                return false;
            }
            @unlink($tempFile);

            ftruncate($fp, 0);
            rewind($fp);
            $result = fwrite($fp, $buffer);
            flock($fp, LOCK_UN);
            fclose($fp);
            clearstatcache();
            return $result;
        } else {
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }
    }

    /**
     * 句子读取写入，修改，删除
     *
     * 
     */
    public function word_new($wid,$source,$word,$words_path) {
        $words_arr = json_decode($this->file_read_safe($words_path),true);
        $words_arr[$wid] = array("source"=>$source,"word"=>$word);
        $this->file_write_safe($words_path,json_encode($words_arr));
    }
    public function word_delete($wid,$words_path) {
        $words_arr = json_decode($this->file_read_safe($words_path),true);
        if (array_key_exists($wid,$words_arr)) {
            unset ($words_arr[$wid]);
            $this->file_write_safe($words_path,json_encode($words_arr));
        }
    }
    public function word_get($wid,$words_path) {
        $words_arr = json_decode($this->file_read_safe($words_path),true);
        if (array_key_exists($wid,$words_arr)) {
            return $words_arr;
        }
    }
    
    /**
     * 获取句子列表
     *
     * 
     */
    public function word_list_get($page,$words_path,$num = 10){
        $words_arr = json_decode($this->file_read_safe($words_path),true);
        $outputs = array();
        if(!empty($words_arr)){
        $keys = array_keys($words_arr);
        rsort($keys);
        for($i=($page-1)*$num;$i<$page*$num&&$i<count($keys);$i++){
            $outputs[$keys[$i]] = $words_arr[$keys[$i]];
        }}
        return $outputs;
    }
    
    public function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length):
        substr(md5(microtime()), -$ckey_length)) : '';
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
    sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() == 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}
    
    public function login_check($row) {
    if (($_POST['username'] != null) && ($_POST['password'] != null)) {
        $userName = $_POST['username'];
        $password = $_POST['password'];
        if ($row['password'] == md5($password) && $row['username'] == md5($userName)) {
            $meass = base64_encode(serialize($row));
            $meass = $this->authcode($meass,'ENCODE','www.meayair.com',0); //加密
            setcookie("meass",$meass,time()+3600*24);
            return true;
        }
        return false;
    }

    //再次访问的时候通过cookie来识别用户
    elseif ($_COOKIE['meass'] != null) {
        $meass = $_COOKIE['meass'];
        $meass = $this->authcode($meass ,'DECODE','www.meayair.com',0);

        if ( base64_encode(serialize($row)) == $meass) {
            return true;
        }
        return false;
    }
    else return false;
}
    

}

