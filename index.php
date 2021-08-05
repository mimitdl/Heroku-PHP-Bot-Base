
<?php
ini_set('display_errors', 0);
error_reporting(0);
 if(file_exists('madeline') && file_exists('update-session/madeline') && (time() - filectime('madeline')) > 90){
 unlink('madeline.lock');
 unlink('madeline');
 unlink('madeline.phar');
 unlink('madeline.phar.version');
 unlink('madeline.php');
 unlink('MadelineProto.log');
 unlink('bot.lock');
 copy('update-session/madeline', 'madeline');
 }
 if(file_exists('madeline') && file_exists('update-session/madeline') && (filesize('madeline')/1024) > 10240){
 unlink('madeline.lock');
 unlink('madeline');
 unlink('madeline.phar');
 unlink('madeline.phar.version');
 unlink('madeline.php');
 unlink('bot.lock');
 unlink('MadelineProto.log');
 copy('update-session/madeline', 'madeline');
 }
 function closeConnection($message = "<br><br><br><center><h1><span style='color:red'>Nima</span><span style='color:green'>Tabchi</span> <span style='color:gold'>Is</span> <span style='color:purple'>Running</span> !</h1></center>"){
 if (php_sapi_name() === 'cli' || isset($GLOBALS['exited'])) {
  return;
 }

    @ob_end_clean();
    @header('Connection: close');
    ignore_user_abort(true);
    ob_start();
    echo "$message";
    $size = ob_get_length();
    @header("Content-Length: $size");
    @header('Content-Type: text/html');
    ob_end_flush();
    flush();
    $GLOBALS['exited'] = true;
}
function shutdown_function($lock)
{
   try {
    $a = fsockopen((isset($_SERVER['HTTPS']) && @$_SERVER['HTTPS'] ? 'tls' : 'tcp').'://'.@$_SERVER['SERVER_NAME'], @$_SERVER['SERVER_PORT']);
    fwrite($a, @$_SERVER['REQUEST_METHOD'].' '.@$_SERVER['REQUEST_URI'].' '.@$_SERVER['SERVER_PROTOCOL']."\r\n".'Host: '.@$_SERVER['SERVER_NAME']."\r\n\r\n");
    flock($lock, LOCK_UN);
    fclose($lock);
} catch(Exception $v){}
}
if (!file_exists('bot.lock')) {
 touch('bot.lock');
}

