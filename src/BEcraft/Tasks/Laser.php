<?php

namespace BEcraft\Tasks;

use BEcraft\Loader;
use pocketmine\scheduler\PluginTask;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\level\particle\{FlameParticle};

class Laser extends PluginTask{
	
	public function __construct(Loader $plugin, Player $player){
	parent::__construct($plugin, $player);
	$this->plugin = $plugin;
	$this->player = $player;
	}
	
	public function onRun($tick){
	$player = $this->player;
	$direction = $player->getDirectionVector();
	for($i = 0; $i < 40; ++$i){
	$x = $i*$direction->x+$player->x;
	$y = $i*$direction->y+$player->y;
	$z = $i*$direction->z+$player->z;
	$player->getLevel()->addParticle(new FlameParticle(new Vector3($x, $y+1, $z)));
	}
	}
	
}