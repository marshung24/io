<?php

namespace marshung\io\config;

/**
 * 複雜模式-範本
 *
 * 單一工作表版本
 *
 * @author Mars.Hung (tfaredxj@gmail.com) 2018-06-11
 *        
 */
class ComplexExampleDeptConfig extends \marshung\io\config\abstracts\Config
{

    /**
     * Construct
     *
     * @throws Exception
     */
    public function __construct()
    {
        // 設定檔版號
        $this->_options['version'] = '0.1';
        // 設定可用最小版號
        $this->_options['versionMini'] = '0.1';
        // 設定檔名稱
        $this->_options['configName'] = preg_replace('|Config$|', '', str_replace(array(
            __NAMESPACE__,
            '\\'
        ), '', __CLASS__));
        // 工作表名稱
        $this->_options['sheetName'] = '部門資料';
        // 模式：簡易(simple)、複雜(complex)
        $this->_options['type'] = 'complex';
        $this->_options['requiredField'] = array('d_code');

        // 初始化
        $this->initialize();
    }

    /**
     * ******************************************************
     * ************** Content Process Function **************
     * ******************************************************
     */

    /**
     * 內容整併處理時執行 - 迴圈內自定步驟
     *
     * @param string $key
     *            當次迴圈的Key值
     * @param array $row
     *            當次迴圈的內容
     */
    protected function eachRefactor($key, &$row)
    {
    }

    /**
     * **************************************************
     * ************** Map Builder Function **************
     * **************************************************
     */

    /**
     * 初始化對映表
     */
    protected function listMapInitialize()
    {
        // 對映表建構 - 層級 - level
        $this->levelMapBuilder();
    }

    /**
     * 對映表建構 - 性別 - gender
     */
    protected function levelMapBuilder()
    {
        // 寫入對映表
        $this->_listMap['d_level'] = array(
            array(
                'value' => '1',
                'text' => '組'
            ),
            array(
                'value' => '2',
                'text' => '課'
            ),
            array(
                'value' => '3',
                'text' => '部'
            ),
            array(
                'value' => '4',
                'text' => '處'
            )
        );
    }

    /**
     * **********************************************
     * ************** Defined Function **************
     * **********************************************
     */

    /**
     * 標題定義函式
     *
     * 單一標題定義可擁有單列資料，所以可定義多個標題定義
     */
    protected function titleDefined()
    {
        // 標題1
        $this->_title[] = array(
            'config' => array(
                // 標題的type必為title
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
                    'value' => '部門編號',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                't2' => array(
                    'key' => 't2',
                    'value' => '部門名稱',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                't3' => array(
                    'key' => '層級',
                    'value' => '',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                't4' => array(
                    'key' => 't4',
                    'value' => '主管',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                )
            )
        );

        // 標題2
        $this->_title[] = array(
            'config' => array(
                // 標題的type必為title
                'type' => 'title',
                'name' => 'example',
                'style' => array(),
                'class' => 'example'
            ),
            'defined' => array(
                't1' => array(
                    'key' => 't1',
                    'value' => 'RD',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                't2' => array(
                    'key' => 't2',
                    'value' => '開發部',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                't3' => array(
                    'key' => 't3',
                    'value' => '部',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                't4' => array(
                    'key' => 't4',
                    'value' => 'Manager.Rd',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                )
            )
        );
    }

    /**
     * 內容定義函式
     *
     * 單一內容定義可擁有多列資料，所以只能有一個內容定義
     */
    protected function contentDefined()
    {
        // 內容
        $this->_content = array(
            'config' => array(
                // 內容的type必為content
                'type' => 'content',
                'name' => 'content',
                'style' => array(),
                'class' => ''
            ),
            'defined' => array(
                'd_code' => array(
                    'key' => 'd_code',
                    'value' => '',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                'd_name' => array(
                    'key' => 'd_name',
                    'value' => '',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                'd_level' => array(
                    'key' => 'd_level',
                    'value' => '',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                'd_manager' => array(
                    'key' => 'd_manager',
                    'value' => '',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                )
            )
        );
    }

    /**
     * 結尾定義函式
     *
     * 單一結尾定義可擁有單列資料，所以可定義多個結尾定義
     * 結尾的type必為foot
     */
    protected function footDefined()
    {
        $this->_foot[] = array(
            'config' => array(
                // 內容的type必為foot
                'type' => 'foot',
                'name' => 'foot',
                'style' => array(),
                'class' => ''
            ),
            'defined' => array(
                'f1' => array(
                    'key' => 'f1',
                    'value' => '結尾定義列',
                    'col' => '3',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '',
                    'list' => ''
                ),
                'f2' => array(
                    'key' => 'f2',
                    'value' => 'marshung/io',
                    'col' => '1',
                    'row' => '1',
                    'style' => array(),
                    'class' => '',
                    'default' => '1',
                    'list' => ''
                )
            )
        );
    }
}