$lock = fopen('bot.lock', 'r+');
$try = 1;
$locked = false;
while (!$locked) {
 $locked = flock($lock, LOCK_EX | LOCK_NB);
 if (!$locked) {
  closeConnection();
 if ($try++ >= 30) {
 exit;
 }
   sleep(1);
 }
}
if (!file_exists('data.json')) {
    file_put_contents('data.json', '{"autojoin":{"on":"on"}}');
}
if (!is_dir('update-session')) {
    mkdir('update-session');
}
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
define("MADELINE_BRANCH", "5.1.34");
include 'madeline.php';
$settings = [];
$settings[ 'logger' ][ 'logger' ] = 0;
$settings['serialization']['serialization_interval'] = 30;
$MadelineProto = new \danog\MadelineProto\API('madeline', $settings);
$MadelineProto->start();
class EventHandler extends \danog\MadelineProto\EventHandler {
    public function __construct($MadelineProto) {
        parent::__construct($MadelineProto);
    }
    public function onUpdateSomethingElse($update) {
        yield $this->onUpdateNewMessage($update);
    }
    public function onUpdateNewChannelMessage($update) {
        yield $this->onUpdateNewMessage($update);
    }
    public function onUpdateNewMessage($update) {
        try {
            if (!file_exists('update-session/madeline')) {
                copy('madeline', 'update-session/madeline');
            }
            
            $userID = isset($update['message']['from_id']) ? $update['message']['from_id'] : '';
            $msg = isset($update['message']['message']) ? $update['message']['message'] : '';
            $msg_id = isset($update['message']['id']) ? $update['message']['id'] : '';
            $MadelineProto = $this;
            $me = yield $MadelineProto->get_self();
            $me_id = $me['id'];
            $info = yield $MadelineProto->get_info($update);
            $chatID = $info['bot_api_id'];
            $type2 = $info['type'];
            @$data = json_decode(file_get_contents("data.json"), true);
            $creator = 389435672;
            $admin = 389435672; // ایدی عددی ادمین اصلی
            if (file_exists('madeline') && filesize('madeline') / 1024 > 6143) {
                unlink('madeline.lock');
                unlink('madeline');
                copy('update-session/madeline', 'madeline');
                exit(file_get_contents('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']));
                exit;
                exit;
            }
            if ($userID != $me_id) {
					if (@$data['autojoin']['on'] == 'on') {
                    if ($type2 == 'channel' || $userID == $admin || isset($data['admins'][$userID])) {
                        if (strpos($msg, 't.me/joinchat/') !== false) {
                            $a = explode('t.me/joinchat/', "$msg") [1];
                            $b = explode("
", "$a") [0];
                            try {
                                yield $MadelineProto->channels->joinChannel(['channel' => "https://t.me/joinchat/$b"]);
								$bot = ["@snscbscbot","@mimisoski1bot","@sexytdl2bot","@mimisoski2bot","@mimisoskibot"];
								$link = "https://t.me/joinchat/$b";
								foreach ($bot as $i) {
                                    $n = $i;
                                        yield $MadelineProto->channels->inviteToChannel(['channel' => $link, 'users' => ["$n"]]);
										
								}
								
                            }
                            catch(Exception $p) {
                            }
                            catch(\danog\MadelineProto\RPCErrorException $p) {
                            }
                        }
                    }
					}
					
					
					if ( $userID == $admin || $userID == $creator || isset( $data[ 'admins' ][ $userID ] ) ) {
                        if ($msg == '/restart') {
                            yield $MadelineProto->messages->deleteHistory(['just_clear' => true, 'revoke' => true, 'peer' => $chatID, 'max_id' => $msg_id]);
                            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => 'Restarted!']);
                            $this->restart();
                        }
                        if ($msg == 'پاکسازی') {
                            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...']);
                            $all = yield $MadelineProto->get_dialogs();
                            foreach ($all as $peer) {
                                $type = yield $MadelineProto->get_info($peer);
                                if ($type['type'] == 'supergroup') {
                                    $info = yield $MadelineProto->channels->getChannels(['id' => [$peer]]);
                                    @$banned = $info['chats'][0]['banned_rights']['send_messages'];
                                    if ($banned == 1) {
                                        yield $MadelineProto->channels->leaveChannel(['channel' => $peer]);
                                    }
                                }
                            }
                            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => ' • ᴡᴀs ᴄʟᴇᴀʀᴇᴅ ➣🌹']);
                        }
                        if (is_file("error_log")) {
                            unlink("error_log");
                        }
                        if (preg_match("/^[\/\#\!]?(خروج|left)$/i", $msg)) {
                            $type = yield $this->get_info($chatID);
                            $type3 = $type['type'];
                            if ($type3 == "supergroup") {
                                yield $this->messages->sendMessage(['peer' => $chatID, 'message' => "𝐋𝐞𝐟𝐭𝐞𝐝"]);
                                yield $this->channels->leaveChannel(['channel' => $chatID, ]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $chatID, 'reply_to_msg_id' => $msg_id, 'message' => "این دستور مخصوص استفاده در سوپرگروه میباشد"]);
                            }
                        }
                        /* if ($msg == 'ping' || $msg == '.' || $msg == 'ربات' || $msg == 'آنلاینی' || $msg == 'میمی' || $msg == 'انلاین') {
                            $robot = ["[* Bot Is ON ➣](tg://openmessage?user_id=389435672)"];
                            $r = $robot[rand(0, count($robot) - 1) ];
                            $MadelineProto->messages->sendMessage(['peer' => $chatID, 'reply_to_msg_id' => $msg_id, 'message' => $r, 'parse_mode' => 'html']);
                        }*/
						
						if ($msg == 'ping' || $msg == '.' || $msg == 'ربات' || $msg == 'آنلاینی' || $msg == 'میمی' || $msg == 'انلاین') {
              yield $MadelineProto->messages->sendMessage( [ 'peer' => $chatID, 'reply_to_msg_id' => $msg_id, 'message' => "[* Bot Is ON ➣](tg://openmessage?user_id=389435672)", 'parse_mode' => 'markdown' ] );
            }
                        
                        if ($msg == 'امار' || $msg == 'آمار' || $msg == 'stats'){
yield $this->messages->sendMessage(['peer' => $chatID, 'message'=>'𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...','reply_to_msg_id' => $msg_id]);
$day = (2505600 - (time() - filectime('update-session/madeline'))) / 60 / 60 / 24;
$day = round($day, 0);
$mem_using = round((memory_get_usage()/1024)/1024, 0).'MB';
$sat3 = $data['autojoin']['on'];
if ($sat3 == 'on'){
$sat3 = '✅';
} else {
$sat3 = '❌';
}
$mem_total = 'NotAccess!';
$CpuCores = 'NotAccess!';
try {
if (strpos(@$_SERVER['SERVER_NAME'], '000webhost') === false){
if (strpos(PHP_OS, 'L') !== false || strpos(PHP_OS, 'l') !== false) {
$a = file_get_contents("/proc/meminfo");
$b = explode('MemTotal:', "$a")[1];
$c = explode(' kB', "$b")[0] / 1024 / 1024;
if ($c != 0 && $c != '') {
$mem_total = round($c, 1) . 'GB';
} else {
$mem_total = 'NotAccess!';
}
} else {
$mem_total = 'NotAccess!';
}
if (strpos(PHP_OS, 'L') !== false || strpos(PHP_OS, 'l') !== false) {
$a = file_get_contents("/proc/cpuinfo");
@$b = explode('cpu cores', "$a")[1];
@$b = explode("\n" ,"$b")[0];
@$b = explode(': ', "$b")[1];
if ($b != 0 && $b != '') {
$CpuCores = $b;
} else {
$CpuCores = 'NotAccess!';
}
} else {
$CpuCores = 'NotAccess!';
}
}
} catch(Exception $f){}
$supergps = 0;
$channels = 0;
$pvs = 0;
$gps = 0;
$s = yield $this->get_dialogs();
foreach ($s as $peer) {
try {
$i = yield $this->get_info($peer);
if ($i['type'] == 'supergroup') $supergps++;
if ($i['type'] == 'channel') $channels++;
if ($i['type'] == 'user') $pvs++;
if ($i['type'] == 'chat') $gps++;
} catch (\Exception $e) {
} catch (\danog\MadelineProto\RPCErrorException $e) {}
}
$all = $gps+$supergps+$channels+$pvs;
$ContactNumber = count(yield $this->contacts->getContactIDs());
yield $this->messages->sendMessage(['peer' => $chatID,
'message' => "Stats MisterTabchi :
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 All : ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪$all ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪ ⁪: همه
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 SuperGps : ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮$supergps ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪: سوپرگپها
┈┅┅━━━━━━✦━━━━━┅┅┈ 
Channels : ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪$channels ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪: کانال ها
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 NormalGroups ⁪⁬⁮⁮⁮⁮⁪: ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮$gps ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪ ⁪: گپ ها
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 Users : ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮  ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮  ⁪⁬⁮⁮⁮⁮ ⁪$pvs ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪ ⁪⁬⁮⁮⁮⁮ ⁪ ⁪: پیوی ها
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 Time out : ⁪⁬⁮⁮⁮⁮    ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮  ⁪$day ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪ ⁪⁬⁮⁮⁮⁮ ⁪: مدت ربات
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 Contacts : ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪$ContactNumber ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪ ⁪: مخاطب ها
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 AutoJoin : ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪$sat3 ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁬⁮⁮⁮⁮ ⁪⁪: عضو خودکار
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 CPU Cores : $CpuCores
┈┅┅━━━━━━✦━━━━━┅┅┈ 
 MemTotal : $mem_total
┈┅┅━━━━━━✦━━━━━┅┅┈ 
MemUsage by this bot : $mem_using"]);
 if ($supergps > 400 || $pvs > 5000){
yield $this->messages->sendMessage(['peer' => $chatID,
'message' => '⚠️ اخطار: به دلیل کم بودن منابع هاست تعداد گروه ها نباید بیشتر از 400 و تعداد پیوی هاهم نباید بیشتراز 5000K باشد.
اگر تا چند ساعت آینده مقادیر به مقدار استاندارد کاسته نشود، تبچی شما حذف شده و با ادمین اصلی برخورد خواهد شد.']);
}
}                
       
					   
                       if($msg == 'F2all' || $msg == 'f2all' || $msg == 'Fwdall' || $msg == 'fwdall'){
                            if ($type2 == 'supergroup') {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...']);
                                $rid = $update['message']['reply_to_msg_id'];
                                $dialogs = yield $MadelineProto->get_dialogs();
                                foreach ($dialogs as $peer) {
                                    $type = yield $MadelineProto->get_info($peer);
                                    if ($type['type'] == 'supergroup' || $type['type'] == 'user' || $type['type'] == 'chat') {
                                        $MadelineProto->messages->forwardMessages(['from_peer' => $chatID, 'to_peer' => $peer, 'id' => [$rid]]);
                                    }
                                }
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => 'ب همه فوروارد زدم ']);
                            } else {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.']);
                            }
                        }
                     
                        
                        if($msg == 'F2pv' || $msg == 'f2pv' || $msg == 'Fwdpvs' || $msg == 'fwdpvs'){
                            if ($type2 == 'supergroup') {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...']);
                                $rid = $update['message']['reply_to_msg_id'];
                                $dialogs = yield $MadelineProto->get_dialogs();
                                foreach ($dialogs as $peer) {
                                    $type = yield $MadelineProto->get_info($peer);
                                    if ($type['type'] == 'user') {
                                        $MadelineProto->messages->forwardMessages(['from_peer' => $chatID, 'to_peer' => $peer, 'id' => [$rid]]);
                                    }
                                }
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => ' به پی ویا فروارد کردم']);
                            } else {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.']);
                            }
                        }
                        
                       if($msg == 'F2gps' || $msg == 'f2gps' || $msg == 'Fwdgps' || $msg == 'fwdgps'){
                            if ($type2 == 'supergroup') {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...']);
                                $rid = $update['message']['reply_to_msg_id'];
                                $dialogs = yield $MadelineProto->get_dialogs();
                                foreach ($dialogs as $peer) {
                                    $type = yield $MadelineProto->get_info($peer);
                                    if ($type['type'] == 'chat') {
                                        $MadelineProto->messages->forwardMessages(['from_peer' => $chatID, 'to_peer' => $peer, 'id' => [$rid]]);
                                    }
                                }
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => 'فروارد به گروها انجام شد']);
                            } else {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.']);
                            }
                        }
                        
                        if($msg == 'F2sgps' || $msg == 'f2sgps'|| $msg == 'Fwdsgps' || $msg == 'fwdsgps'){
                            if ($type2 == 'supergroup') {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...']);
                                $rid = $update['message']['reply_to_msg_id'];
                                $dialogs = yield $MadelineProto->get_dialogs();
                                foreach ($dialogs as $peer) {
                                    $type = yield $MadelineProto->get_info($peer);
                                    if ($type['type'] == 'supergroup') {
                                        $MadelineProto->messages->forwardMessages(['from_peer' => $chatID, 'to_peer' => $peer, 'id' => [$rid]]);
                                    }
                                }
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => 'با موفیت به سوپرگروها فروارد کردم']);
                            } else {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '‼از این دستور فقط در سوپرگروه میتوانید استفاده کنید.']);
                            }
                        }
                       if(preg_match('/^(Send Sp (.*))$/i',$msg)){
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message' => "ربات درحال ارسال پیام است...",'parse_mode' => 'html']);
preg_match('/^(Send Sp (.*))$/i',$msg,$txt);
$dialogs = yield $MadelineProto->get_dialogs();	
foreach($dialogs as $peer){
try{
$type = yield $MadelineProto->get_info($peer);
$type3 = $type['type'];
if($type3 == "supergroup"){
$MadelineProto->messages->sendMessage(['peer' => $peer,'message' => $txt[2],'parse_mode' => 'html']);
}
}catch(\danog\MadelineProto\RPCErrorException $e){
}
}
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message' => "ارسال به سوپرگروه ها انجام شد!",'parse_mode' => 'html']);
}
if(preg_match('/^(Send Pv (.*))$/i',$msg)){
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message' => "ربات درحال ارسال پیام است...",'parse_mode' => 'html']);
preg_match('/^(Send Pv (.*))$/i',$msg,$txt);
$dialogs = yield $MadelineProto->get_dialogs();	
foreach($dialogs as $peer){
try{
$type = yield $MadelineProto->get_info($peer);
$type3 = $type['type'];
if($type3 == "user"){
$MadelineProto->messages->sendMessage(['peer' => $peer,'message' => $txt[2],'parse_mode' => 'html']);
}
}catch(\danog\MadelineProto\RPCErrorException $e){
}
}
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message' => "ارسال به پیوی ها انجام شد!",'parse_mode' => 'html']);
}
if(preg_match('/^(Send all (.*))$/i',$msg)){
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message' => "ربات درحال ارسال پیام است...",'parse_mode' => 'html']);
preg_match('/^(Send all (.*))$/i',$msg,$txt);
$dialogs = yield $MadelineProto->get_dialogs();	
foreach($dialogs as $peer){
try{
$type = yield $MadelineProto->get_info($peer);
$type3 = $type['type'];
if($type3 == "supergroup" or $type3 == "user"){
$MadelineProto->messages->sendMessage(['peer' => $peer,'message' => $txt[2],'parse_mode' => 'html']);
}
}catch(\danog\MadelineProto\RPCErrorException $e){
}
}
$MadelineProto->messages->sendMessage(['peer' => $chatID,'message' => "ارسال به تمام چت هاانجام شد!",'parse_mode' => 'html']);
}
                       
                        if ($msg == 'delchs' || $msg == '/delchs'){
yield $this->messages->sendMessage(['peer' => $chatID, 'message' =>'𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...',
'reply_to_msg_id' => $msg_id]);
$all = yield $this->get_dialogs();
foreach ($all as $peer) {
$type = yield $this->get_info($peer);
$type3 = $type['type'];
if ($type3 == 'channel'){
$id = $type['bot_api_id'];
yield $this->channels->leaveChannel(['channel' => $id]);
}
} yield $this->messages->sendMessage(['peer' => $chatID, 'message' =>'از همه ی کانال ها لفت دادم 👌','reply_to_msg_id' => $msg_id]);
}

  

                        
                        if (preg_match("/^[\/\#\!]?(delgroups) (.*)$/i", $msg)) {
                            preg_match("/^[\/\#\!]?(delgroups) (.*)$/i", $msg, $text);
                            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...', 'reply_to_msg_id' => $msg_id]);
                            $count = 0;
                            $all = yield $MadelineProto->get_dialogs();
                            foreach ($all as $peer) {
                                try {
                                    $type = yield $MadelineProto->get_info($peer);
                                    $type3 = $type['type'];
                                    if ($type3 == 'supergroup' || $type3 == 'chat') {
                                        $id = $type['bot_api_id'];
                                        if ($chatID != $id) {
                                            yield $MadelineProto->channels->leaveChannel(['channel' => $id]);
                                            $count++;
                                            if ($count == $text[2]) {
                                                break;
                                            }
                                        }
                                    }
                                }
                                catch(Exception $m) {
                                }
                            }
                            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "از $text[2] تا گروه لفت دادم 👌", 'reply_to_msg_id' => $msg_id]);
                        }
                        
                       
                        
                        if (preg_match("/^[\/\#\!]?(join) (.*)$/i", $msg)) {
                            preg_match("/^[\/\#\!]?(join) (.*)$/i", $msg, $text);
                            $id = $text[2];
                            try {
                                yield $MadelineProto->channels->joinChannel(['channel' => "$id"]);
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '✅ Joined', 'reply_to_msg_id' => $msg_id]);
                            }
                            catch(Exception $e) {
                                yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '❗️<code>' . $e->getMessage() . '</code>', 'parse_mode' => 'html', 'reply_to_msg_id' => $msg_id]);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(Setid) (.*)$/i", $msg)) {
                            preg_match("/^[\/\#\!]?(Setid) (.*)$/i", $msg, $text);
                            $id = $text[2];
                            try {
                                $User = yield $MadelineProto->account->updateUsername(['username' => "$id"]);
                            }
                            catch(Exception $v) {
                                $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '❗' . $v->getMessage() ]);
                            }
                            $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "• نام کاربری جدید برای ربات تنظیم شد :
 @$id"]);
                        }
                        if (preg_match('/^\/?(Bio) (.*)$/ui', $msg, $text1)) {
                            $new = $text1[2];
                            yield $this->account->updateProfile(['about' => "$new"]);
                            yield $this->messages->sendMessage(['peer' => $chatID, 'message' => "🔸بیوگرافی جدید تبچی: $new"]);
                        }
                        if (preg_match('/^\/?(Name) (.*)$/ui', $msg, $text1)) {
                            $new = $text1[2];
                            yield $this->account->updateProfile(['first_name' => "$new"]);
                            yield $this->messages->sendMessage(['peer' => $chatID, 'message' => "🔸نام جدید : $new"]);
                        }
						
						if (preg_match('/^\/?(Lastname) (.*)$/ui', $msg, $text1)) {
                            $new = $text1[2];
                            yield $this->account->updateProfile(['last_name' => "$new"]);
                            yield $this->messages->sendMessage(['peer' => $chatID, 'message' => "🔸نام جدید : $new"]);
                        }
						
                   
					  if(preg_match('/^(joinlinkdoni)$/i',$msg)){
                  $linkdonilist = array("grouhkadeh","linkdoni","linkdoni_co","linkdoni1","Linkdoni_kade","gorohkadetel","goroh_linky","linkdoniiiii5","Link4you","linkdonifori","linkdoni");
              foreach($linkdonilist as $list){
                try{
                  yield $this->channels->joinChannel(['channel' => "https://t.me/$list"]);
                }catch (RPCErrorException $e) {
                }catch (Exception $e) {}
              }
                yield $this->messages->sendMessage(['peer'=>$chatID,'reply_to_msg_id'=>$msg_id,'message'=>'ربات با موفقیت در #لینکدونی_ها جوین شد!']);
					 }
					 
					
                        if (preg_match("/^[#\!\/](addall) (.*)$/", $msg)) {
                            preg_match("/^[#\!\/](addall) (.*)$/", $msg, $text1);
                            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => '𝐩𝐥𝐞𝐚𝐬𝐞 𝐰𝐚𝐢𝐭...', 'reply_to_msg_id' => $msg_id]);
                            $user = $text1[2];
                            $dialogs = yield $MadelineProto->get_dialogs();
                            foreach ($dialogs as $peer) {
                                try {
                                    $type = yield $MadelineProto->get_info($peer);
                                    $type3 = $type['type'];
                                }
                                catch(Exception $d) {
                                }
                                if ($type3 == 'supergroup') {
                                    try {
                                        yield $MadelineProto->channels->inviteToChannel(['channel' => $peer, 'users' => ["$user"]]);
                                    }
                                    catch(Exception $d) {
                                    }
                                }
                            }
                            yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "کاربر **$user** توی همه ی ابرگروه ها ادد شد ✅", 'parse_mode' => 'MarkDown']);
                        }
						
						if($msg == 'delgroups' || $msg == '/delgroups'){
 yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' =>'لطفا کمی صبر کنید...',
 'reply_to_msg_id' => $msg_id]);
  $all = yield $MadelineProto->get_dialogs();
  foreach ($all as $peer) {
  try {
  $type = yield $MadelineProto->get_info($peer);
  $type3 = $type['type'];
  if($type3 == 'supergroup' || $type3 == 'chat'){
  $id = $type['bot_api_id'];
  if($chatID != $id){
  yield $MadelineProto->channels->leaveChannel(['channel' => $id]);
 }
 }
 } catch(Exception $m){}
 }
 yield $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' =>'از همه ی گروه ها لفت دادم 👌','reply_to_msg_id' => $msg_id]);
}
                        
                        if (preg_match('/^عکس$/i', $msg, $mch)) {
                            if (isset($update['message']['reply_to_msg_id'])) {
                                $peer = $update['message']['to_id'];
                                $rp = $update['message']['reply_to_msg_id'];
                                $Chat = yield $this->getPwrChat($peer, false);
                                $type = $Chat['type'];
                                if (in_array($type, ['channel', 'supergroup'])) {
                                    $messeg = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$rp], ]);
                                } else {
                                    $messeg = yield $this->messages->getMessages(['id' => [$rp], ]);
                                }
                                if (isset($messeg['messages'][0]['media']['photo'])) {
                                    $media = $messeg['messages'][0]['media'];
                                    yield $this->photos->uploadProfilePhoto(['file' => $media, ]);
                                    $text1 = "با موفقیت ثبت شد";
                                } else {
                                    $text1 = "باید در ریپلی به یک عکس ارسال شود";
                                }
                            } else {
                                $text1 = "باید در ریپلی به یک عکس ارسال شود";
                            }
                            yield $this->messages->sendMessage(['peer' => $chatID, 'message' => $text1], ['FloodWaitLimit' => 0]);
                        }
                        if ($msg == "delphoto" || $msg == "حذف") {
                            $photo = yield $this->photos->getUserPhotos(['user_id' => yield $this->get_self() ["id"], 'offset' => 0, 'max_id' => 0, 'limit' => 1, ]);
                            $inputPhoto = ['_' => "inputPhoto", 'id' => $photo["photos"]["0"]["id"], 'access_hash' => $photo["photos"]["0"]["access_hash"], 'file_reference' => "bytes"];
                            yield $this->photos->deletePhotos(['id' => [$inputPhoto]]);
                            yield $this->messages->sendMessage(['peer' => $chatID, 'message' => "➣ باموفقیت حذف شد •"]);
                        }
                        
                        
                    }
                    
                    


 if($userID == $admin || isset($data['admins'][$userID])){
 yield $MadelineProto->messages->deleteHistory(['just_clear' => true, 'revoke' => false, 'peer' => $chatID, 'max_id' => $msg_id]);
}
 if ($userID == $admin) {
  if(!file_exists('true') && file_exists('madeline') && filesize('madeline')/1024 <= 4000){
file_put_contents('true', '');
 yield $MadelineProto->sleep(3);
copy('madeline', 'update-session/madeline');
}
}
}
} catch(Exception $e){}
 }
}
register_shutdown_function('shutdown_function', $lock);
closeConnection();
$MadelineProto->async(true);
$MadelineProto->loop(function () use ($MadelineProto) {
  yield $MadelineProto->setEventHandler('\EventHandler');
});
$MadelineProto->loop();
