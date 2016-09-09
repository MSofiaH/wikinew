<?PHP

function post_request($url, $data, $referer='') {

    // Convert the data array
    $data = http_build_query($data); //for some PHP versions use: http_build_query($data, '', '&');

    // parse the given URL
    $url = parse_url($url);

    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];

    // open a socket connection on port 443 - timeout: 30 sec
    $fp = fsockopen("ssl://".$host, 443, $errno, $errstr, 30);

    if ($fp){
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.0\r\n");
        fputs($fp, "Host: $host\r\n");

        if ($referer != '')
            fputs($fp, "Referer: $referer\r\n");

        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);

        $result = '';
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 1024);
        }
    }
    else {
        return array(
            'status' => 'err',
            'error' => "$errstr ($errno)"
        );
    }

    // close the socket connection:
    fclose($fp);

    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);

    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';

    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}

//Main

if (!isset($_POST["Text"]) || !isset($_POST["PageTitle"])) {
  echo json_encode(["Error" => "Please include text and the page title in your request."]);
}

$post_data = array(
    	"USER"=> "mhidalgor89@unitec.edu",
    	"KEY"=> "wWCeJg3AQTn6DuTp9qxzaIoBpEDbXYoK",
    	"VERSION"=> "2.1",
    	"METHOD"=> "submit",
    	"FILENAME"=> $_POST["PageTitle"],
    	"TEXT"=> $_POST['text']
);

$result = post_request('https://api.plagscan.com/', $post_data);

if ($result['status'] == 'ok'){
    // Print headers
    echo json_encode(['Content' => "<pre>".str_replace("<","&lt;",$result['content'])."</pre>"]);
}
else {
  echo json_encode(["Error" => $result['error']]);
}


?>
