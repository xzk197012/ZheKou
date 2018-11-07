<?php
if (!(defined("IN_IA"))) 
{
	exit("Access Denied");
}
if (!(function_exists('m'))) 
{
	function m($name = '') 
	{
		static $_modules = array();
		if (isset($_modules[$name])) 
		{
			return $_modules[$name];
		}
		$model = NETS_HAOJIK_CORE . 'model/' . strtolower($name) . '.php';
		if (!(is_file($model))) 
		{
			exit(' Model ' . $name . ' Not Found!');
		}
		require_once $model;
		$class_name = ucfirst($name) . '_NetsHaojkModel';
		$_modules[$name] = new $class_name();
		return $_modules[$name];
	}
}
if (!(function_exists("byte_format"))) 
{
	function byte_format($input, $dec = 0) 
	{
		$prefix_arr = array(' B', 'K', 'M', 'G', 'T');
		$value = round($input, $dec);
		$i = 0;
		while (1024 < $value) 
		{
			$value /= 1024;
			++$i;
		}
		$return_str = round($value, $dec) . $prefix_arr[$i];
		return $return_str;
	}
}
if (!(function_exists("is_array2"))) 
{
	function is_array2($array) 
	{
		if (is_array($array)) 
		{
			foreach ($array as $k => $v ) 
			{
				return is_array($v);
			}
			return false;
		}
		return false;
	}
}
if (!(function_exists("set_medias"))) 
{
	function set_medias($list = array(), $fields = NULL) 
	{
		if (empty($list)) 
		{
			return array();
		}
		if (empty($fields)) 
		{
			foreach ($list as &$row ) 
			{
				$row = tomedia($row);
			}
			return $list;
		}
		if (!(is_array($fields))) 
		{
			$fields = explode(',', $fields);
		}
		if (is_array2($list)) 
		{
			foreach ($list as $key => &$value ) 
			{
				foreach ($fields as $field ) 
				{
					if (isset($list[$field])) 
					{
						$list[$field] = tomedia($list[$field]);
					}
					if (is_array($value) && isset($value[$field])) 
					{
						$value[$field] = tomedia($value[$field]);
					}
				}
			}
			return $list;
		}
		foreach ($fields as $field ) 
		{
			if (isset($list[$field])) 
			{
				$list[$field] = tomedia($list[$field]);
			}
		}
		return $list;
	}
}
if (!(function_exists("get_last_day"))) 
{
	function get_last_day($year, $month) 
	{
		return date('t', strtotime($year . '-' . $month . ' -1'));
	}
}
if (!(function_exists("show_message"))) 
{
	function show_message($msg = '', $url = '', $type = '') 
	{
		$site = new Page();
		$site->message($msg, $url, $type);
		exit();
	}
}
if (!(function_exists("show_json"))) 
{
	function show_json($status = 1, $return = NULL) 
	{
		$ret = array('status' => $status, 'result' => ($status == 1 ? array('url' => referer()) : array()));
		if (!(is_array($return))) 
		{
			if ($return) 
			{
				$ret['result']['message'] = $return;
			}
			exit(json_encode($ret));
		}
		else 
		{
			$ret['result'] = $return;
		}
		if (isset($return['url'])) 
		{
			$ret['result']['url'] = $return['url'];
		}
		else if ($status == 1) 
		{
			$ret['result']['url'] = referer();
		}
		exit(json_encode($ret));
	}
}
if (!(function_exists("is_weixin"))) 
{
	function is_weixin() 
	{
		if (NETS_HAOJIK_DEBUG)
		{
			return true;
		}
		if (empty($_SERVER['HTTP_USER_AGENT']) || ((strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false))) 
		{
			return false;
		}
		return true;
	}
}
if (!(function_exists("is_h5app"))) 
{
	function is_h5app() 
	{
		if (!(empty($_SERVER['HTTP_USER_AGENT'])) && strpos($_SERVER['HTTP_USER_AGENT'], 'CK 2.0')) 
		{
			return true;
		}
		return false;
	}
}
if (!(function_exists("is_ios"))) 
{
	function is_ios() 
	{
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) 
		{
			return true;
		}
		return false;
	}
}
if (!(function_exists("is_mobile"))) 
{
	function is_mobile() 
	{
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match("/(android|bb\\d+|meego).+mobile|avantgo|bada\\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i", $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\\-(n|u)|c55\\/|capi|ccwa|cdm\\-|cell|chtm|cldc|cmd\\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\\-s|devi|dica|dmob|do(c|p)o|ds(12|\\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\\-|_)|g1 u|g560|gene|gf\\-5|g\\-mo|go(\\.w|od)|gr(ad|un)|haie|hcit|hd\\-(m|p|t)|hei\\-|hi(pt|ta)|hp( i|ip)|hs\\-c|ht(c(\\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\\-(20|go|ma)|i230|iac( |\\-|\\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\\/)|klon|kpt |kwc\\-|kyo(c|k)|le(no|xi)|lg( g|\\/(k|l|u)|50|54|\\-[a-w])|libw|lynx|m1\\-w|m3ga|m50\\/|ma(te|ui|xo)|mc(01|21|ca)|m\\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\\-2|po(ck|rt|se)|prox|psio|pt\\-g|qa\\-a|qc(07|12|21|32|60|\\-[2-7]|i\\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\\-|oo|p\\-)|sdk\\/|se(c(\\-|0|1)|47|mc|nd|ri)|sgh\\-|shar|sie(\\-|m)|sk\\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\\-|v\\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\\-|tdg\\-|tel(i|m)|tim\\-|t\\-mo|to(pl|sh)|ts(70|m\\-|m3|m5)|tx\\-9|up(\\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\\-|your|zeto|zte\\-/i', substr($useragent, 0, 4))) 
		{
			return true;
		}
		return false;
	}
}
if (!(function_exists("b64_encode"))) 
{
	function b64_encode($obj) 
	{
		if (is_array($obj)) 
		{
			return urlencode(base64_encode(json_encode($obj)));
		}
		return urlencode(base64_encode($obj));
	}
}
if (!(function_exists("b64_decode"))) 
{
	function b64_decode($str, $is_array = true) 
	{
		$str = base64_decode(urldecode($str));
		if ($is_array) 
		{
			return json_decode($str, true);
		}
		return $str;
	}
}
if (!(function_exists("create_image"))) 
{
	function create_image($img) 
	{
		$ext = strtolower(substr($img, strrpos($img, '.')));
		if ($ext == '.png') 
		{
			$thumb = imagecreatefrompng($img);
		}
		else if ($ext == '.gif') 
		{
			$thumb = imagecreatefromgif($img);
		}
		else 
		{
			$thumb = imagecreatefromjpeg($img);
		}
		return $thumb;
	}
}
if (!(function_exists("get_authcode"))) 
{
	function get_authcode() 
	{
		$auth = get_auth();
		return (empty($auth['code']) ? '' : $auth['code']);
	}
}


if (!(function_exists("shop_template_compile")))
{
    function shop_template_compile($from, $to, $inmodule = false)
    {
        $path = dirname($to);
        if (!(is_dir($path)))
        {
            load()->func('file');
            mkdirs($path);
        }
        $content = shop_template_parse(file_get_contents($from), $inmodule);
        if ((IMS_FAMILY == 'x') && !(preg_match("/(footer|header|account\\/welcome|login|register)+/", $from)))
        {
            $content = str_replace('微擎', '系统', $content);
        }
        file_put_contents($to, $content);
    }
}
if (!(function_exists("shop_template_parse")))
{
    function shop_template_parse($str, $inmodule = false)
    {
        global $_W;
        $str = template_parse($str, $inmodule);
        if (strexists($_W['siteurl'], 'merchant.php'))
        {
            if (p('merch'))
            {
                $str = preg_replace('/{ifp\\s+(.+?)}/', '<?php if(mcv($1)) { ?>', $str);
                $str = preg_replace('/{ifpp\\s+(.+?)}/', '<?php if(mcp($1)) { ?>', $str);
                $str = preg_replace('/{ife\\s+(\\S+)\\s+(\\S+)}/', '<?php if( mce($1 ,$2) ) { ?>', $str);
                return $str;
            }
        }
        $str = preg_replace('/{ifp\\s+(.+?)}/', '<?php if(cv($1)) { ?>', $str);
        $str = preg_replace('/{ifpp\\s+(.+?)}/', '<?php if(cp($1)) { ?>', $str);
        $str = preg_replace('/{ife\\s+(\\S+)\\s+(\\S+)}/', '<?php if( ce($1 ,$2) ) { ?>', $str);
        return $str;
    }
}
if (!(function_exists("mobileUrl"))) 
{
	function mobileUrl($do = '', $query = NULL, $full = false) 
	{
		global $_W;
		global $_GPC;
		!($query) && ($query = array());
		$dos = explode('/', trim($do));
		$routes = array();
		$routes[] = $dos[0];
		if (isset($dos[1])) 
		{
			$routes[] = $dos[1];
		}
		if (isset($dos[2])) 
		{
			$routes[] = $dos[2];
		}
		if (isset($dos[3])) 
		{
			$routes[] = $dos[3];
		}
		$r = implode('.', $routes);
		if (!(empty($r))) 
		{
			$query = array_merge(array('r' => $r), $query);
		}
		$query = array_merge(array('do' => 'mobile'), $query);
		$query = array_merge(array('m' => 'nets_haojk'), $query);
		if (empty($query['mid'])) 
		{
			$mid = intval($_GPC['mid']);
			if (!(empty($mid))) 
			{
				$query['mid'] = $mid;
			}
			if (!(empty($_W['openid'])) && !(is_weixin()) && !(is_h5app())) 
			{
				$myid = m('member')->getMid();
				if (!(empty($myid))) 
				{
					$member = pdo_fetch('select id,isagent,status from' . tablename('nets_hjk_members') . 'where id=' . $myid);
//					if (!(empty($member['isagent'])) && !(empty($member['status'])))
//					{
//						$query['mid'] = $member['id'];
//					}
				}
			}
		}
		if (empty($query['merchid'])) 
		{
			$merchid = intval($_GPC['merchid']);
			if (!(empty($merchid))) 
			{
				$query['merchid'] = $merchid;
			}
		}
		else if ($query['merchid'] < 0) 
		{
			unset($query['merchid']);
		}
		if ($full) 
		{
			return $_W['siteroot'] . 'app/' . substr(murl('entry', $query, true), 2);
		}
		return murl("entry", $query, true);
	}
}
if (!(function_exists("webUrl"))) 
{
	function webUrl($do = '', $query = array(), $full = true) 
	{
		global $_W;
		global $_GPC;
		if (!(empty($_W['plugin']))) 
		{
			if ($_W['plugin'] == 'merch') 
			{
				if (function_exists('merchUrl')) 
				{
					return merchUrl($do, $query, $full);
				}
			}
		}
		$dos = explode('/', trim($do));
		$routes = array();
		$routes[] = $dos[0];
		if (isset($dos[1])) 
		{
			$routes[] = $dos[1];
		}
		if (isset($dos[2])) 
		{
			$routes[] = $dos[2];
		}
		if (isset($dos[3])) 
		{
			$routes[] = $dos[3];
		}
		$r = implode('.', $routes);
		if (!(empty($r))) 
		{
			$query = array_merge(array('r' => $r), $query);
		}
		$query = array_merge(array('do' => 'web'), $query);
		$query = array_merge(array('m' => 'nets_haojk'), $query);
		if ($full) 
		{
			return $_W['siteroot'] . 'web/' . substr(wurl('site/entry', $query), 2);
		}
		return wurl("site/entry", $query);
	}
}
if (!(function_exists("dump"))) 
{
	function dump() 
	{
		$args = func_get_args();
		foreach ($args as $val ) 
		{
			echo '<pre style="color: red">';
			var_dump($val);
			echo "</pre>";
		}
	}
}
if (!(function_exists("my_scandir"))) 
{
	function my_scandir($dir) 
	{
		global $my_scenfiles;
		if ($handle = opendir($dir)) 
		{
			while (($file = readdir($handle)) !== false) 
			{
				if (($file != '..') && ($file != '.')) 
				{
					if (is_dir($dir . '/' . $file)) 
					{
						my_scandir($dir . '/' . $file);
					}
					else 
					{
						$my_scenfiles[] = $dir . '/' . $file;
					}
				}
			}
			closedir($handle);
		}
	}
	$my_scenfiles = array();
}
if (!(function_exists("cut_str"))) 
{
	function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') 
	{
		if ($code == 'UTF-8') 
		{
			$pa = '/[' . "\x1" . '-]|[' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . ']|' . "?" . '[' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . ']|[' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . ']|' . "?" . '[' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . ']|[' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . '][' . "?" . '-' . "?" . ']/';
			preg_match_all($pa, $string, $t_string);
			if ($sublen < (count($t_string[0]) - $start)) 
			{
				return join('', array_slice($t_string[0], $start, $sublen));
			}
			return join('', array_slice($t_string[0], $start, $sublen));
		}
		$start = $start * 2;
		$sublen = $sublen * 2;
		$strlen = strlen($string);
		$tmpstr = '';
		$i = 0;
		while ($i < $strlen) 
		{
			if (($start <= $i) && ($i < ($start + $sublen))) 
			{
				if (129 < ord(substr($string, $i, 1))) 
				{
					$tmpstr .= substr($string, $i, 2);
				}
				else 
				{
					$tmpstr .= substr($string, $i, 1);
				}
			}
			if (129 < ord(substr($string, $i, 1))) 
			{
				++$i;
			}
			++$i;
		}
		return $tmpstr;
	}
}
if (!(function_exists("save_media"))) 
{
	function save_media($url, $enforceQiniu = false) 
	{
		global $_W;
		static $com;
		if (!($com)) 
		{
			$com = com('qiniu');
		}
		if ($com) 
		{
			$qiniu_url = $com->save($url, NULL, $enforceQiniu);
			if (!(empty($qiniu_url)) && !(is_error($qiniu_url))) 
			{
				return $qiniu_url;
			}
		}
		$ext = strrchr($url, '.');
		if (($ext != '.jpeg') && ($ext != '.gif') && ($ext != '.jpg') && ($ext != '.png')) 
		{
			return $url;
		}
		if (!(empty($_W['setting']['remote']['type'])) && !(empty($url)) && !(strexists($url, 'http:') || strexists($url, 'https:'))) 
		{
			if (is_file(ATTACHMENT_ROOT . $url)) 
			{
				load()->func('file');
				$remotestatus = file_remote_upload($url, false);
				if (!(is_error($remotestatus))) 
				{
					$remoteurl = $_W['attachurl_remote'] . $url;
					return $remoteurl;
				}
			}
		}
		return $url;
	}
}
if (!(function_exists("array_column"))) 
{
	function array_column($input, $column_key, $index_key = NULL) 
	{
		$arr = array();
		foreach ($input as $d ) 
		{
			if (!(isset($d[$column_key]))) 
			{
				return;
			}
			if ($index_key !== NULL) 
			{
				return array($d[$index_key] => $d[$column_key]);
			}
			$arr[] = $d[$column_key];
		}
		if ($index_key !== NULL) 
		{
			$tmp = array();
			foreach ($arr as $ar ) 
			{
				$tmp[key($ar)] = current($ar);
			}
			$arr = $tmp;
		}
		return $arr;
	}
}
if (!(function_exists("is_utf8"))) 
{
	function is_utf8($str) 
	{
		return preg_match('%^(?:' . "\r\n" . '            [\	\
\\ -\~]              # ASCII' . "\r\n" . '            | [\?-\?][\?-\?]             # non-overlong 2-byte' . "\r\n" . '            | \?[\?-\?][\?-\?]         # excluding overlongs' . "\r\n" . '            | [\?-\?\?\?][\?-\?]{2}  # straight 3-byte' . "\r\n" . '            | \?[\?-\?][\?-\?]         # excluding surrogates' . "\r\n" . '            | \?[\?-\?][\?-\?]{2}      # planes 1-3' . "\r\n" . '            | [\?-\?][\?-\?]{3}          # planes 4-15' . "\r\n" . '            | \?[\?-\?][\?-\?]{2}      # plane 16' . "\r\n" . '            )*$%xs', $str);
	}
}
if (!(function_exists("price_format"))) 
{
	function price_format($price) 
	{
		$prices = explode('.', $price);
		if (intval($prices[1]) <= 0) 
		{
			$price = $prices[0];
		}
		else if (isset($prices[1][1]) && ($prices[1][1] <= 0)) 
		{
			$price = $prices[0] . '.' . $prices[1][0];
		}
		return $price;
	}
}
if (!(function_exists("redis"))) 
{
	function redis() 
	{
		global $_W;
		static $redis;
		if (is_null($redis)) 
		{
			if (!(extension_loaded('redis'))) 
			{
				return error(-1, 'PHP 未安装 redis 扩展');
			}
			if (!(isset($_W['config']['setting']['redis']))) 
			{
				return error(-1, '未配置 redis, 请检查 data/config.php 中参数设置');
			}
			$config = $_W['config']['setting']['redis'];
			if (empty($config['server'])) 
			{
				$config['server'] = '127.0.0.1';
			}
			if (empty($config['port'])) 
			{
				$config['port'] = '6379';
			}
			$redis_temp = new Redis();
			if ($config['pconnect']) 
			{
				$connect = $redis_temp->pconnect($config['server'], $config['port'], $config['timeout']);
			}
			else 
			{
				$connect = $redis_temp->connect($config['server'], $config['port'], $config['timeout']);
			}
			if (!($connect)) 
			{
				return error(-1, 'redis 连接失败, 请检查 data/config.php 中参数设置');
			}
			if (!(empty($config['requirepass']))) 
			{
				$redis_temp->auth($config['requirepass']);
			}
			try 
			{
				$ping = $redis_temp->ping();
			}
			catch (ErrorException $e) 
			{
				return error(-1, 'redis 无法正常工作，请检查 redis 服务');
			}
			if ($ping != '+PONG') 
			{
				return error(-1, 'redis 无法正常工作，请检查 redis 服务');
			}
			$redis = $redis_temp;
		}
		return $redis;
	}
}
if (!(function_exists("logg"))) 
{
	function logg($name, $data) 
	{
		global $_W;
		$data = ((is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data));
		file_put_contents(IA_ROOT . '/' . $name, $data);
	}
}
if (!(function_exists("is_wxerror"))) 
{
	function is_wxerror($data) 
	{
		if (!(is_array($data)) || !(array_key_exists('errcode', $data)) || (array_key_exists('errcode', $data) && ($data['errcode'] == 0))) 
		{
			return false;
		}
		return true;
	}
}
if (!(function_exists("set_wxerrmsg"))) 
{
	function set_wxerrmsg($data) 
	{
		$errors = array(-1 => '系统繁忙，此时请稍候再试', 0 => '请求成功', 40001 => '获取access_token时AppSecret错误，或者access_token无效。请认真比对AppSecret的正确性，或查看是否正在为恰当的公众号调用接口', 40002 => '不合法的凭证类型', 40003 => '不合法的OpenID，请确认OpenID（该用户）是否已关注公众号，或是否是其他公众号的OpenID', 40004 => '不合法的媒体文件类型', 40005 => '不合法的文件类型', 40006 => '不合法的文件大小', 40007 => '不合法的媒体文件id', 40008 => '不合法的消息类型', 40009 => '不合法的图片文件大小', 40010 => '不合法的语音文件大小', 40011 => '不合法的视频文件大小', 40012 => '不合法的缩略图文件大小', 40013 => '不合法的AppID，请检查AppID的正确性，避免异常字符，注意大小写', 40014 => '不合法的access_token，请认真比对access_token的有效性（如是否过期），或查看是否正在为恰当的公众号调用接口', 40015 => '不合法的菜单类型', 40016 => '不合法的按钮个数', 40017 => '不合法的按钮个数', 40018 => '不合法的按钮名字长度', 40019 => '不合法的按钮KEY长度', 40020 => '不合法的按钮URL长度', 40021 => '不合法的菜单版本号', 40022 => '不合法的子菜单级数', 40023 => '不合法的子菜单按钮个数', 40024 => '不合法的子菜单按钮类型', 40025 => '不合法的子菜单按钮名字长度', 40026 => '不合法的子菜单按钮KEY长度', 40027 => '不合法的子菜单按钮URL长度', 40028 => '不合法的自定义菜单使用用户', 40029 => '不合法的oauth_code', 40030 => '不合法的refresh_token', 40031 => '不合法的openid列表', 40032 => '不合法的openid列表长度', 40033 => '不合法的请求字符，不能包含\\uxxxx格式的字符', 40035 => '不合法的参数', 40038 => '不合法的请求格式', 40039 => '不合法的URL长度', 40050 => '不合法的分组id', 40051 => '分组名字不合法', 40117 => '分组名字不合法', 40118 => 'media_id大小不合法', 40119 => 'button类型错误', 40120 => 'button类型错误', 40121 => '不合法的media_id类型', 40132 => '微信号不合法', 40137 => '不支持的图片格式', 40155 => '请勿添加其他公众号的主页链接', 41001 => '缺少access_token参数', 41002 => '缺少appid参数', 41003 => '缺少refresh_token参数', 41004 => '缺少secret参数', 41005 => '缺少多媒体文件数据', 41006 => '缺少media_id参数', 41007 => '缺少子菜单数据', 41008 => '缺少oauth code', 41009 => '缺少openid', 42001 => 'access_token超时，请检查access_token的有效期，请参考基础支持-获取access_token中，对access_token的详细机制说明', 42002 => 'refresh_token超时', 42003 => 'oauth_code超时', 42007 => '用户修改微信密码，accesstoken和refreshtoken失效，需要重新授权', 43001 => '需要GET请求', 43002 => '需要POST请求', 43003 => '需要HTTPS请求', 43004 => '需要接收者关注', 43005 => '需要好友关系', 43019 => '需要将接收者从黑名单中移除', 44001 => '多媒体文件为空', 44002 => 'POST的数据包为空', 44003 => '图文消息内容为空', 44004 => '文本消息内容为空', 45001 => '多媒体文件大小超过限制', 45002 => '消息内容超过限制', 45003 => '标题字段超过限制', 45004 => '描述字段超过限制', 45005 => '链接字段超过限制', 45006 => '图片链接字段超过限制', 45007 => '语音播放时间超过限制', 45008 => '图文消息超过限制', 45009 => '接口调用超过限制', 45010 => '创建菜单个数超过限制', 45011 => 'API调用太频繁，请稍候再试', 45015 => '回复时间超过限制', 45016 => '系统分组，不允许修改', 45017 => '分组名字过长', 45018 => '分组数量超过上限', 45047 => '客服接口下行条数超过上限', 46001 => '不存在媒体数据', 46002 => '不存在的菜单版本', 46003 => '不存在的菜单数据', 46004 => '不存在的用户', 47001 => '解析JSON/XML内容错误', 48001 => 'api功能未授权，请确认公众号已获得该接口，可以在公众平台官网-开发者中心页中查看接口权限', 48002 => '粉丝拒收消息（粉丝在公众号选项中，关闭了“接收消息”）', 48004 => 'api接口被封禁，请登录mp.weixin.qq.com查看详情', 48005 => 'api禁止删除被自动回复和自定义菜单引用的素材', 48006 => 'api禁止清零调用次数，因为清零次数达到上限', 50001 => '用户未授权该api', 50002 => '用户受限，可能是违规后接口被封禁', 61451 => '参数错误(invalid parameter)', 61452 => '无效客服账号(invalid kf_account)', 61453 => '客服帐号已存在(kf_account exsited)', 61454 => '客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)(invalid   kf_acount length)', 61455 => '客服帐号名包含非法字符(仅允许英文+数字)(illegal character in     kf_account)', 61457 => '无效头像文件类型(invalid   file type)', 61450 => '系统错误(system error)', 61500 => '日期格式错误', 65301 => '不存在此menuid对应的个性化菜单', 65302 => '没有相应的用户', 65303 => '没有默认菜单，不能创建个性化菜单', 65304 => 'MatchRule信息为空', 65305 => '个性化菜单数量受限', 65306 => '不支持个性化菜单的帐号', 65307 => '个性化菜单信息为空', 65308 => '包含没有响应类型的button', 65309 => '个性化菜单开关处于关闭状态', 65310 => '填写了省份或城市信息，国家信息不能为空', 65311 => '填写了城市信息，省份信息不能为空', 65312 => '不合法的国家信息', 65313 => '不合法的省份信息', 65314 => '不合法的城市信息', 65316 => '该公众号的菜单设置了过多的域名外跳（最多跳转到3个域名的链接）', 65317 => '不合法的URL', 9001001 => 'POST数据参数不合法', 9001002 => '远端服务不可用', 9001003 => 'Ticket不合法', 9001004 => '获取摇周边用户信息失败', 9001005 => '获取商户信息失败', 9001006 => '获取OpenID失败', 9001007 => '上传文件缺失', 9001008 => '上传素材的文件类型不合法', 9001009 => '上传素材的文件尺寸不合法', 9001010 => '上传失败', 9001020 => '帐号不合法', 9001021 => '已有设备激活率低于50%，不能新增设备', 9001022 => '设备申请数不合法，必须为大于0的数字', 9001023 => '已存在审核中的设备ID申请', 9001024 => '一次查询设备ID数量不能超过50', 9001025 => '设备ID不合法', 9001026 => '页面ID不合法', 9001027 => '页面参数不合法', 9001028 => '一次删除页面ID数量不能超过10', 9001029 => '页面已应用在设备中，请先解除应用关系再删除', 9001030 => '一次查询页面ID数量不能超过50', 9001031 => '时间区间不合法', 9001032 => '保存设备与页面的绑定关系参数错误', 9001033 => '门店ID不合法', 9001034 => '设备备注信息过长', 9001035 => '设备申请参数不合法', 9001036 => '查询起始值begin不合法');
		if (array_key_exists($data['errcode'], $errors)) 
		{
			$data['errmsg'] = $errors[$data['errcode']];
		}
		return $data;
	}
}
function auth_user($siteid, $domain) 
{
	$ret = cloud_upgrade('user', array('website' => $siteid, 'domain' => $domain));
	return $ret;
}
function auth_checkauth($auth) 
{
	$ret = cloud_upgrade('checkauth', array('code' => $auth['code']));
	return $ret;
}
function auth_grant($data) 
{
	$ret = cloud_upgrade('grant', array('code' => $data['code']));
	return $ret;
}
function auth_check($auth, $version, $release) 
{
	$ret = cloud_upgrade('check', array('version' => $version, 'release' => $release, 'code' => $auth['code']));
	return $ret;
}
function auth_download($auth, $path) 
{
	$ret = cloud_upgrade('download', array('path' => $path, 'code' => $auth['code']));
	return $ret;
}
function auth_downaddress($auth) 
{
	$ret = cloud_upgrade('downaddress', array('code' => $auth['code']));
	return $ret;
}
function auth_upaddress($auth, $data) 
{
	$ret = cloud_upgrade('upaddress', array('code' => $auth['code'], 'data' => $data));
	return $ret;
}



//add by t
function str($json){
    $re_json = str_replace('"',"",json_encode(MODULE_URL."/skin").'\\');
    $save = str_replace("{hello}", $re_json,$json);
    return $save;
}
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    }
    if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

function arr_ru($action){
    if (is_array($action)) {
        foreach ($action as $key => $value) {
            $ru = $value;

        }
    }
    return $ru;
}

function recent($day){
    $date['begin']=mktime(0,0,0,date('m'),date('d')-$day,date('Y'));
    $date['end']=mktime(0,0,0,date('m'),date('d'),date('Y'));
    return $date;
}


function get_fromnickname($from_uid){
    global $_GPC, $_W;
    //return $from_uid;
    if(empty($from_uid)){
        return "无";
    }
    $from_member=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_members")." AS em  where  em.memberid=:memberid",array(":memberid"=>$from_uid));

    return $from_member["nickname"];
}
//证书文件上传
function file_upload1($file, $type = 'pem', $name = '') {
    $harmtype = array('pem');
    if (empty($file)) {
        return error(-1, '没有上传内容');
    }
    if (!in_array($type, array('pem'))) {
        return error(-2, '未知的上传类型');
    }
    global $_W;
    $result = array();
    $uniacid = intval($_W['uniaccount']['uniacid']);
    $path = "fytcert/".$name.".".$uniacid;
    $result['path'] = $path;
    if (!file_move3($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
        return error(-1, '保存上传文件失败');
    }
    $result['success'] = true;
    return $result;
}
function file_move3($filename, $dest) {
    //print("<br/>".$filename);
    //print("<br/>".$dest);
    global $_W;
    if (is_uploaded_file($filename)) {
        move_uploaded_file($filename, $dest);
    } else {
        //rename($filename, $dest);
    }
    @chmod($filename, $_W['config']['setting']['filemode']);
    return is_file($dest);
}

function get_ip(){
    if(isset($_SERVER)){
        if($_SERVER['SERVER_ADDR']){
            $server_ip=$_SERVER['SERVER_ADDR'];
        }
    }else{
        $server_ip = getenv('SERVER_ADDR');
    }
    if(_isPrivate($server_ip)||empty($server_ip)){

        $host=$_SERVER['HTTP_HOST'];
        $arr = explode('.',$host);
        if(count($arr)==3){
            $host=$arr[1].".".$arr[2];
        }
        $set["from_host"]=$host;
        $server_ip = gethostbyname($_SERVER["HTTP_HOST"]);
    }
    return $server_ip;
}
function _isPrivate($ip) {
    $i = explode('.', $ip);
    if ($i[0] == 10) return true;
    if ($i[0] == 172 && $i[1] > 15 && $i[1] < 32) return true;
    if ($i[0] == 127 && $i[1] == 0) return true;
    if ($i[0] == 192 && $i[1] == 168) return true;
    return false;
}



//获取商品源商品
function getSourceGoods($page,$cateid){
    global $_GPC,$_W;
    $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));

    $pagesize=20;
    $where="";
    if(!empty($cateid)){
        $where=" and menuid=".$cateid;
    }
    $total=pdo_fetch("select count(0) AS 'total' from ".tablename("nets_hjk_usegoods")." where uniacid=:uniacid ".$where,array(":uniacid"=>$_W['uniacid']));
    $total=empty($total['total'])?0:$total['total'];
    //echo "123::".$total;
    $usegoods=pdo_fetchall("select * from ".tablename("nets_hjk_usegoods")." where uniacid=:uniacid ".$where." order by sort desc limit " . (($page - 1) * $pagesize) . ',' .$pagesize,array(":uniacid"=>$_W['uniacid']));

    $skuids="";
    foreach($usegoods AS $ug){
        $skuids.=$ug['skuId'].",";
    }
    $skuids=substr($skuids,0,strlen($skuids)-1);
    $operation = !empty($_GPC['op'])?$_GPC['op']:'useselgoods';
    $data = array(
        'unionId' => $global['jduniacid'],
        'skuIds' => $skuids,
    );
    $url=HAOJK_HOST."index/goodslisbyskuids";
    $temp_url="index/goodslisbyskuids".$_W['uniacid'];
    $filename=getfilename($temp_url);
    load()->func('communication');
    $res=ihttp_post($url,$data);
    $res=$res["content"];
    $list=json_decode($res);
    //$total=$list->total;
    $list=$list->data;
    $json_string = json_encode($list);
    file_put_contents($filename, $json_string);
    $json=file_get_contents($filename);
    $list=json_decode($json, true);
    for($i=0;$i<count($list);$i++){
        foreach($usegoods AS $ug){
            if($ug['skuId']==$list[$i]['skuId']){
                $list[$i]['sort']=$ug['sort'];
                $list[$i]['id']=$ug['id'];
            }
        }
    }
    //var_dump($list);
    return array("list"=>$list,"total"=>$total);
}
//根据pid获取用户名
function getUserNameByPid($pid,$orderno=''){
    global $_GPC, $_W;
    if(empty($_GPC['isbyuser'])){
        $m=pdo_fetch("SELECT m.memberid,m.nickname FROM ".tablename("nets_hjk_probit")." AS p LEFT JOIN ".tablename("nets_hjk_members")." AS m ON m.memberid=p.memberid and m.uniacid=p.uniacid WHERE bitno=:bitno and  p.uniacid=:uniacid  and p.memberid <>0",array(":bitno"=>$pid,":uniacid"=>$_W['uniaccount']['uniacid']));
        if(empty($m) || empty($m["nickname"])){
            $m["nickname"]="站长";
        }
        return $m["memberid"].'/'.$m["nickname"];
    }else{
        $o=pdo_fetch("SELECT o.*,m.nickname FROM ".tablename("nets_hjk_orders")." AS o LEFT JOIN ".tablename("nets_hjk_members")." AS m ON m.memberid=o.memberid WHERE orderno=:orderno",array(":orderno"=>$orderno));
        return $o["nickname"];
    }
}
//根据pid获取用户名
function getUserNameByPddPid($pid,$orderno=''){
    global $_GPC, $_W;
    if(empty($_GPC['isbyuser'])){
        $m=pdo_fetch("SELECT m.memberid,m.nickname FROM ".tablename("nets_hjk_members")." AS m WHERE pdd_bitno=:bitno",array(":bitno"=>$pid));
        if(empty($m) || empty($m["nickname"])){
            $m["nickname"]="站长";
        }
        return $m["memberid"].'/'.$m["nickname"];
    }else{
        $o=pdo_fetch("SELECT o.*,m.nickname FROM ".tablename("nets_hjk_orders")." AS o LEFT JOIN ".tablename("nets_hjk_members")." AS m ON m.memberid=o.memberid WHERE orderno=:orderno",array(":orderno"=>$orderno));
        return $o["nickname"];
    }
}
//根据pid获取昵称
function getPidByNickname($nickname){
    global $_W;
    $m=pdo_fetch("SELECT jd_bitno as bitno from ".tablename("nets_hjk_members")."   WHERE  uniacid=:uniacid and (nickname=:nickname or memberid=:nickname)",array(":nickname"=>$nickname,":uniacid"=>$_W['uniaccount']['uniacid']));
    if(empty($m) || empty($m["bitno"])){
        $m["bitno"]="";
    }
    return $m["bitno"];
}
//根据用户名获取订单
function getUserNameByOrderno($orderno){
    global $_W;
    $o=pdo_fetch("SELECT o.*,m.nickname FROM ".tablename("nets_hjk_orders")." AS o LEFT JOIN ".tablename("nets_hjk_members")." AS m ON m.memberid=o.memberid WHERE orderno=:orderno",array(":orderno"=>$orderno));
    if(!empty($o)){
        return "用户提单<br/>".$o["nickname"]."[ID:".$o["memberid"]."]";
    }else{
        return "推广订单";
    }
}
//根据用户名获取订单
function getUserNameByOrdernoForCutting($orderno){
    global $_W;
    $o=pdo_fetch("SELECT o.*,m.nickname FROM ".tablename("nets_hjk_freeorders")." AS o LEFT JOIN ".tablename("nets_hjk_members")." AS m ON m.memberid=o.memberid WHERE orderno=:orderno",array(":orderno"=>$orderno));
    if(!empty($o)){
        return "用户提单<br/>".$o["nickname"]."[ID:".$o["memberid"]."]";
    }else{
        return "推广订单";
    }
}
//验证砍价订单是否已返还过
function getMemberLogForCutting($orderId){
    global $_W;
    $orderlog=pdo_fetch("select * from ".tablename("nets_hjk_member_logs")." where logno=:orderno and remark like '%购买免单商品返还%'",array(":orderno"=>$orderId));
    if(!empty($orderlog)){
        return 'false';
	}
	return 'true';
}

//微信付款
function payWeixin($openid,$money,$mchid,$tradeno=""){
    global $_GPC, $_W;
    $uniacid=$_W['uniaccount']['uniacid'];
    //测试
    $wxconfig=get_wxconfig_admin($mchid);
    $settings["appid"]=$wxconfig['appid'];
    $settings["appsecret"]=$wxconfig['appsecret'];
    $settings["mchid"]=$wxconfig['mchid'];
    $settings["uniacid"]=$_W['uniaccount']['uniacid'];
    $settings['password']=$wxconfig['password'];
    $settings['tj_amount']=-1*$money*100;//money是负数//1*100; //这里要转换成分
    $toUser=$openid;//"onwnCvmcr8-_uDCa2BPLzC4xX3Es";//测试的openid
    $settings['ip']=$wxconfig['ip'];
    $settings['password']=$wxconfig['password'];
    $result=sendhb($settings,$toUser,$tradeno);
    return $result;
}
function get_wxconfig_admin($mchid=""){
    global $_GPC, $_W;
    $uniacid=$_W['uniaccount']['uniacid'];
    $setting = uni_setting($uniacid, array('payment', 'recharge'));
    $pay = $setting['payment'];
    $wxconfig['appid']=trim($_W['uniaccount']['key']);
    $wxconfig['appsecret']=trim($_W['uniaccount']['secret']);
    $wxconfig['mchid']=trim($mchid);
    $wxconfig['ip']=get_ip();//服务器IP
    $wxconfig['password']=$pay['wechat']['signkey'];
    //var_dump($wxconfig);
    return $wxconfig;
}

/*
 * 企业微信打款给微信用户
 */
function sendhb($settings,$toUser,$tradeno=""){
    global $_GPC, $_W;
	define('MB_ROOT', IA_ROOT . '/attachment/botcert');//定义的微信支付证书路径
	
	//echo "订单号：：".$tradeno['tradeno'];
    load()->func('communication');
    if (empty($settings['tj_amount'])){
        return;
    }
    $amount=$settings['tj_amount'];
    $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
    $pars = array();
    $pars['mch_appid'] =$settings['appid'];
    $pars['mchid'] = $settings['mchid'];
    $pars['nonce_str'] = random(32);
    $pars['partner_trade_no'] = $tradeno;
    $pars['openid'] =$toUser;
    $pars['check_name'] = "NO_CHECK";
    $pars['amount'] =$amount;
    $pars['desc'] = "提现";
    $pars['spbill_create_ip'] =$settings['ip'];
    ksort($pars, SORT_STRING);
    $string1 = '';
    foreach($pars as $k => $v) {
        $string1 .= "{$k}={$v}&";
    }
    $string1 .= "key={$settings['password']}";
    $pars['sign'] = strtoupper(md5($string1));
    $xml = array2xml($pars);
    $extras = array();
    // $extras['CURLOPT_CAINFO'] = MB_ROOT . '/rootca.pem.7';
    // $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/apiclient_cert.pem.7';
    // $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/apiclient_key.pem.7';
    if(!empty($settings["uniacid"])){
		if(file_exists(MB_ROOT . '/rootca.pem.'.$settings["uniacid"])){
			$extras['CURLOPT_CAINFO'] = MB_ROOT . '/rootca.pem.'.$settings["uniacid"];
		}
        $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/apiclient_cert.pem.'.$settings["uniacid"];
        $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/apiclient_key.pem.'.$settings["uniacid"];
    }
    $procResult = null;
    $resp = ihttp_request($url, $xml, $extras);
    if(is_error($resp)){
        $procResult = $resp;
    } else {
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
        $dom = new DOMDocument();
        if($dom->loadXML($xml)) {
            $xpath = new DOMXPath($dom);
            $code = $xpath->evaluate('string(//xml/return_code)');
            $ret = $xpath->evaluate('string(//xml/result_code)');
            if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                $procResult = true;
            } else {
                $error = $xpath->evaluate('string(//xml/err_code_des)');
                $procResult = error(-2, $error);
            }
        } else {
            $procResult = error(-1, 'error response');
        }
        //var_dump($procResult);
    }
    return $procResult;
}

function chstr($str,$in){

    $tmparr = explode($in,$str);

    if(count($tmparr)>1){

        return true;

    }else{

        return false;

    }

}


function GrabImage($url, $filename = "") {
    if ($url == ""):return false;
    endif;
    //如果$url地址为空，直接退出
    if ($filename == "") {
        //如果没有指定新的文件名
        $ext = strrchr($url, ".");
        //得到$url的图片格式
        if ($ext != ".gif" && $ext != ".jpg"):return false;
        endif;
        //如果图片格式不为.gif或者.jpg，直接退出
        $filename = date("dMYHis") . $ext;
        //用天月面时分秒来命名新的文件名
    }
    ob_start();//打开输出
    readfile($url);//输出图片文件
    $img = ob_get_contents();//得到浏览器输出
    ob_end_clean();//清除输出并关闭
    $size = strlen($img);//得到图片大小
    $fp2 = @fopen($filename, "a");
    fwrite($fp2, $img);//向当前目录写入图片文件，并重新命名
    fclose($fp2);
    return $filename;//返回新的文件名
}

function publicAliPay($params = array(), $return = NULL)
{
    $public = array('app_id' => $params['app_id'], 'method' => $params['method'], 'format' => 'JSON', 'charset' => 'utf-8', 'sign_type' => 'RSA2', 'timestamp' => date('Y-m-d H:i:s'), 'version' => '1.0');
    if (!(empty($params['return_url'])))
    {
        $public['return_url'] = $params['return_url'];
    }
    if (!(empty($params['app_auth_token'])))
    {
        $public['app_auth_token'] = $params['app_auth_token'];
    }
    if (!(empty($params['notify_url'])))
    {
        $public['notify_url'] = $params['notify_url'];
    }
    if (!(empty($params['biz_content'])))
    {
        $public['biz_content'] = ((is_array($params['biz_content']) ? json_encode($params['biz_content']) : $params['biz_content']));
    }
    $signature = '';
    $res = "-----BEGIN RSA PRIVATE KEY-----\n".
        wordwrap(chackKey($params['private_key']), 64, "\n", true).
        "\n-----END RSA PRIVATE KEY-----";
    openssl_sign(getSignContent($public), $signature, $res, OPENSSL_ALGO_SHA256);
    $signature = base64_encode($signature);
    $public['sign'] = $signature;
    load()->func('communication');
    $url = 'https://openapi.alipay.com/gateway.do';
    if ($return !== NULL)
    {
        return $public;
    }
    $response = ihttp_post($url, $public);
    $result = json_decode(iconv('GBK', 'UTF-8//IGNORE', $response['content']), true);
    return $result;
}

function chackKey($key, $public = true)
{
    if (empty($key))
    {
        return $key;
    }
    if ($public)
    {
        if (strexists($key, '-----BEGIN PUBLIC KEY-----'))
        {
            $key = str_replace(array('-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----'), '', $key);
        }
        $head_end = '-----BEGIN PUBLIC KEY-----' . "\n" . '{key}' . "\n" . '-----END PUBLIC KEY-----';
    }
    else if (strexists($key, '-----BEGIN RSA PRIVATE KEY-----'))
    {
        $key = str_replace(array('-----BEGIN RSA PRIVATE KEY-----', '-----END RSA PRIVATE KEY-----'), '', $key);
    }
    return $key;
}

function getSignContent(array $toBeSigned, $verify = false)
    {
        ksort($toBeSigned);

$stringToBeSigned = '';
foreach ($toBeSigned as $k => $v) {
    if ($verify && $k != 'sign' && $k != 'sign_type') {
        $stringToBeSigned .= $k.'='.$v.'&';
    }
    if (!$verify && $v !== '' && !is_null($v) && $k != 'sign' && '@' != substr($v, 0, 1)) {
        $stringToBeSigned .= $k.'='.$v.'&';
    }
}
$stringToBeSigned = substr($stringToBeSigned, 0, -1);
unset($k, $v);

return $stringToBeSigned;
}
?>