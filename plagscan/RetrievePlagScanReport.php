<?php
//PlagScan API usage example in PHP (requires PHP 5 or higher)
//Make sure to change user and key and PID!

// Submit those variables to the server
$post_data = array(
    	"USER"=> "074632015",
    	"KEY"=> "wWCeJg3AQTn6DuTp9qxzaIoBpEDbXYoK",
    	"VERSION"=> "2.1",
    	"METHOD"=> "retrieve",
      //This is the PID from the email
    	"PID"=> $_GET["PID"],
      //This is interactive HTML mode
    	"MODE"=> "6"
);
/*
Retrieve report modes
*    0      Retrieve statistics only, such as plagiarism level, number of hits and sources
*    1      Retrieve links; to list of possible plagiarism sources; e.g. http://www.plagscan.com/report?6055
and in-document view of possible plagiarism sources; e.g. http://www.plagscan.com/view?6055
*    2      Retrieve XML with data on all possible plagiarism sources
*    3      Retrieve annotated Docx document (if available, depending on user configuration)
*    4      Retrieve HTML document with annotations
*    5      Retrieve HTML report of matches sorted by relevance
*/

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

// Send the request
$result = post_request('https://api.plagscan.com/', $post_data);

if ($result['status'] == 'ok'){

    // Print headers
    echo $result['header'];

     //if docx data successfully retrieved try to write this to a file:
    if((intval($post_data["MODE"])==3)&&(!strpos($result['content'],"N/A")))
    	if(!file_put_contents("test.docx",$result['content']))
    		echo '<br/><b>Could not write test.docx - no access rights?</b>';

    echo '<hr />';

    // print the result of the whole request:
    echo "<pre>".str_replace("<","&lt;",$result['content'])."</pre>";
    //For live use you need to parse the xml in $result['content']!

}
else {
    echo 'An error occured: ' . $result['error'];
}

?>
