<?php
namespace luffyzhao\Means;

/**
 *
 */
class KMeans
{
    private $data = [];

    private $clusters = [];

    private $config = [
        'xKey' => 0,
        'yKey' => 1,
        'clusterCount' => 0,
    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * 执行算法
     * @method   solve
     * @DateTime 2017-12-07T15:02:24+0800
     * @return   [type]                   [description]
     */
    public function solve()
    {
        $this->_initialiseClusters();
        while ($this->_iterate()) {}

        return $this;
    }

    /**
     * 格式化数组
     *
     * @return array
     */
    public function toArray()
    {
        $clusters = [];
        foreach ($this->clusters as $key => $value) {
            $clusters[$key] = $value->toArray();
        }
        return $clusters;
    }

    /**
     * 生成带小数的随机之间的随机数
     * @method   randFloat
     * @DateTime 2017-12-07T14:05:14+0800
     * @param    int                      $min [description]
     * @param    int                      $max [description]
     * @return   [type]                        [description]
     */
    private function randFloat($min = 0, $max = 1)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    /**
     * 算法核心
     *
     * @return bool
     */
    private function _iterate()
    {
        $continue = false;

        foreach ($this->clusters as $c) {

            foreach ($c->getData() as $point) {

                $leastWcss = 2147483647;
                $nearestCluster = null;

                foreach ($this->clusters as $cluster) {

                    $wcss = $this->_getWcss($point, $cluster);

                    if ($wcss < $leastWcss) {

                        $leastWcss = $wcss;
                        $nearestCluster = $cluster;
                    }
                }

                if ($nearestCluster != $c) {

                    $c->removeData($point);
                    $nearestCluster->addData($point);
                    $continue = true;
                }
            }
        }

        foreach ($this->clusters as $cluster) {

            $cluster->updateCenter();
        }

        return $continue;
    }

    /**
     * 初始化集群分析
     *
     * @return null
     */
    private function _initialiseClusters()
    {
        $this->clusters = array();

        $maxX = $this->_getMaxX();
        $maxY = $this->_getMaxY();

        $minX = $this->_getMinX();
        $minY = $this->_getMinY();

        for ($i = 0; $i < $this->getClusterCount(); $i++) {

            $cluster = new Cluster($this->config);
            $cluster->setCenter([
                $this->randFloat($minX, $maxX),
                $this->randFloat($minY, $maxY),
            ]);

            $this->clusters[] = $cluster;
        }

        if ($this->getClusterCount()) {

            $this->clusters[0]->setData($this->data);
        }
    }

    /**
     * Get the x-bounds of the source data
     *
     * @return float
     */
    private function _getMaxX()
    {
        $max = 0;

        foreach ($this->data as $point) {

            if ($point[$this->getXKey()] > $max) {

                $max = $point[$this->getXKey()];
            }
        }

        return $max;
    }

    private function _getMinX()
    {
        $min = current($this->data)[$this->getXKey()];
        foreach ($this->data as $point) {

            if ($point[$this->getXKey()] < $min) {

                $min = $point[$this->getXKey()];
            }
        }

        return $min;
    }

    /**
     * Get the y-bounds of the source data
     *
     * @return float
     */
    private function _getMaxY()
    {
        $max = 0;

        foreach ($this->data as $point) {

            if ($point[$this->getYKey()] > $max) {

                $max = $point[$this->getYKey()];
            }
        }

        return $max;
    }

    /**
     * Get the y-bounds of the source data
     *
     * @return float
     */
    private function _getMinY()
    {
        $min = current($this->data)[$this->getYKey()];

        foreach ($this->data as $point) {

            if ($point[$this->getYKey()] < $min) {

                $min = $point[$this->getYKey()];
            }
        }

        return $min;
    }

    /**
     * Get the within-cluster sum of squares for a data point/cluster centroid
     *
     * @param array $point An element from the source data
     * @param KMeans_Cluster $cluster A cluster to calculate the distance to
     * @return float
     */
    private function _getWcss($point, $cluster)
    {

        return (pow($point[$this->getXKey()] - $cluster->getCenter()[0], 2) +
            pow($point[$this->getYKey()] - $cluster->getCenter()[1], 2));
    }

    /**
     * 重载函数
     * @method   __call
     * @DateTime 2017-12-07T14:46:49+0800
     * @param    [type]                   $method     [description]
     * @param    [type]                   $parameters [description]
     * @return   [type]                               [description]
     */
    public function __call($method, $parameters)
    {
        $key = ucfirst(substr($method, 3));
        if (!isset($this->config[lcfirst($key)])) {
            return $this;
        }

        if (strpos($method, 'set') === 0) {
            $this->config[lcfirst($key)] = current($parameters);
        } else if (strpos($method, 'get') === 0) {
            return $this->config[lcfirst($key)];
        }
        return $this;
    }

}
