<?php
$error = "";
$msg = "";
$fileNmae = "";
$fileElementName = 'fileToUpload';
if(!empty($_FILES[$fileElementName]['error']))
{
    switch($_FILES[$fileElementName]['error'])
    {
        case '1':
            $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            break;
        case '2':
            $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            break;
        case '3':
            $error = 'The uploaded file was only partially uploaded';
            break;
        case '4':
            $error = 'No file was uploaded.';
            break;
        case '6':
            $error = 'Missing a temporary folder';
            break;
        case '7':
            $error = 'Failed to write file to disk';
            break;
        case '8':
            $error = 'File upload stopped by extension';
            break;
        case '999':
        default:
            $error = 'No error code avaiable';
    }
}
elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
{
    $error = 'No file was uploaded....';
}
else
{
    $upload_dir = dirname(dirname(__FILE__))."/equipmentImages";    //預儲存的圖片路徑
    $targetfile = $_FILES['fileToUpload']['name'];                  //檔案名稱
    $msg .= " 檔案名稱: " . $_FILES['fileToUpload']['name'] . ", ";
    $msg .= " 檔案大小: " . @filesize($_FILES['fileToUpload']['tmp_name']);
    $fileNmae = $_FILES['fileToUpload']['name'];
 
    //將暫存檔移到目的資料夾
    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "{$upload_dir}/{$targetfile}");
 
    //刪除上傳的暫存檔
    @unlink($_FILES['fileToUpload']);
}


//宣告裝入JSON的陣列
$jsonMessage = array();
$jsonMessage[] = array("error" => $error, "msg" => urlencode($msg), "fileName" => urlencode($fileNmae), "uploadDir" => urlencode($upload_dir));
echo urldecode(json_encode($jsonMessage));

/* 
echo "{";
echo "error: '" . $error . "',\n";
echo "msg: '" . $msg . "'\n";
echo "}";
*/

?>