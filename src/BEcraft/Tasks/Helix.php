<?php

namespace BEcraft\Tasks;

use BEcraft\Loader;
use pocketmine\scheduler\PluginTask;
use pocketmine\level\{Location, Level};
use pocketmine\level\particle\FlameParticle;

class Helix extends PluginTask{
	
	public function __construct(Loader $plugin, Location $location, Level $level){
	parent::__construct($plugin, $location, $level);
	$this->plugin = $plugin;
	$this->location = $location;
	$this->level = $level;
	}
	
	public function onRun($tick){
	$level = $this->level;
	$location = $this->location;
	$radio = 1;
	for($y = 0; $y < 10; $y+=0.2){
	$x = $radio*cos($y);
	$z = $radio*sin($y);
	$level->addParticle(new FlameParticle($location->add($x, $y, $z)));
	}
	}
	
}