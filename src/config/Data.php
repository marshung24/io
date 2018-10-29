<?php
namespace marshung\io\config;

/**
 * IO Data
 *
 * @author Mars.Hung (tfaredxj@gmail.com) 2018-010-29
 *        
 */
class Data
{

    /**
     * Options Cache
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Construct
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * Destruct
     */
    public function __destruct()
    {}

    /**
     * Re-initialize
     */
    public function reInitialize()
    {
        $this->initialize();
    }

    /**
     * initialize
     */
    public function initialize()
    {
        // ====== 初始化定義 ======
    }

    /**
     * *****************************************************
     * ************** Config Setting Function **************
     * *****************************************************
     */
    
    /**
     * 
     * @param string $type
     */
    public function setCfgType($type)
    {
        $this->_options['cfgType'] = $type;
        return $this;
    }
    
    /**
     *
     * @param string $name
     */
    public function setCfgName($name)
    {
        $this->_options['cfgName'] = $name;
        return $this;
    }
    
    /**
     *
     * @param array $style
     */
    public function setCfgStyle(Array $style)
    {
        $this->_options['cfgStyle'] = $style;
        return $this;
    }
    
    /**
     *
     * @param string $class
     */
    public function setCfgClass($class)
    {
        $this->_options['cfgClass'] = $class;
        return $this;
    }
    
    /**
     * ******************************************************
     * ************** Defined Setting Function **************
     * ******************************************************
     */
    
    /**
     * Set key list
     * 
     * Key list is to specify the available data key values and sorting.
     * The data of the key values not in this list is invalid.
     * 
     * @param array $keyList
     */
    public function setKey(Array $key)
    {
        $this->_options['key'] = $key;
        return $this;
    }
    
    /**
     * Set value list
     */
    public function setValue(Array $value)
    {
        $this->_options['value'] = $value;
        return $this;
    }
    
    /**
     * Set col list
     */
    public function setCol(Array $col)
    {
        $this->_options['col'] = $keyList;
        return $this;
    }
    
    /**
     * Set row list
     */
    public function setRow(Array $row)
    {
        $this->_options['key'] = $row;
        return $this;
    }
    
    /**
     * Set style list
     */
    public function setStyle(Array $style)
    {
        $this->_options['style'] = $style;
        return $this;
    }
    
    /**
     * Set class list
     */
    public function setClass(Array $class)
    {
        $this->_options['class'] = $class;
        return $this;
    }
    
    /**
     * Set default list
     */
    public function setDefault($default)
    {
        $this->_options['default'] = $default;
        return $this;
    }
    
    /**
     * **************************************************
     * ************** Get Setting Function **************
     * **************************************************
     */
    
    /**
     * Getting defined config
     */
    public function get()
    {
        
    }
}
