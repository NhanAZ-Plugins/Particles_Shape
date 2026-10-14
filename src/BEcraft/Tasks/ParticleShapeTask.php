<?php

declare(strict_types=1);

namespace BEcraft\Tasks;

use BEcraft\Loader;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\WaterDripParticle;

final class ParticleShapeTask extends Task{
	private ?Position $anchor;

	public function __construct(
		private Loader $plugin,
		private Player $player,
		private string $shape,
		private string $name
	){
		$this->anchor = $shape === "laser" ? null : $player->getPosition();
	}

	public function onRun() : void{
		if($this->shape === "laser"){
			if(!$this->player->isConnected() || !$this->player->isAlive()){
				throw new CancelTaskException();
			}
			$world = $this->player->getWorld();
			$origin = $this->player->getPosition();
			$direction = $this->player->getDirectionVector();
			$points = ParticleShapeGeometry::laser($direction->x, $direction->y, $direction->z);
		}else{
			if($this->anchor === null || !$this->anchor->isValid()){
				throw new CancelTaskException();
			}
			$world = $this->anchor->getWorld();
			$origin = $this->anchor;
			$points = ParticleShapeGeometry::points($this->shape);
		}

		if($points === [] || count($points) > ParticleShapeGeometry::MAX_POINTS_PER_RUN){
			throw new CancelTaskException();
		}

		foreach($points as [$x, $y, $z, $particleName]){
			$world->addParticle($origin->add($x, $y, $z), $this->createParticle($particleName));
		}
	}

	public function onCancel() : void{
		$this->plugin->forgetEffect($this->name);
	}

	private function createParticle(string $name) : FlameParticle|ExplodeParticle|WaterDripParticle{
		return match($name){
			"flame" => new FlameParticle(),
			"explosion" => new ExplodeParticle(),
			"water_drip" => new WaterDripParticle(),
			default => throw new CancelTaskException()
		};
	}
}
