<?php

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
 * 匯出 - 手動處理 - 簡易模式 - 數字key
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
 * *************** Data ***************
 */

function getData($number = '1')
{
    // 取得原始資料
    return $data = [
        [
            'u_no' => 'export'.$number.'001',
            'c_name' => 'Mars',
            'id_no' => 'A234567890',
            'birthday' => '2000-01-01',
            'gender' => '1',
            'text' => '11',
        ],
        [
            'u_no' => 'export'.$number.'002',
            'c_name' => 'Jack',
            'id_no' => 'A123456751',
            'birthday' => null,
            'gender' => '1',
            'text' => '1',
        ],
        [
            'u_no' => 'export'.$number.'003',
            'c_name' => 'Marry',
            'id_no' => 'A223456789',
            'birthday' => '2003-05-06',
            'gender' => '0',
            'text' => '-11',
        ],
        [
            'u_no' => 'export'.$number.'004',
            'c_name' => 'Joe',
            'id_no' => 'A123456743',
            'birthday' => null,
            'gender' => '1',
            'text' => '0',
        ],
        [
            'u_no' => 'export'.$number.'005',
            'c_name' => 'Ann',
            'id_no' => 'A223434252',
            'birthday' => null,
            'gender' => '0',
            'text' => -111,
        ],
        [
            'u_no' => 'export'.$number.'006',
            'c_name' => 'Judy',
            'id_no' => 'A223467893',
            'birthday' => '2006-08-22',
            'gender' => '0',
            'text' => 111,
        ]
    ];
}
