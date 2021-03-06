<?php

namespace marshung\io\config\abstracts;

/**
 * IO Config abstract
 *
 * 規則：
 * 1. title,foot二種資料，一列一筆定義
 * 2. content種類資料，多列一筆定義
 * 3. 如果沒有設定title,foot定義，則不處理該種類資料
 * 4. 如果沒有設定content定義，則傳入資料不做過濾
 * 
 * 舊版相容1，預計20190331移除
 * 
 * @author Mars.Hung (tfaredxj@gmail.com) 2018-04-18
 *        
 */
abstract class Config
{

    /**
     * abstract設定參數
     * 
     * @var array
     */
    private static $_config = [
        // abstract目前版本
        'abstractVersion' => '0.9',
        // abstract最小可版本
        'abstractVersionMini' => '0.1',
    ];


    /**
     * 設定檔參數
     *
     * @var array
     */
    protected $_options = [
        // config目前版本
        'version' => '0.1',
        // config最小可用版本
        'versionMini' => '0.1',
        'configName' => __CLASS__,
        // Sheet Title：1.Excel的SheetTitle最多31個字 2.不可用於Sheet Title的七個字元 \ / * [ ] : ?
        'sheetName' => 'Worksheet',
        // 模式：簡易(simple)、複雜(complex)、待偵測(detect)
        'type' => 'detect',
        // 必要欄位，必需有值，提供讀取資料時驗証用 - 有設定，且必要欄位有無資料者，跳出 - 因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列
        'requiredField' => [],
        // 日期格式
        'dateFormat' => 'Y-m-d',
    ];

    /**
     * 標題定義
     *
     * @var array
     */
    protected $_title = [];

    /**
     * 內容定義
     *
     * @var array
     */
    protected $_content = [];

    /**
     * 結尾定義
     *
     * @var array
     */
    protected $_foot = [];

    /**
     * 對映表儲存表 - 下拉選單用
     *
     * $_listMap['目標鍵名'] = array(array('value' => '數值','text' => '數值名稱'),.....);
     *
     * @var array
     */
    protected $_listMap = [];

    /**
     * 暫存
     *
     * @var array
     */
    protected $_cache = [];

    /**
     * 資料範本 - 鍵值表及預設值
     *
     * 使用函式templateDefined()從內容定義中建構本表
     *
     * @var array
     */
    protected $_dataTemplate = [];

    /**
     * 喂給helper的欄位
     *
     * @var array
     */
    protected $_helperField = [
        'key' => '',
        'value' => '',
        'col' => '1',
        'row' => '1',
        'skip' => '1',
        'dataType' => null,
    ];

    /**
     * Construct
     *
     * @throws Exception
     */
    public function __construct()
    {
        // 初始化
        $this->initialize();
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
    }

    /**
     * 重新初始化
     */
    public function reInitialize()
    {
        $this->initialize();
    }

    /**
     * 初始化
     */
    public function initialize()
    {
        // Sheet name filter
        $this->sheetNameFilter();

        // ====== 初始化定義 ======
        $this->_title = [];
        $this->_content = [];
        $this->_foot = [];
        $this->_dataTemplate = [];

        $this->titleDefined();
        $this->contentDefined();
        $this->footDefined();
        // 必需在$this->contentDefined();之後
        $this->templateDefined();
        // ======

        // ====== 初始化對映表 ======
        $this->_listMap = [];
        $this->listMapInitialize();
        // ======

        // 清空暫存
        $this->_cache = [
            // 下拉選單資料轉換表
            'valueTextMap' => [],
            // 下拉選單資料轉換失敗記錄
            'mismatch' => [],
        ];

        return true;
    }

    /**
     * **********************************************
     * ************** Getting Function **************
     * **********************************************
     */

