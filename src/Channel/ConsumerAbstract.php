<?php

namespace Arcus\Channel;


use ION\Sequence;

abstract class ConsumerAbstract implements ConsumerInterface {

    /**
     * @var string[] producer name
     */
    protected $_channels = [];
    /**
     * @var string consumer name
     */
    protected $_consumer;
    /**
     * @var int count of jobs
     */
    protected $_batch_size = 5;
    /**
     * @var bool remove reserved jobs automatically after done
     */
    protected $_auto_release = true;
    protected $_auto_enable = true;
    /**
     * @var Sequence
     */
    protected $_on_task;

    /**
     * @inheritdoc
     * @param string $channel
     *
     * @return int
     */
    public function subscribe(string $channel) : int {
        $this->_channels[] = $channel;
        $this->_channels = array_unique($this->_channels);
        return count($this->_channels);
    }

    public function getName() : string {
        return $this->_consumer;
    }


    public function getChannelsNames() : array {
        return $this->_channels;
    }

    public function __toString() : string {
        return get_called_class()."({$this->_consumer})";
    }

    /**
     * @param bool $state
     *
     * @return ConsumerInterface
     */
    public function setAutoRelease(bool $state) {
        $this->_auto_release = $state;

        return $this;
    }

    /**
     * @param int $size
     *
     * @return ConsumerInterface
     */
    public function setBatchSize(int $size) {
        $this->_batch_size = $size;

        return $this;
    }

    public function autoEnable(bool $state) {
        $this->_auto_enable = $state;

        return $this;
    }

    /**
     * @return Sequence
     */
    public function whenTask() : Sequence {
        if (!$this->_on_task) {
            $this->_on_task = new Sequence();
        }
        return $this->_on_task;
    }
}