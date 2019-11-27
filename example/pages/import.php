<?php

include_once('data.php');

/**
 * example for import
 */

// IO物件建構
$io = new \marshung\io\IO();

try {
    // 匯入處理 - 取得匯入資料
    $dataImport = $io->import($builder = 'Excel', $fileArgu = 'fileupload');
} catch (Exception $e) {
    echo 'Code: ' . $e->getCode() . "<br>\n";
    echo 'Message: ' . $e->getMessage() . "<br>\n";
    exit;
}



// 取得原資料
$dataOrig = getData();

$configName = $io->getConfig()->getOption('configName');

echo '<pre>';
echo 'Config Name = ' . $configName . "<br>\n";


/*
 * 資料比較
 */
// 更新後 新增資料
$diffNew = diffRecursive($dataImport, $dataOrig);

// 更新後 缺少資料
$diffOld = diffRecursive($dataOrig, $dataImport);

// 回傳 json
responseHtml([
    '$diffNew' => $diffNew,
    '$diffOld' => $diffOld,
    '$dataImport' => $dataImport,
    '$dataOrig' => $dataOrig,
    '$mismatch' => $io->getMismatch(),
]);



/**
 * 資料遞迴比較
 *
 * @example
 * diffRecursive($newData, $oldData);
 *
 * @author Gunter Chou
 * @param array $Comparative
 * @param array $Comparison
 * @return array $outputDiff Result diff data
 */
function diffRecursive($Comparative, $Comparison)
{
    $outputDiff = [];
    foreach ($Comparative as $key => $value) {
        if (array_key_exists($key, $Comparison)) {
            if (is_array($value)) {
                $recursiveDiff = diffRecursive($value, $Comparison[$key]);
                if (!empty($recursiveDiff)) {
                    $outputDiff[$key] = $recursiveDiff;
                }
            } elseif (!in_array($value, $Comparison)) {
                $outputDiff[$key] = $value;
            }
        } elseif (!in_array($value, $Comparison)) {
            $outputDiff[$key] = $value;
        }
    }
    return $outputDiff;
}

/**
 * 回傳json格式
 * @author Gunter Chou
 */
function responseJson(array $data)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
}

/**
 * 回傳html格式
 * @author Gunter Chou
 */
function responseHtml(array $data)
{
    header('Content-Type: text/html; charset=utf-8');
    foreach ($data as $key => $value) {
        $jsonEncodeVal = json_encode($value, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        echo "<pre style=\"float: left;\"> {$key} = {$jsonEncodeVal} </pre>";
    }
}