<?php

include '../vendor/autoload.php';

use \luffyzhao\Means\KMeans;
function arrayUniqueFb($array2D)
{
    foreach ($array2D as $v) {
        $v = join(",", $v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        $temp[] = $v;
    }

    $temp = array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
    foreach ($temp as $k => $v) {
        $temp[$k] = explode(",", $v); //再将拆开的数组重新组装
    }
    return $temp;
}
// [113.937889, 22.518047]
// [114.054573, 22.534653]
$data = [
    [
        'lng' => 113.937889,
        'lat' => 22.518047,
        'id' => 1,
    ],
    [
        'lng' => 114.054573,
        'lat' => 22.534653,
        'id' => 2,
    ],
];
$means = new KMeans($data);

$means->setXKey('lng')->setYKey('lat')->setClusterCount(2)->solve();

foreach ($means->toArray() as $key => $value) {
    print_r($value);
}
