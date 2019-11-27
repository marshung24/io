匯出匯入模組
===

[![Latest Stable Version](https://poser.pugx.org/marshung/io/v/stable)](https://packagist.org/packages/marshung/io) [![Total Downloads](https://poser.pugx.org/marshung/io/downloads)](https://packagist.org/packages/marshung/io) [![Latest Unstable Version](https://poser.pugx.org/marshung/io/v/unstable)](https://packagist.org/packages/marshung/io) [![License](https://poser.pugx.org/marshung/io/license)](https://packagist.org/packages/marshung/io) [![composer.lock](https://poser.pugx.org/marshung/io/composerlock)](https://packagist.org/packages/marshung/io)

# 目錄
- [說明](#說明)
- [安裝](#安裝)
- [用法](#用法)
  - [有資料的結構定義物件](#有資料的結構定義物件)
  - [空的結構定義物件](#空的結構定義物件)
  - [手動處理-簡易模式](#手動處理-簡易模式)
  - [手動處理-複雜模式](#手動處理-複雜模式)
- [樣式](#樣式)
  - [可用清單](#可用清單)
  - [設定方式](#設定方式)
  - [手動-複雜模式-樣式](#手動-複雜模式-樣式)
  - [樣式後處理](#樣式後處理)
- [多頁工作表](#多頁工作表)


# 說明
為簡化匯出匯入使用法式，編寫此模組

# 安裝
```shell
$ composer require marshung/io
```

# 用法
## 有資料的結構定義物件
需先定義好結構物件，匯出時，直接指定定義好的結構物件即可
> 結構物件參考：
> src/config/ComplexExampleConfig.php
> src/config/EmptyConfig.php
> src/config/SimpleExampleConfig.php

### 匯出
```php
// 取得原始資料
$data = [
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Mars',
	            'id_no' => 'A234567890',
	            'birthday' => '2000-01-01',
	            'gender' => '1',
	        ],
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Jack',
	            'id_no' => 'A123456789',
	            'birthday' => '20001-01-01',
	            'gender' => '1',
	        ]
	    ];
```

```php
// IO物件建構
$io = new \marshung\io\IO();

// 匯出處理 - 建構匯出資料 - 簡易模式結構定義物件-範本
$io->export($data, $config = 'SimpleExample', $builder = 'Excel', $style = 'Io');
```

```php
// IO物件建構
$io = new \marshung\io\IO();

// 匯出處理 - 建構匯出資料 - 複雜模式結構定義物件-範本
$io->export($data, $config = 'ComplexExample', $builder = 'Excel', $style = 'Io');
```

```php
// IO物件建構
$io = new \marshung\io\IO();

// 匯出處理 - 物件注入方式
$config = new \marshung\io\config\SimpleExampleConfig();
// 必要欄位設定 - 提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列，導致在讀取excel時有讀不完狀況。
$config->setOption([
    'u_no'
], 'requiredField');
$builder = new \marshung\io\builder\ExcelBuilder();
$style = new \marshung\io\style\IoStyle();
// 欄位B凍結
$style->setFreeze('B');
$io->export($data, $config, $builder, $style);
```

> 需注意設置"必要欄位設定"，因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列，導致在讀取excel時有讀不完狀況。


### 匯入
```php
// IO物件建構
$io = new \marshung\io\IO();
// 匯入處理 - 取得匯入資料
$data = $io->import($builder = 'Excel', $fileArgu = 'fileupload');
// 取得匯入config名子
$configName = $io->getConfig()->getOption('configName');
// 取得有異常有下拉選單內容
$mismatch = $io->getMismatch();
// $mismatch = $io->getConfig()->getMismatch();

echo 'Config Name = ' . $configName . "<br>\n";
echo 'Data = ';
var_export($data);
echo "\n";
echo 'Exception content = ';
var_export($mismatch);
```
> 當有下拉選單的欄位，其輸入的內容不在下拉選單中，$data中的資料內容會是空字串，但原內容會記錄在$mismatch中

## 空的結構定義物件
如果不需要格式、樣式等設定，只需將資料陣列純輸出，可使用空結構定義

### 匯出
```php
// 取得原始資料
$data = [
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Mars',
	            'id_no' => 'A234567890',
	            'birthday' => '2000-01-01',
	            'gender' => '1',
	        ],
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Jack',
	            'id_no' => 'A123456789',
	            'birthday' => '20001-01-01',
	            'gender' => '1',
	        ]
	    ];

// IO物件建構
$io = new \marshung\io\IO();
// 匯出處理 - 建構匯出資料 - 空的結構定義物件
$io->export($data, $config = 'Empty', $builder = 'Excel', $style = 'Io');
```

### 匯入
```php
// IO物件建構
$io = new \marshung\io\IO();
// 匯入處理 - 取得匯入資料
$data = $io->import($builder = 'Excel', $fileArgu = 'fileupload');
// 取得匯入config名子
$configName = $io->getConfig()->getOption('configName');

echo 'Config Name = ' . $configName . "<br>\n";
echo 'Data = ';
var_export($data);
```

## 手動處理-簡易模式
如果資料欄位為變動長度時，將無法定義完善的結構定義物件，此時可用手動模式

> 當然，此狀況可以定義好可預期的欄位結構，然後出現額外的欄位時，使用$config的getTitle(),getContent()取出資料並改寫，再利用setTitle(),setContent()回寫，並用setList()補充對映表資料即可

### 匯出
```php
// 取得原始資料
$data = [
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Mars',
	            'id_no' => 'A234567890',
	            'birthday' => '2000-01-01',
	            'gender' => '1',
	        ],
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Jack',
	            'id_no' => 'A123456789',
	            'birthday' => '20001-01-01',
	            'gender' => '1',
	        ]
	    ];

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

// 必要欄位設定 - 提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列，導致在讀取excel時有讀不完狀況。
$conf->setOption([
    'u_no'
], 'requiredField');

// 匯出處理 - 建構匯出資料 - 手動處理
$io->setData($data)->exportBuilder();
```

### 匯入
```php
// IO物件建構
$io = new \marshung\io\IO();
// 匯入處理 - 取得匯入資料
$data = $io->import($builder = 'Excel', $fileArgu = 'fileupload');
// 取得匯入config名子
$configName = $io->getConfig()->getOption('configName');

echo 'Config Name = ' . $configName . "<br>\n";
echo 'Data = ';
var_export($data);
```

## 手動處理-複雜模式
如果資料欄位為變動長度時，將無法定義完善的結構定義物件，此時可用手動模式

> 當然，此狀況可以定義好可預期的欄位結構，然後出現額外的欄位時，使用$config的getTitle(),getContent()取出資料並改寫，再利用setTitle(),setContent()回寫，並用setList()補充對映表資料即可

### 匯出
```php
// 取得原始資料
$data = [
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Mars',
	            'id_no' => 'A234567890',
	            'birthday' => '2000-01-01',
	            'gender' => '1',
	        ],
	        [
	            'u_no' => 'A001',
	            'c_name' => 'Jack',
	            'id_no' => 'A123456789',
	            'birthday' => '20001-01-01',
	            'gender' => '1',
	        ]
	    ];

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
            'key' => 't4',
            'value' => '性別',
            'col' => '2',
            'row' => '1',
            'style' => array(),
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
            'key' => 't4',
            'value' => '男',
            'col' => '2',
            'row' => '1',
            'style' => array(),
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

// 必要欄位設定 - 提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列，導致在讀取excel時有讀不完狀況。
$conf->setOption([
    'u_no'
], 'requiredField');

// 匯出處理 - 建構匯出資料 - 手動處理
$io->setData($data)->exportBuilder();
```

### 匯入
```php
// IO物件建構
$io = new \marshung\io\IO();
// 匯入處理 - 取得匯入資料
$data = $io->import($builder = 'Excel', $fileArgu = 'fileupload');
// 取得匯入config名子
$configName = $io->getConfig()->getOption('configName');
// 取得有異常有下拉選單內容
$mismatch = $io->getMismatch();
// $mismatch = $io->getConfig()->getMismatch();

echo 'Config Name = ' . $configName . "<br>\n";
echo 'Data = ';
var_export($data);
echo "\n";
echo 'Exception content = ';
var_export($mismatch);
```
> 當有下拉選單的欄位，其輸入的內容不在下拉選單中，$data中的資料內容會是空字串，但原內容會記錄在$mismatch中


# 樣式
## 可用清單
### 顏色
| 代碼 | 色碼 | 顏色
|:----:|:---:|:----:|
| black | FF000000 | 黑色
| blue | FF0000FF | 藍色
| darkblue | FF000080 | 深藍
| darkgreen | FF008000 | 深綠
| darkred | FF800000 | 深紅
| darkyellow | FF808000 | 深黃
| green | FF00FF00 | 綠
| red | FFFF0000 | 紅
| white | FFFFFFFF | 白
| yellow | FFFFFF00 | 黃

### 水平對齊
| 代碼 | 方式
|:----:|:---:|
| center | 置中
| centercontinuous | |
| general | 一般
| justify | 左右貼齊
| left | 靠左
| right | 靠右


### 垂直對齊
| 代碼 | 方式
|:----:|:---:|
| bottom | 置底
| center | 置中
| middle | 置中
| justify | |
| top | 置頂

### 邊線
| 代碼 | 說明
|:----:|:---:|
| dashdot | |
| dashdotdot | |
| dashed | |
| dotted | |
| double | |
| hair | |
| medium | |
| mediumdashdot | |
| mediumdashdotdot | |
| mediumdashed | |
| none | |
| slantdashdot | |
| thick | |
| thin | |


### 儲存格格式
| 代碼 | 說明
|:----:|:---:|
| general | 通用格式 |
| txt | 文字 |
| text | 文字 |
| string | 文字 |
| number | 數字 |
| number_00 | 數字(小數二位) |
| date | 日期 |
| time | 時間 |
| datetime | 日期+時間 |

## 設定方式
### 設定檔
- 自定樣式檔
```php
class MyStyle extends marshung\io\style\IoStyle
{
    public function __construct()
    {
        parent::__construct();
        // 初始化 - 預設樣式
        $this->run();
    }

     /**
     * 初始化 - 預設樣式
     * 'width' => 20.71,//儲存格欄寬
     * 'height' => -1,//儲存格欄高(-1為自動高度)
     * 'wraptext' => true,//儲存格自動換行
     * 'font-name' => '微軟正黑體',//字體字型
     * 'font-size' => 11,//字體大小
     * 'font-bold' => false,//字體粗體
     * 'font-underline' => false,//字體底線
     * 'font-color' => 'black',//字體顏色
     * 'align-horizontal' => 'left',//水平對齊
     * 'align-vertical' => 'center',//垂直對齊
     * 'border-all-style' => 'thin',//欄線樣式-全部
     * 'border-all-color' => 'FF9F9FA0',//欄線顏色-全部
     * 'border-top-style' => 'thin',//上欄線樣式
     * 'border-left-style' => 'thin',//左欄線樣式
     * 'border-right-style' => 'thin',//右欄線樣式
     * 'border-bottom-style' => 'thin',//下欄線樣式
     * 'border-outline-style' => 'thin',//外圈線樣式
     * 'border-inside-style' => 'thin',//內部線樣式
     * 'border-top-color' => 'FFDADCDD',//上欄線顏色
     * 'border-left-color' => 'FFDADCDD',//左欄線顏色
     * 'border-right-color' => 'FFDADCDD',//右欄線顏色
     * 'border-bottom-color' => 'FFDADCDD',//下欄線顏色
     * 'border-outline-color' => 'FFDADCDD',//外圈欄線顏色
     * 'border-inside-color' => 'FFDADCDD',//內部欄線顏色
     * 'background-color' => 'white'//儲存格背景顏色
     * 'row-odd-background-color' => 'F7F7F7',//內容奇數列背景顏色
     * 'row-even-background-color' => 'white'//內容偶數列背景顏色
     */
    protected function initDefault()
    {
        // 自定樣式集
        $this->_classMap['mystyle'] = array(
            'width' => 20.71, // 儲存格欄寬
            'height' => - 1, // 儲存格欄高(-1為自動高度)
            'format' => 'text', // 儲存格格式-文字
            'wraptext' => true, // 儲存格自動換行
            'font-name' => '微軟正黑體', // 字體字型
            'font-size' => 11, // 字體大小
            'font-color' => 'black', // 字體顏色
            'align-vertical' => 'center', // 垂直對齊
            'border-all-style' => 'thin', // 欄線樣式-全部
            'border-all-color' => 'FF9F9FA0' // 欄線顏色-全部
        );
    }
}
```

- 設定結構構設定檔 ocnfig (節錄)
如標題要用自定樣式集

```php
$this->_title[] = array(
    'config' => array(
        'type' => 'title',
        'name' => 'title1',
        // 自定樣式
        'style' => array(
            'font-size' => '16'
        ),
        // 樣式集名稱
        'class' => 'mystyle'
    ),
    'defined' => array(
        't1' => array(
            'key' => 't1',
            'value' => '日期',
            'col' => '1',
            'row' => '1',
            // 自定樣式
            'style' => array('format' => 'date'),
            'class' => '',
            'default' => '',
            'list' => ''
        ),
);

```

### 手動-複雜模式-樣式
```php
$style = new \marshung\io\style\IoStyle();
$style->setClass(array(
            'width' => 20.71, // 儲存格欄寬
            'height' => - 1, // 儲存格欄高(-1為自動高度)
            'format' => 'text', // 儲存格格式-文字
            'wraptext' => true, // 儲存格自動換行
            'font-name' => '微軟正黑體', // 字體字型
            'font-size' => 11, // 字體大小
            'font-color' => 'black', // 字體顏色
            'align-vertical' => 'center', // 垂直對齊
            'border-all-style' => 'thin', // 欄線樣式-全部
            'border-all-color' => 'FF9F9FA0' // 欄線顏色-全部
        ), 'mystyle');

// 標題1
$title1 = array(
    'config' => array(
        'type' => 'title',
        'name' => 'title1',
        // 自定樣式
        'style' => array(
            'font-size' => '16'
        ),
        // 樣式集名稱
        'class' => 'mystyle'
    ),
    'defined' => array(
        't1' => array(
            'key' => 't1',
            'value' => '日期',
            'col' => '1',
            'row' => '1',
            // 自定樣式
            'style' => array('format' => 'date'),
            'class' => '',
            'default' => '',
            'list' => ''
        ),
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

// 略，參照： 手動 - 複雜模式

```

### 樣式後處理
為因應過於特殊的樣式設定，開放樣式後處理

#### 自動模式-樣式後處理
```php
// 取得原始資料
$data = getData('3');

// IO物件建構
$io = new \marshung\io\IO();

// 匯出處理 - 物件注入方式
$config = new \marshung\io\config\ComplexExampleConfig();
$builder = new \marshung\io\builder\ExcelBuilder();
$style = new \marshung\io\style\IoStyle();

// Output format: file, phpSpreadsheet(src/object/sheet/spreadsheet/phpspreadsheet)
$builder->setOption('object', 'outputFormat');

// 建構輸出物件
$spreadsheet = $io->export($data, $config, $builder, $style);

// 自定樣式 - style後處理
$titleStyle = ['background-color' => 'FF0094D8'];
$titleRange = 'A4:C4';
\marshung\io\builder\ExcelStyleBuilder::setExcelRangeStyle($titleStyle, $spreadsheet, $titleRange);

// 重新輸出
$builder->output('my_file_name', 'file');
```


#### 手動模式-樣式後處理
```php
// 取得原始資料
$data = getData('7');

// 結構定義-簡易模式
$defined = array('....');

// IO物件建構
$io = new \marshung\io\IO();

// 手動建構相關物件
$io->setConfig()->setBuilder()->setStyle();

// 載入外部定義
$conf = $io->getConfig()->setTitle($defined)->setContent($defined);

// 建構外部對映表
$listMap = array('...');

// 取得建構物件
$builder = $io->getBuilder();
// Output format: file, phpSpreadsheet(src/object/sheet/spreadsheet/phpspreadsheet)
$builder->setOption('object', 'outputFormat');

// 載入外部對映表
$conf->setList($listMap);

// 匯出處理 - 建構匯出資料
$spreadsheet = $io->setData($data)->exportBuilder();

// 自定樣式 - style後處理
$titleStyle = ['background-color' => 'FF0094D8'];
$titleRange = 'B4:D4';
\marshung\io\builder\ExcelStyleBuilder::setExcelRangeStyle($titleStyle, $spreadsheet, $titleRange);

// 重新輸出
$builder->output('export7', 'file');
```


# 多頁工作表
```php
// 取得原始資料
$data = getData('10');
$deptData = getDeptData('10');

// 匯出處理 - 物件注入方式
$config1 = new \marshung\io\config\ComplexExampleConfig();
$config2 = new \marshung\io\config\ComplexExampleDeptConfig();
$builder = new \marshung\io\builder\ExcelBuilder();
$style = new \marshung\io\style\IoStyle();

// 欄位B凍結
$style->setFreeze('B');

// 手動建構相關物件
$spreadsheet = $builder->setData($data)
    ->setConfig($config1)
    ->setStyle($style)
    ->build()
    ->setConfig($config2)
    ->setData($deptData)
    ->build()
    ->output('export-10', 'file');
```



















