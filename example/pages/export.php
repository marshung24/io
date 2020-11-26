<?php

include_once('data.php');


/**
 * example for export
 */

/**
 * 匯出 - 有資料的結構定義物件(簡易模式結構定義物件-範本)
 */
function export1()
{
    // 取得原始資料
    $data = getData('1');
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-01', 'fileName');
    
    // 匯出處理 - 建構匯出資料 - 簡易模式結構定義物件-範本
    $io->export($data, $config = 'SimpleExample', $builder = 'Excel', $style = 'Io');
}

/**
 * 匯出 - 有資料的結構定義物件(複雜模式結構定義物件-範本)
 */
function export2()
{
    // 取得原始資料
    $data = getData('12');
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-02', 'fileName');
    
    
    // 匯出處理 - 建構匯出資料 - 複雜模式結構定義物件-範本
    $io->export($data, $config = 'ComplexExample', $builder = 'Excel', $style = 'Io');
}

/**
 * 匯出 - 有資料的結構定義物件(物件注入方式)
 */
function export3()
{
    // 取得原始資料
    $data = getData('3');
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-03', 'fileName');
    
    // 匯出處理 - 物件注入方式
    $config = new \marshung\io\config\SimpleExampleConfig();
    // 必要欄位設定 - 提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列
    $config->setOption([
        'u_no'
    ], 'requiredField');
    $builder = new \marshung\io\builder\ExcelBuilder();
    $style = new \marshung\io\style\IoStyle();
    // 欄位B凍結
    $style->setFreeze('B');
    $io->export($data, $config, $builder, $style);
}

/**
 * 匯出 - 空的結構定義物件
 */
function export4()
{
    // 取得原始資料
    $data = getData('4');
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-04', 'fileName');
    
    // 匯出處理 - 建構匯出資料 - 空的結構定義物件
    $io->export($data, $config = 'Empty', $builder = 'Excel', $style = 'Io');
}

/**
 * 匯出 - 手動處理 - 簡易模式
 */
function export5()
{
    // 取得原始資料
    $data = getData('5');
    
    // 結構定義-簡易模式
    $defined = array(
        'u_no' => '員工編號',
        'c_name' => '姓名',
        'id_no' => '身分證字號',
        'birthday' => '出生年月日',
        'gender' => '性別'
    );
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-05', 'fileName');
    
    // 手動建構相關物件
    $io->setConfig()
    ->setBuilder()
    ->setStyle();
    
    // 載入外部定義
    $conf = $io->getConfig()
    ->setTitle($defined)
    ->setContent($defined);
    
    // 建構外部對映表
    $listMap = array(
        'gender' => array(
            array(
                'value' => '1',
                'text' => '男'
            ),
            array(
                'value' => '0',
                'text' => '女'
            )
        )
    );
    
    // 載入外部對映表
    $conf->setList($listMap);
    
    // 匯出處理 - 建構匯出資料 - 手動處理
    $io->setData($data)->exportBuilder();
}

/**
 * 匯出 - 手動處理 - 複雜模式
 */
