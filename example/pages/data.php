<?php


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
            'birthday' => '2003-01-01',
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
            'birthday' => '2000-01-01',
            'gender' => '0',
            'text' => '-11',
        ],
        [
            'u_no' => 'export'.$number.'004',
            'c_name' => 'Joe',
            'id_no' => 'A123456743',
            'birthday' => '2000-01-01',
            'gender' => '1',
            'text' => '0',
        ],
        [
            'u_no' => 'export'.$number.'005',
            'c_name' => 'Ann',
            'id_no' => 'A223434252',
            'birthday' => '2000-01-01',
            'gender' => '0',
            'text' => -111,
        ],
        [
            'u_no' => 'export'.$number.'006',
            'c_name' => 'Judy',
            'id_no' => 'A223467893',
            'birthday' => '2000-01-01',
            'gender' => '0',
            'text' => 111,
        ],
        [
            'u_no' => 'export'.$number.'006',
            'c_name' => 'Judy',
            'id_no' => 'A223467893',
            'birthday' => '2000-01-01',
            'gender' => '0',
            'text' => 111,
        ],
        [
            'u_no' => 'export'.$number.'006',
            'c_name' => 'Judy',
            'id_no' => 'A223467893',
            'birthday' => '2000-01-01',
            'gender' => '0',
            'text' => 111,
        ]
    ];
}

function getDeptData($number = '1')
{
    // 取得原始資料
    return $data = [
        [
            'd_code' => 'dept_'.$number.'001',
            'd_name' => 'RD',
            'd_level' => '3',
            'd_manager' => 'Mary'
        ],
        [
            'd_code' => 'dept_'.$number.'002',
            'd_name' => 'Sales',
            'd_level' => '3',
            'd_manager' => 'Cindy'
        ],
        [
            'd_code' => 'dept_'.$number.'003',
            'd_name' => 'Management',
            'd_level' => '3',
            'd_manager' => 'Pikachu'
        ]
    ];
}