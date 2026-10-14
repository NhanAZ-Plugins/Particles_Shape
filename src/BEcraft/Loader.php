<?php

declare(strict_types=1);

namespace BEcraft;

use BEcraft\Tasks\ParticleShapeTask;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

final class Loader extends PluginBase{
	public const PREFIX = TextFormat::GRAY . "§e༄༂" . TextFormat::WHITE . "§cP" . TextFormat::GREEN . "§cS" . TextFormat::GRAY . "§e༂࿐" . TextFormat::RESET;
	private const SHAPES = ["helix", "crown", "cloud", "dhelix", "laser"];
	private const MAX_ACTIVE_EFFECTS = 5;
	private const TASK_PERIOD_TICKS = 5;

	/** @var array<string, array{task: ParticleShapeTask, shape: string}> */
	private array $tasks = [];

	protected function onDisable() : void{
		foreach(array_values($this->tasks) as $effect){
			$effect["task"]->getHandler()?->cancel();
		}
		$this->tasks = [];
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(strtolower($command->getName()) !== "particles"){
			return false;
		}
		if(!$sender instanceof Player){
			$sender->sendMessage(self::PREFIX . TextFormat::RED . " Chỉ có thể dùng lệnh này trong trò chơi.");
			return true;
		}

		$action = strtolower($args[0] ?? "");
		switch($action){
			case "add":
				$this->addEffect($sender, $args[1] ?? "", $args[2] ?? "");
				return true;
			case "remove":
				$name = strtolower($args[1] ?? "");
				if(!$this->removeEffect($name)){
					$sender->sendMessage(self::PREFIX . TextFormat::RED . " Không có hiệu ứng tên " . TextFormat::GOLD . $name . TextFormat::RED . ".");
					return true;
				}
				$sender->sendMessage(self::PREFIX . TextFormat::GREEN . " Đã dừng hiệu ứng " . TextFormat::GOLD . $name . TextFormat::GREEN . ".");
				return true;
			case "list":
				$this->sendEffectList($sender);
				return true;
			default:
				$sender->sendMessage(self::PREFIX . TextFormat::YELLOW . " Dùng: /particles add <" . implode("|", self::SHAPES) . "> <tên>, /particles remove <tên> hoặc /particles list");
				return true;
		}
	}

	public function scheduleEffect(Player $player, string $shape, string $name) : bool{
		$key = strtolower($name);
		if(isset($this->tasks[$key]) || count($this->tasks) >= self::MAX_ACTIVE_EFFECTS){
			return false;
		}

		$task = new ParticleShapeTask($this, $player, $shape, $key);
		$this->getScheduler()->scheduleRepeatingTask($task, self::TASK_PERIOD_TICKS);
		$this->tasks[$key] = [
			"task" => $task,
			"shape" => $shape
		];
		return true;
	}

	public function removeEffect(string $name) : bool{
		$key = strtolower($name);
		if(!isset($this->tasks[$key])){
			return false;
		}
		$handler = $this->tasks[$key]["task"]->getHandler();
		if($handler === null){
			$this->forgetEffect($key);
			return false;
		}
		$handler->cancel();
		return true;
	}

	public function forgetEffect(string $name) : void{
		unset($this->tasks[strtolower($name)]);
	}

	/** @return array<string, string> */
	public function getActiveEffects() : array{
		$effects = [];
		foreach($this->tasks as $name => $effect){
			$effects[$name] = $effect["shape"];
		}
		return $effects;
	}

	private function addEffect(Player $player, string $shape, string $name) : void{
		$shape = strtolower($shape);
		$name = strtolower($name);
		if(!in_array($shape, self::SHAPES, true)){
			$player->sendMessage(self::PREFIX . TextFormat::RED . " Particle không hợp lệ. Chọn: " . TextFormat::GREEN . implode(", ", self::SHAPES));
			return;
		}
		if(preg_match('/^[a-z0-9_-]{1,32}$/D', $name) !== 1){
			$player->sendMessage(self::PREFIX . TextFormat::RED . " Tên chỉ được gồm chữ thường, số, dấu gạch ngang hoặc gạch dưới (tối đa 32 ký tự).");
			return;
		}
		if(isset($this->tasks[$name])){
			$player->sendMessage(self::PREFIX . TextFormat::RED . " Tên này đang được dùng bởi một hiệu ứng khác.");
			return;
		}
		if(count($this->tasks) >= self::MAX_ACTIVE_EFFECTS){
			$player->sendMessage(self::PREFIX . TextFormat::RED . " Máy chủ đã đạt giới hạn " . self::MAX_ACTIVE_EFFECTS . " hiệu ứng cùng lúc.");
			return;
		}

		$this->scheduleEffect($player, $shape, $name);
		$player->sendMessage(self::PREFIX . TextFormat::GRAY . " Đã thêm hiệu ứng " . TextFormat::GREEN . $name . TextFormat::GRAY . " thuộc dạng " . TextFormat::GREEN . $shape . TextFormat::GRAY . ".");
	}

	private function sendEffectList(Player $player) : void{
		if($this->tasks === []){
			$player->sendMessage(self::PREFIX . TextFormat::RED . " Máy chủ hiện không có hiệu ứng particle nào.");
			return;
		}

		$items = [];
		foreach($this->getActiveEffects() as $name => $shape){
			$items[] = $name . " (" . $shape . ")";
		}
		$player->sendMessage(self::PREFIX . TextFormat::GREEN . implode(", ", $items));
	}
}