function export6()
{
    // 取得原始資料
    $data = getData('6');
    
    // 結構定義-複雜模式
    // 標題1
    $title1 = array(
        'config' => array(
            'type' => 'title',
            'name' => 'title1',
            'style' => array(
                'font-size' => '16'
            ),
            'class' => ''
        ),
        'defined' => array(
            't1' => array(
                'key' => 't1',
                'value' => '帳號',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't2' => array(
                'key' => 't2',
                'value' => '姓名',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't3' => array(
                'key' => 't3',
                'value' => '身分證字號',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't4' => array(
                'key' => 't4',
                'value' => '生日',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't5' => array(
                'key' => 't5',
                'value' => '性別',
                'col' => '2',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't6' => array(
                'key' => 't6',
                'value' => '備註',
                'col' => '1',
                'row' => '1',
                'style' => array('format' => '0.0_ ;[Red]\-0.0\ '),
                'class' => '',
                'default' => '',
                'list' => ''
            )
        )
    );
    
    // 標題2
    $title2 = array(
        'config' => array(
            'type' => 'title',
            'name' => 'example',
            'style' => array(),
            'class' => 'example'
        ),
        'defined' => array(
            't1' => array(
                'key' => 't1',
                'value' => 'A001',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't2' => array(
                'key' => 't2',
                'value' => '派大星',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't3' => array(
                'key' => 't3',
                'value' => 'ET9000001',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't4' => array(
                'key' => 't4',
                'value' => '2000-01-01',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't5' => array(
                'key' => 't5',
                'value' => '男',
                'col' => '2',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            't6' => array(
                'key' => 't6',
                'value' => '備註',
                'col' => '1',
                'row' => '1',
                'style' => array('format' => '0.0_ ;[Red]\-0.0\ '),
                'class' => '',
                'default' => '',
                'list' => ''
            )
        )
    );
    
    // 內容
    $content = array(
        'config' => array(
            'type' => 'content',
            'name' => 'content',
            'style' => array(),
            'class' => ''
        ),
        'defined' => array(
            'u_no' => array(
                'key' => 'u_no',
                'value' => '帳號',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            'c_name' => array(
                'key' => 'c_name',
                'value' => '姓名',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            'id_no' => array(
                'key' => 'id_no',
                'value' => '身分證字號',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            'birthday' => array(
                'key' => 'birthday',
                'value' => '生日',
                'col' => '1',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '',
                'list' => ''
            ),
            'gender' => array(
                'key' => 'gender',
                'value' => '性別',
                'col' => '2',
                'row' => '1',
                'style' => array(),
                'class' => '',
                'default' => '1',
                'list' => ''
            ),
            'text' => array(
                'key' => 'text',
                'value' => '備註',
                'col' => '1',
                'row' => '1',
                'style' => array('format' => '0.0_ ;[Red]\-0.0\ '),
                'class' => '',
                'default' => '1',
                'list' => ''
            )
        )
    );
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-06', 'fileName');
    
    // 手動建構相關物件
    $io->setConfig()
    ->setBuilder()
    ->setStyle();
    
    // 載入外部定義
    $conf = $io->getConfig()
    ->setTitle($title1)
    ->setTitle($title2)
    ->setContent($content);
    
    // 建構外部對映表
    $listMap = array(
        'gender' => array(
            array(
                'value' => '1',
                'text' => '男'
            ),
            array(
                'value' => '0',
                'text' => '女'
            )
        )
    );
    
    // 載入外部對映表
    $conf->setList($listMap);
    
    // 匯出處理 - 建構匯出資料 - 手動處理
    $io->setData($data)->exportBuilder();
}

/**
 * 匯出 - 手動處理 - 簡易模式 - 數字key + 回傳spreadsheet做style後處理
 */
function export7()
{
    // 取得原始資料
    $data = getData('7');
    foreach ($data as & $d) {
        $d = array_values($d);
    }
    
    // 結構定義-簡易模式
    $defined = array(
        '員工編號',
        '姓名',
        '身分證字號',
        '出生年月日',
        '性別',
        '備註'
    );
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-07', 'fileName');
    
    // 手動建構相關物件
    $io->setConfig()
    ->setBuilder()
    ->setStyle();
    
    // 載入外部定義
    $conf = $io->getConfig()
    ->setTitle($defined)
    ->setContent($defined);
    
    // 建構外部對映表
    $listMap = array(
        'gender' => array(
            array(
                'value' => '1',
                'text' => '男'
            ),
            array(
                'value' => '0',
                'text' => '女'
            )
        )
    );
    
    $builder = $io->getBuilder();
    // Output format: builder, file, phpSpreadsheet(src/object/sheet/spreadsheet/phpspreadsheet)
    $builder->setOption('object', 'outputFormat');
    
    // 載入外部對映表
    $conf->setList($listMap);
    
    // 匯出處理 - 建構匯出資料 - 手動處理 + 回傳spreadsheet做style後處理)
    $spreadsheet = $io->setData($data)->exportBuilder();
    
    // 自定樣式 - style後處理
    $titleStyle = ['background-color' => 'FF0094D8'];
    $titleRange = 'B4:D4';
    \marshung\io\builder\ExcelStyleBuilder::setExcelRangeStyle($titleStyle, $spreadsheet, $titleRange);
    
    // 輸出
    $builder->output('export-07_output', 'file');
}

/**
 * 匯出 - 有資料的結構定義物件(複雜模式結構定義物件-範本)
 */
function export8()
{
    // 取得原始資料
    $data = getData('8');
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-08', 'fileName');
    
    // 匯出處理 - 物件注入方式
    $config = new \marshung\io\config\ComplexExampleConfig();
    $builder = new \marshung\io\builder\ExcelBuilder();
    $style = new \marshung\io\style\IoStyle();
    
    // 測試工作表名稱過濾
    $config->setOption('[測試工作表名稱過濾]測試工作表名稱過濾測試工作表名稱過濾測試工作表名稱過濾測試工作表名稱過濾測試工作表名稱過濾', 'sheetName');
    
    // 必要欄位設定 - 提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列
    $config->setOption([
        'u_no'
    ], 'requiredField');
    // Output format: builder, file, phpSpreadsheet(src/object/sheet/spreadsheet/phpspreadsheet)
    $builder->setOption('object', 'outputFormat');
    
    // 欄位B凍結
    $style->setFreeze('B');
    $spreadsheet = $io->export($data, $config, $builder, $style);
    
    // 自定樣式 - style後處理
    $titleStyle = ['background-color' => 'FF0094D8'];
    $titleRange = 'A4:C4';
    \marshung\io\builder\ExcelStyleBuilder::setExcelRangeStyle($titleStyle, $spreadsheet, $titleRange);
    
    // 輸出
    $builder->output('', 'file');
}

/**
 * 匯出 - 有資料的結構定義物件(複雜模式結構定義物件-範本)
 */
function export9()
{
    // 取得原始資料
    $data = null;
    
    // IO物件建構
    $io = new \marshung\io\IO();
    $io->setOption('export-09', 'fileName');
    
    // 匯出處理 - 物件注入方式
    $config = new \marshung\io\config\ComplexExampleConfig();
    $builder = new \marshung\io\builder\ExcelBuilder();
    $style = new \marshung\io\style\IoStyle();
    
    // 測試工作表名稱過濾
    $config->setOption('[測試工作表名稱過濾]測試工作表名稱過濾測試工作表名稱過濾測試工作表名稱過濾測試工作表名稱過濾測試工作表名稱過濾', 'sheetName');
    
    // 必要欄位設定 - 提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列
    $config->setOption([
        'u_no'
    ], 'requiredField');
    // Output format: builder, file, phpSpreadsheet(src/object/sheet/spreadsheet/phpspreadsheet)
    $builder->setOption('object', 'outputFormat');
    
    // 欄位B凍結
    $style->setFreeze('B');
    $spreadsheet = $io->export($data, $config, $builder, $style);
    
    // 自定樣式 - style後處理
    $titleStyle = ['background-color' => 'FF0094D8'];
    $titleRange = 'A3:C3';
    \marshung\io\builder\ExcelStyleBuilder::setExcelRangeStyle($titleStyle, $spreadsheet, $titleRange);
    
    // 輸出
    $builder->output('export9', 'file');
}

/**
 * 匯出 - 二頁-複雜模式
 */
function export10()
{
    // 取得原始資料
    $data = getData('10');
    $deptData = getDeptData('10');
    
    // 匯出處理 - 物件注入方式
    $config1 = new \marshung\io\config\ComplexExampleConfig();
    $config2 = new \marshung\io\config\ComplexExampleDeptConfig();
    $builder = new \marshung\io\builder\ExcelBuilder();
    $style = new \marshung\io\style\IoStyle();
    
//     // 必要欄位設定 - 提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列
//     $config1->setOption([
//         'u_no'
//     ], 'requiredField');
//     // Output format: builder, file, phpSpreadsheet(src/object/sheet/spreadsheet/phpspreadsheet)
//     $builder->setOption('object', 'outputFormat');
//     // 欄位B凍結
//     $style->setFreeze('B');
    
    // 手動建構相關物件
    $spreadsheet = $builder->setData($data)
        ->setConfig($config1)
        ->setStyle($style)
        ->build()
        ->setConfig($config2)
        ->setData($deptData)
        ->build()
        ->output('', 'src');
    
//     // 自定樣式 - style後處理
//     $titleStyle = [
//         'background-color' => 'FF0094D8'
//     ];
//     $titleRange = 'A3:C3';
//     $spreadsheet->setActiveSheetIndex(1);
//     \marshung\io\builder\ExcelStyleBuilder::setExcelRangeStyle($titleStyle, $spreadsheet, $titleRange);
    
    // 輸出
    $builder->output('export-10', 'file');
}

/**
 * 匯出 - 沒有ConfigSheet-無法匯入
 */
function export11()
{
    // 取得原始資料
    $data = getData('11');
    
    // 匯出處理 - 物件注入方式
    $config1 = new \marshung\io\config\EmptyConfig();
    $builder = new \marshung\io\builder\ExcelBuilder();
    $style = new \marshung\io\style\IoStyle();
    
    // 手動建構相關物件
    $spreadsheet = $builder->setData($data)
        ->setConfig($config1)
        ->setStyle($style)
        ->build()
        ->output('', 'src');
    
    // 輸出
    $builder->output('export-11', '', 'withoutconfig');
}