    /**
     * 取得設定檔參數
     *
     * @return array
     */
    public function getOption($optionName = null)
    {
        $this->_options = array_merge($this->_options, self::$_config);

        if (is_null($optionName)) {
            // 未定鍵名 - 取得全部
            return $this->_options;
        } else {
            // 指定鍵名
            if (!isset($this->_options[$optionName])) {
                throw new \Exception('Donot have option: ' . $optionName . ' !', 404);
            }

            return $this->_options[$optionName];
        }
    }

    /**
     * 取得標題定義
     *
     * @return array
     */
    public function getTitle()
    {
        if (empty($this->_title)) {
            $this->titleDefined();
            // 模式促偵測及複雜模式config type重寫處理
            $this->structureTypeSet('title');
        }

        return $this->_title;
    }

    /**
     * 取得內容定義
     *
     * @return array
     */
    public function getContent()
    {
        if (empty($this->_content)) {
            $this->contentDefined();
            // 模式促偵測及複雜模式config type重寫處理
            $this->structureTypeSet('content');
        }

        return $this->_content;
    }

    /**
     * 取得結尾定義
     *
     * @return array
     */
    public function getFoot()
    {
        if (empty($this->_foot)) {
            $this->footDefined();
            // 模式促偵測及複雜模式config type重寫處理
            $this->structureTypeSet('foot');
        }

        return $this->_foot;
    }

    /**
     * 取得設定格式範本 - 鍵值表及預設值
     *
     * 如需動態設定預設值時，需取出本表修改後回寫
     *
     * 取得：無參數時
     * 設定：有參數時
     *
     * @return array
     */
    public function getConfTemplate()
    {
        if ($this->_options['type'] == 'complex') {
            // 模式：複雜(complex)
            $config = [
                'config' => [
                    'type' => '{種類:title/content/foot}',
                    'name' => '{結構名稱}',
                    'style' => [
                        '{結構自定樣式集，可為空陣列}'
                    ],
                    'class' => '{結構自定樣式名，可為空字串}'
                ],
                'defined' => [
                    '{鍵名}' => [
                        'key' => '{鍵名}',
                        'value' => '{(在結構設定中，此值為該欄位名稱)}',
                        'desc' => '{說明}',
                        'col' => '1',
                        'row' => '1',
                        'style' => [],
                        'class' => '',
                        'default' => '{預設值}',
                        'list' => '{下拉選單名}'
                    ]
                ]
            ];
        } else {
            // 模式：簡易(simple)
            $config = [
                'u_no' => '編號',
                'c_name' => '姓名',
                'id_no' => '身分證字號',
                'birthday' => '出生年月日',
                'u_country' => '國別'
            ];
        }

        return $config;
    }

    /**
     * 取得選單對映範本 - 鍵值表及預設值
     *
     * 如需動態設定預設值時，需取出本表修改後回寫
     *
     * 取得：無參數時
     * 設定：有參數時
     *
     * @return array
     */
    public function getListTemplate()
    {
        return [
            '$key' => [
                'value' => '數值',
                'text' => '數值名稱'
            ]
        ];
    }

    /**
     * 取得資料範本 - 鍵值表及預設值
     *
     * 如需動態設定預設值時，需取出本表修改後回寫
     *
     * 取得：無參數時
     * 設定：有參數時
     *
     * @return array
     */
    public function getDataTemplate()
    {
        if (empty($this->_dataTemplate)) {
            $this->templateDefined();
        }

        return $this->_dataTemplate;
    }

    /**
     * 取得對映表 - 下拉選單:值&文字
     *
     * @param string $key
     *            鍵名，不指定則傳回全部
     * @return array
     */
    public function &getList($key = null)
    {
        if (is_null($key)) {
            // 未定鍵名 - 取得全部
            return $this->_listMap;
        } else {
            // 指定鍵名
            if (!isset($this->_listMap[$key])) {
                throw new \Exception('List Map donot have key: ' . $key . ' !', 404);
            }
            return $this->_listMap[$key];
        }
    }

    /**
     * **********************************************
     * ************** Setting Function **************
     * **********************************************
     */

