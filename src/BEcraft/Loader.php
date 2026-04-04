<?php

namespace BEcraft;

use pocketmine\{Server, Player};
use pocketmine\plugin\PluginBase;
use pocketmine\level\{Level, Position, Location};
use BEcraft\Tasks\{DoubleHelix, Crown, CloudRain, Laser, Helix};
use BEcraft\ParticleCommand;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase{
	
	const PREFIX = TextFormat::GRAY."§e༄༂".TextFormat::WHITE."§cP".TextFormat::GREEN."§cS".TextFormat::GRAY."§e༂࿐".TextFormat::RESET;
	
	public $tasks;
	
	public function onEnable(){
	$this->getLogger()->info(TextFormat::GREEN."Plugin Đã Được Kích Hoạt!".PHP_EOL.TextFormat::GOLD."Tác Giả: ".TextFormat::AQUA."@BEcraft_MCPE");
	$this->getServer()->getCommandMap()->register("particles", new ParticleCommand("particles", $this));
	$this->tasks = [];
	}
	
	public function onDisable(){
	$this->getLogger()->info(TextFormat::RED."Plugin Đã Bị Vô Hiệu Hóa!");
	}
	
	public function onLoad(){
	$this->getLogger()->info(TextFormat::YELLOW."Đang Tải Plugin...");
	}
	
	public function removeTask($id){
	$this->getServer()->getScheduler()->cancelTask($id);
	}
	
	public function existsTask($name){
	return array_key_exists($name, $this->tasks);
	}
	
	public function getTasks(){
	if(empty($this->tasks)){
	return TextFormat::RED."§e༄༂§cPS§e༂࿐§a Máy Chủ Hiện Không Có Bất Kỳ Một Particles Nào";
	}
	$keys = implode(", ", array_keys($this->tasks));
	return TextFormat::GREEN.$keys;
	}
	
	public function newCrown(Location $location, Level $level, $name){
	$this->getServer()->getScheduler()->scheduleRepeatingTask($task = new Crown($this, $location, $level), 5);
	$this->tasks[$name] = $task->getTaskId();
	}
	
	public function newCloud(Location $location, Level $level, $name){
	$this->getServer()->getScheduler()->scheduleRepeatingTask($task = new CloudRain($this, $location, $level), 5);
	$this->tasks[$name] = $task->getTaskId();
	}
	
	public function newLaser(Player $player, $name){
	$this->getServer()->getScheduler()->scheduleRepeatingTask($task = new Laser($this, $player), 5);
	$this->tasks[$name] = $task->getTaskId();
	}
	
	public function newHelix(Location $location, Level $level, $name){
	$this->getServer()->getScheduler()->scheduleRepeatingTask($task = new Helix($this, $location, $level), 5);
	$this->tasks[$name] = $task->getTaskId();
	}
	
	public function newDoubleHelix(Location $location, Level $level, $name){
    $this->getServer()->getScheduler()->scheduleRepeatingTask($task = new DoubleHelix($this, $location, $level), 5);
	$this->tasks[$name] = $task->getTaskId();
	}
	
    }