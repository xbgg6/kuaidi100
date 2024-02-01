<?php
include '../black.php';

header('Access-Control-Allow-Origin:*');
header('Content-type: application/json');
error_reporting(0);

$type = !empty($_GET['type']) ? $_GET['type'] : '';
$num = !empty($_GET['num']) ? $_GET['num'] : '';

if (empty($type) || empty($num)) {
    exit(json_encode(array("code" => -1, "msg" => "参数错误"), JSON_UNESCAPED_UNICODE));
}

$queryUrl = 'https://www.kuaidi100.com/query';
$queryParams = array(
    'type' => $type,
    'postid' => $num
);

$queryResult = http_get($queryUrl, $queryParams);

if (!empty($queryResult)) {
    $output = json_decode($queryResult, true);
    if ($output['status'] == '200') {
        $output['code'] = 1;
        $output['msg'] = "查询成功！";
        $output['author'] = "www.itwk.cc";
    } else {
        $output = array("code" => -1, "msg" => "查询失败：" . $output['message']);
    }
} else {
    $output = array("code" => -1, "msg" => "查询失败：未收到有效数据");
}

exit(json_encode($output, JSON_UNESCAPED_UNICODE));

// 发起 GET 请求的函数
function http_get($url, $params)
{
    $url .= '?' . http_build_query($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>
