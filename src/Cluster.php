<?php
namespace luffyzhao\Means;

/**
 *
 */
class Cluster
{
    /**
     * 中心点
     * @var [type]
     */
    private $center;
    /**
     * 聚合点
     * @var array
     */
    private $data = [];
    /**
     * 配置
     * @var array
     */
    private $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }
    /**
     * 获取中心点
     * @method   getCenter
     * @DateTime 2017-12-07T14:27:05+0800
     * @return   [type]                   [description]
     */
    public function getCenter()
    {
        return $this->center;
    }
    /**
     * 设置中心点
     * @method   setCenter
     * @DateTime 2017-12-07T14:27:46+0800
     * @param    array                    $center [description]
     */
    public function setCenter(array $center)
    {
        $this->center = $center;
        return $this;
    }

    /**
     * 获取聚合点
     * @method   getData
     * @DateTime 2017-12-07T14:38:25+0800
     * @return   [type]                   [description]
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * 设置聚合点
     * @method   setData
     * @DateTime 2017-12-07T15:15:25+0800
     * @param    array                    $data [description]
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
    /**
     * 添加点到聚合点
     * @method   addData
     * @DateTime 2017-12-07T14:28:45+0800
     * @param    [type]                   $data [description]
     */
    public function addData($data)
    {
        $this->data[] = $data;
        return $this;
    }

    /**
     * 从聚合点中删除某点
     * @method   removeData
     * @DateTime 2017-12-07T14:29:03+0800
     * @param    [type]                   $data [description]
     * @return   [type]                         [description]
     */
    public function removeData($data)
    {
        if (($key = array_search($data, $this->data)) !== false) {
            unset($this->data[$key]);
        }
        return $this;
    }

    /**
     * 更新中心点
     * @method   updateCenter
     * @DateTime 2017-12-07T14:30:16+0800
     * @return   [type]                   [description]
     */
    public function updateCenter()
    {
        if (!count($this->data)) {
            return;
        }

        $xTotal = 0;
        $yTotal = 0;

        foreach ($this->data as $point) {
            $xTotal += $point[$this->config['xKey']];
            $yTotal += $point[$this->config['yKey']];
        }

        $this->setCenter([
            $xTotal / count($this->data),
            $yTotal / count($this->data),
        ]);

        return $this;
    }

    /**
     * 格式化数组
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'center' => $this->getCenter(),
            'data' => $this->getData(),
        ];
    }
}