    /**
     * 設置設定檔參數
     *
     * @param array $option
     *            參數值
     * @param string $optionName
     *            參數名稱
     * @return \marshung\io\config\abstracts\Config
     */
    public function setOption($option, $optionName = null)
    {
        if (is_null($optionName)) {
            $this->_options = $option;
        } else {
            $this->_options[$optionName] = $option;
        }

        // Sheet name filter
        $this->sheetNameFilter();

        return $this;
    }

    /**
     * 設定標題定義
     *
     * 標題可有多個，以陣列儲存
     *
     * @param array $data
     *            格式定義
     * @return \marshung\io\config\abstracts\Config
     */
    public function setTitle($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            $this->_title[] = $data;
        } else {
            // 沒有傳入值時
            $this->titleDefined();
        }
        // 模式促偵測及複雜模式config type重寫處理
        $this->structureTypeSet('title');

        return $this;
    }

    /**
     * 設定內容定義
     *
     * 內容只有一個，直接儲存
     *
     * @param array $data
     *            格式定義
     * @return \marshung\io\config\abstracts\Config
     */
    public function setContent($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            $this->_content = $data;
            // 設定資料範本 - 鍵值表及預設值
            $this->templateDefined();
        } else {
            $this->contentDefined();
            // 設定資料範本 - 鍵值表及預設值
            $this->templateDefined();
        }
        // 模式促偵測及複雜模式config type重寫處理
        $this->structureTypeSet('content');

        return $this;
    }

    /**
     * 設定結尾定義
     *
     * 結尾可有多個，以陣列儲存
     *
     * @param array $data
     *            格式定義
     * @return \marshung\io\config\abstracts\Config
     */
    public function setFoot($data = null)
    {
        if (!is_null($data) && is_array($data)) {
            $this->_foot[] = $data;
        } else {
            $this->footDefined();
        }
        // 模式促偵測及複雜模式config type重寫處理
        $this->structureTypeSet('foot');

        return $this;
    }

    /**
     * 設定資料範本 - 鍵值表及預設值
     *
     * 資料範本無法直接修改，如需動態設定預設值時，請修改內容設定後，再執行本函式
     *
     * @return array
     */
    public function setTemplate()
    {
        $this->templateDefined();

        return $this;
    }

    /**
     * 設定對映表 - 下拉選單:值&文字
     *
     * @param array $mapData
     *            對映表資料
     * @param string $key
     *            鍵名
     * @return \marshung\io\config\abstracts\Config
     */
    public function setList(array $mapData, $key = null)
    {
        if (is_null($key)) {
            $this->_listMap = $mapData;
        } else {
            $this->_listMap[$key] = $mapData;
        }

        return $this;
    }

    /**
     * **********************************************
     * ************** Options Function **************
     * **********************************************
     */

    /**
     * 版本檢查
     *
     * @param string $version
     *            版本編號
     */
    public function checkVersion($version)
    {
        return $this->_options['version'] == $version;
    }

    /**
     * 參數編碼 - 結構定義物件內容
     *
     * 1. 將結構定義物件內容編碼後待存入參數工作表中
     * 2. 因Excel儲存格有資料上限，所以config要分開儲存，然後在讀取時重組(壓縮+切割字串)
     *
     * @return string[]
     */
    public function optionEncode()
    {
        $config = [
            '_options' => $this->getOption(),
            '_title' => $this->_title,
            '_content' => $this->_content,
            '_foot' => $this->_foot,
            '_listMap' => $this->_listMap,
        ];

        $optionEncode = explode("\n", trim(chunk_split(base64_encode(gzdeflate(json_encode($config))), 30000)));
        $optionEncode = array_map('trim', $optionEncode);
        array_unshift($optionEncode, 'ConfigContent');

        return $optionEncode;
    }

    /**
     * 參數解析 - 結構定義物件內容
     *
     * 解析來自參數工作表中讀到的參數 (依序還原Key)
     * 為本設定檔資料時，才回傳解析後的資料，否則回傳false
     * 因Excel儲存格有資料上限，所以config要分開儲存，然後在讀取時重組(壓縮+切割字串)
     *
     * @param array $optionData
     *            參數
     * @return boolean|mixed
     */
    public function optionDecode(array $optionData)
    {
        // 驗証資料
        $opt = false;
        if ($optionData[0] == 'ConfigContent') {
            // 去除無用資料
            array_shift($optionData);

            if (substr($optionData[0], 0, 1) === '{') {
                // 舊版相容1，預計20190331移除
                $opt = $_options = json_decode($optionData[0], 1);
                $_title = json_decode($optionData[1], 1);
                $_content = json_decode($optionData[2], 1);
                $_foot = json_decode($optionData[3], 1);
                $_listMap = json_decode($optionData[4], 1);
            } else {
                // 新版
                // 重組config字串
                $optionEncode = implode('', $optionData);
                $optionData = json_decode(gzinflate(base64_decode($optionEncode)), 1);

                $opt = $_options = $optionData['_options'];
                $_title = $optionData['_title'];
                $_content = $optionData['_content'];
                $_foot = $optionData['_foot'];
                $_listMap = $optionData['_listMap'];
            }

            // 版本支援檢查
            if ($_options['abstractVersion'] < self::$_config['abstractVersionMini']) {
                throw new \Exception('The template version is too old, please re-download the template!', 404001);
            }
            if ($_options['version'] < $this->_options['versionMini']) {
                throw new \Exception('The template version is too old, please re-download the template!', 404002);
            }

            // 回寫設定檔
            $this->_options = $_options;
            $this->_title = $_title;
            $this->_content = $_content;
            $this->_foot = $_foot;
            $this->_listMap = $_listMap;

            // 設定資料範本 - 鍵值表及預設值
            $this->templateDefined();
        }

        return $opt;
    }

    /**
     * ******************************************************
     * ************** Content Process Function **************
     * ******************************************************
     */

    /**
     * 內容整併 - 以資料內容範本為模版合併資料
     *
     * 整併原始資料後，如需要多餘資料執行額外處理，可在處理完後再執行內容過濾 $this->contentFilter($data);
     *
     * @param array $data
     *            原始資料內容
     * @return \marshung\io\config\AddInsConfig
     */
    public function contentRefactor(array &$data)
    {
        // 將現有對映表轉成value=>text格式存入暫存
        $this->value2TextMapBuilder();

        foreach ($data as $key => &$row) {
            $row = (array) $row;

            // 以資料內容範本為模版合併資料 - 為支援numeric key，改用 +
            $row = $row + $this->_dataTemplate;

            // 內容整併處理時執行 - 迴圈內自定步驟
            $this->eachRefactor($key, $row);

            // 執行資料轉換 value <=> text - 單筆資料
            $this->valueTextConv($key, $row);
        }

        return $this;
    }

    /**
     * 內容過濾 - 以資料內容範本為模版過濾多餘資料
     *
     * 將不需要的多餘資料濾除，通常處理$this->contentRefactor($data)整併完的內容
     *
     * @param array $data
     *            原始資料內容
     * @return \marshung\io\config\AddInsConfig
     */
    public function contentFilter(array &$data)
    {
        foreach ($data as $key => &$row) {
            $row = (array) $row;

            // 以資料內容範本為模版過濾多餘資料 - 有設定才過濾
            if (!empty($this->_dataTemplate)) {
                $row = array_intersect_key($row + $this->_dataTemplate, $this->_dataTemplate);
            }
        }

        return $this;
    }

    /**
     * 匯入資料解析
     *
     * 1. 將匯入的資料依資料範本給key，並做資料轉換 text=>value
     * 2. 複雜模式如有欄位合並時，一並處理資料平移
     * 
     * @param array $data
     *            匯入的原始資料
     * @return \marshung\io\config\abstracts\Config
     */
    public function contentParser(array &$data)
    {
        // 將現有對映表轉成text=>value格式存入暫存
        $this->text2ValueMapBuilder();
        // 資料範本資料量、key
        $templateSize = sizeof($this->_dataTemplate);
        $templateKey = array_keys($this->_dataTemplate);

        // 有資料範本時，才解析
        if ($templateSize) {
            // 遍歷資料，並解析
            foreach ($data as $key => &$row) {
                $row = (array) $row;

                // 匯入資料解析 - 複雜模式欄位合併之資料平移處理
                $this->contentDataShift4Complex($row);

                $row = array_slice($row, 0, $templateSize);
                $row = array_combine($templateKey, $row);

                // 執行資料轉換 value <=> text - 單筆資料
                $this->valueTextConv($key, $row);

                // 資料匯入-日期格式轉換
                $this->dateFormatConv($row);

                // issue#13 Trim all data when parsing imported data
                $row = array_map('trim', $row);
            }
        }

        return $this;
    }

    /**
     * 匯入資料驗証 - 必要欄位驗証
     *
     * 使用必要欄位驗証：有設定必要欄位，且該欄位有無資料的狀況，跳出，因各版本excel對空列定義不同，可能編輯過列，就會產生沒有結尾的空列
     *
     * @param array $row
     *            單列資料
     */
    public function contentValidate(array $row)
    {
        // 取得必要欄位
        $requiredField = $this->getOption('requiredField');
        $requiredField = is_array($requiredField) ? $requiredField : [];
        // 資料範本資料量、key
        $templateSize = sizeof($this->_dataTemplate);
        $templateKeyFlip = array_flip(array_keys($this->_dataTemplate));

        $opt = true;

        // 有資料範本資料、設定必要欄位才驗証
        if ($templateSize && !empty($requiredField)) {
            foreach ($requiredField as $keyCode) {
                // 列資料$row有$requiredField指定的欄位，且該欄無資料時，回傳false
                if (isset($templateKeyFlip[$keyCode]) && isset($row[$templateKeyFlip[$keyCode]]) && empty($row[$templateKeyFlip[$keyCode]])) {
                    return false;
                }
            }
        }

        return $opt;
    }

    /**
     * 執行資料轉換 value <=> text - 單筆資料
     *
     * 依照建構的對映表是value => text，還是text =>value決定轉換方向
     * 不管同名(text)問題
     *
     * @param string $key
     *            當次迴圈的Key值
     * @param array $row
     *            當次迴圈的內容
     */
    public function valueTextConv($key, &$row)
    {
        // 遍歷資料，並轉換內容
        foreach ($row as $k => &$v) {
            // 檢查是否需要內容轉換
            if (!isset($this->_cache['valueTextMap'][$k])) {
                continue;
            }

            // 處理資料轉換
            if (isset($this->_cache['valueTextMap'][$k][$v])) {
                // 有符合的資料，資料轉換
                $v =  $this->_cache['valueTextMap'][$k][$v];
            } else {
                // 無符合的資料，記錄並清空
                $this->_cache['mismatch']["$key"][$k] = $v;
                $v =  '';
            }
        }
    }

    /**
     * 資料匯入-日期格式轉換
     * 
     * - 因Excel通用/日期/時間格式的資料讀取有問題，但使用文字模式對使用者使用不便，因此加入解析函式
     * - 如果欄位格式定義為日期格式時，還原日期格式
     * - 條件
     *   - 複雜模式
     *   - 欄位style的format為date
     *   - 暫不解析class內容
     *
     * @param array $row 當次迴圈的內容
     * @return void
     */
    protected function dateFormatConv(&$row)
    {
        // 遍歷資料，並轉換內容
        foreach ($row as $k => &$v) {
            // 取得欄位格式定義 - 複雜模式才有
            $format = $this->_content['defined'][$k]['style']['format'] ?? null;
            // 資料轉換-日期格式，且資料為純數字時
            if ($format === 'date' && \is_numeric($v)) {
                $v = date($this->_options['dateFormat'] ?? 'Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($v));
            }
        }
    }

    /**
     * 取得有異常有下拉選單內容
     *
     * @return array
     */
    public function getMismatch()
    {
        return $this->_cache['mismatch'] ?? [];
    }

    /**
     * **************************************************
     * ************** Map Builder Function **************
     * **************************************************
     */

    /**
     * 將現有對映表轉成value=>text格式存入暫存
     */
    public function value2TextMapBuilder()
    {
        // 初始化暫存
        $this->_cache['valueTextMap'] = [];

        foreach ($this->_listMap as $key => $map) {
            $this->_cache['valueTextMap'][$key] = array_column($map, 'text', 'value');
        }
    }

    /**
     * 將現有對映表轉成text=>value格式存入暫存
     */
    public function text2ValueMapBuilder()
    {
        // 初始化暫存
        $this->_cache['valueTextMap'] = [];

        foreach ($this->_listMap as $key => $map) {
            $this->_cache['valueTextMap'][$key] = array_column($map, 'value', 'text');
        }
    }

    /**
     * **********************************************
     * ************** Defined Function **************
     * **********************************************
     */

    /**
     * 資料範本 - 鍵值表及預設值
     *
     * 從內容定義撈取
     */
    protected function templateDefined()
    {
        $content = $this->getContent();
        $defined = isset($content['defined']) ? $content['defined'] : (array) $content;
        $template = [];

        foreach ($defined as $key => $info) {
            if (is_array($info)) {
                // 模式：複雜
                $template[$key] = isset($info['default']) ? $info['default'] : '';
            } else {
                // 模式：簡易
                $template[$key] = $info;
            }
        }

        $this->_dataTemplate = $template;
    }

    /**
     * 設定資料過濾，在喂給helper時不會有多餘的資料
     *
     * @param array $defined            
     */
    public function definedFilter(&$defined)
    {
        if (isset($defined['defined']) && is_array($defined['defined'])) {
            // 模式：複雜(complex)
            $def = &$defined['defined'];

            foreach ($def as $key => $info) {
                $def[$key] = array_intersect_key(array_merge($this->_helperField, $info), $this->_helperField);
            }
        }

        return $defined;
    }

    /**
     * 從定義資料中取得列定義
     *
     * 有$defined['defined']為複雜(complex)模式，否則為簡易(simple)模式
     *
     * @param array $defined            
     * @return array
     */
    public function getRowFromDefined($defined)
    {
        return isset($defined['defined']) && is_array($defined['defined']) ? $defined['defined'] : $defined;
    }

    /**
     * 模式促偵測及複雜模式config type重寫處理
     *
     * 複雜模式中的$config['config']['type']值是固定的，為防止使用者設定錯誤，取值時覆寫成固定值
     *
     * @param string $type
     *            模式 title,content,foot
     */
    protected function structureTypeSet($type)
    {
        // 自動偵測設定模式
        $this->configTypeDetect();

        // 複雜模式才處理
        if ($this->_options['type'] != 'complex') {
            return false;
        }

        switch ($type) {
            case 'title':
                // 標題
                foreach ($this->_title as &$row) {
                    $row['config']['type'] = $type;
                }
                break;
            case 'content':
                // 內容
                $this->_content['config']['type'] = $type;
                break;
            case 'foot':
                // 結尾
                foreach ($this->_foot as &$row) {
                    $row['config']['type'] = $type;
                }
                break;
        }
    }

    /**
     * 自動偵測設定模式
     */
    protected function configTypeDetect()
    {
        // 待偵測時處理
        if ($this->_options['type'] == 'detect') {
            // 取得樣本
            $sample = [];
            if (sizeof($this->_title) > 0) {
                $sample = $this->_title[0];
            } elseif (sizeof($this->_content) > 0) {
                $sample = $this->_content;
            } elseif (sizeof($this->_foot) > 0) {
                $sample = $this->_foot[0];
            }

            // 模式偵測
            if (!empty($sample)) {
                $this->_options['type'] = isset($sample['defined']) && is_array($sample['defined']) ? 'complex' : 'simple';
            }
        }
    }

    /**
     * 匯入資料解析 - 複雜模式欄位合併之資料平移處理
     *
     * @param array $row            
     */
    protected function contentDataShift4Complex(&$row)
    {
        if ($this->_options['type'] == 'complex') {
            $isShift = false;
            $cellCount = 0;
            // 遍歷欄位定義，檢查欄位合併
            foreach ($this->_content['defined'] as $key => $cell) {
                $cw = isset($cell['col']) ? (int)$cell['col'] : 1;
                // 有多欄位合併，處理空欄位
                if ($cw > 1) {
                    $isShift = true;
                    for ($i = $cellCount + 1; $i < $cellCount + $cw; $i++) {
                        unset($row[$i]);
                    }
                }

                // 下一個位址
                $cellCount += $cw;
            }
            // 重整索引
            if ($isShift) {
                $row = array_values($row);
            }
        }
    }

    /**
     * Sheet name filter
     */
    protected function sheetNameFilter()
    {
        // Sheet Title處理：1.Excel的SheetTitle最多31個字 2.移除不可用於Sheet Title的七個字元 \ / * [ ] : ?
        $this->_options['sheetName'] = rtrim(mb_substr(trim(preg_replace('|[\\\/\*\]\[\:\?]|', '', $this->_options['sheetName'])), 0, 31));
    }

    /**
     * **********************************************
     * ************** Abstract Function **************
     * **********************************************
     */

    /**
     * 內容整併處理時執行 - 迴圈內自定步驟
     *
     * 對內容整併時，需額外處理的欄位，不建議使用，應在原始資料傳入時就做好
     *
     * @param string $key
     *            當次迴圈的Key值
     * @param array $row
     *            當次迴圈的內容
     */
    protected abstract function eachRefactor($key, &$row);

    /**
     * 初始化對映表
     */
    protected abstract function listMapInitialize();

    /**
     * 標題定義函式範例
     *
     * // 結構一 - 標題1
     * $this->_title[] = array(
     * 'config' => array(
     * 'type' => 'title',
     * 'name' => 'title1',
     * 'style' => array(
     * 'font-size' => '16'
     * ),
     * 'class' => 'title1'
     * ),
     * 'defined' => array(
     * 't1' => array(
     * 'key' => 't1',
     * 'value' => get_language('id'), //'員工編號',
     * 'col' => '1',
     * 'row' => '1',
     * 'style' => [],
     * 'class' => '',
     * 'default' => '',
     * 'list' => ''
     * ),
     * 't2' => array(
     * 'key' => 't2',
     * 'value' => get_language('name'), //'姓名',
     * 'col' => '1',
     * 'row' => '1',
     * 'style' => [],
     * 'class' => '',
     * 'default' => '',
     * 'list' => ''
     * )
     * )
     * );
     *
     * 結構二 - 標題1
     * $this->_title[] = array(
     * 'config' => array(
     * 'type' => '結構名稱',
     * 'name' => '結構名稱',
     * 'style' => array('結構自定樣式集'),
     * 'class' => '結構自定樣式名',
     * ),
     * 'defined' => array(
     * 'u_no' => '編號',
     * 'c_name' => '姓名',
     * 'id_no' => '身分證字號',
     * 'birthday' => '出生年月日',
     * 'u_country' => '國別',
     * :
     * :
     * )
     * );
     *
     * 單一標題定義可擁有單列資料，所以可定義多個標題定義
     */
    protected abstract function titleDefined();

    /**
     * 內容定義函式
     *
     * 單一內容定義可擁有多列資料，所以只能有一個內容定義
     */
    protected abstract function contentDefined();

    /**
     * 結尾定義函式
     *
     * 單一結尾定義可擁有單列資料，所以可定義多個結尾定義
     */
    protected abstract function footDefined();
}
