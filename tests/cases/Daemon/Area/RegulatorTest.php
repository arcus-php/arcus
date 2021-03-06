<?php

namespace Arcus\Daemon\Area;


use Arcus\Daemon\Plant;
use Arcus\TestCase;

class RegulatorTest extends TestCase {

    public function testConstRegulator() {

        $regulator = new ConstantRegulator(5);

        $this->assertEquals(5, $regulator(new class($this->daemon, "test", $regulator) extends Plant {
            public function getLoadAverage() : float {
                return 1.0;
            }
        }));
    }

    public function testLoadRegulator() {
        $regulator = new LoadRegulator(2, 6);
        $regulator->setLoadLevel(0.4, 0.6);
        $regulator->setStepSize(2);

        $area = new class($this->daemon, "test", $regulator) extends Plant {
            public $load;
            public $workers_count;
            public function setStars(float $load, int $workers_count) {
                $this->load = $load;
                $this->workers_count = $workers_count;
                return $this;
            }

            public function getLoadAverage() : float {
                return $this->load;
            }

            public function getProcessCount() : int {
                return $this->workers_count;
            }
        };

        $this->assertSame(2, $regulator($area->setStars(0.0, 0)));
        $this->assertSame(2, $regulator($area->setStars(0.4, 2)));
        $this->assertSame(2, $regulator($area->setStars(0.6, 2)));
        $this->assertSame(4, $regulator($area->setStars(0.7, 2)));
        $this->assertSame(4, $regulator($area->setStars(0.5, 4)));
        $this->assertSame(6, $regulator($area->setStars(0.8, 4)));
        $this->assertSame(6, $regulator($area->setStars(0.8, 6)));
        $this->assertSame(4, $regulator($area->setStars(0.3, 6)));
        $this->assertSame(2, $regulator($area->setStars(0.3, 4)));
        $this->assertSame(2, $regulator($area->setStars(0.3, 2)));
    }
}