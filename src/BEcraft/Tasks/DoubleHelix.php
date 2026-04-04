<?php

namespace BEcraft\Tasks;

use BEcraft\Loader;
use pocketmine\scheduler\PluginTask;
use pocketmine\level\{Location, Level};
use pocketmine\level\particle\{FlameParticle, WaterDripParticle};

class DoubleHelix extends PluginTask{
	
	public function __construct(Loader $plugin, Location $location, Level $level){
	parent::__construct($plugin, $location, $level);
	$this->plugin = $plugin;
	$this->location = $location;
	$this->level = $level;
	}
	
	public function onRun($tick){
	$level = $this->level;
	$location = $this->location;
	$radio = 5;
	for($i = 5; $i > 0; $i-=0.1){
	$radio = $i/3;
	$x = $radio*cos(3*$i);
	$y = 5-$i;
	$z = $radio*sin(3*$i);
	$level->addParticle(new FlameParticle($location->add($x, $y, $z)));
	}
	for($i = 5; $i > 0; $i-=0.1){
	$radio = $i/3;
	$x = -$radio*cos(3*$i);
	$y = 5-$i;
	$z = -$radio*sin(3*$i);
	$level->addParticle(new WaterDripParticle($location->add($x, $y, $z)));
	}
	}
	
}