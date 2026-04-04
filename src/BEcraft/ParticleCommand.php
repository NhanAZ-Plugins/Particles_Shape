<?php

namespace BEcraft;

use BEcraft\Loader;
use pocketmine\{Server, Player};
use pocketmine\utils\TextFormat;
use pocketmine\command\{CommandSender, PluginCommand};

class ParticleCommand extends PluginCommand{
	
	private $plugin;
	
	public function __construct($command, Loader $plugin){
	parent::__construct($command, $plugin);
	$this->setDescription(TextFormat::YELLOW."§aLệnh Particles Shape");
	$this->plugin = $plugin;
	}
	
	public function getPlugin(){
	return $this->plugin;
	}
	
	public function execute(CommandSender $sender, $label, array $args){
	if(!$sender instanceof Player){
	$sender->sendMessage(Loader::PREFIX.TextFormat::RED." §aVui Lòng Sử Dụng Lệnh Trong Trò Chơi!");
	return;
	}
	
	if(!$sender->isOp()){
	$sender->sendMessage(Loader::PREFIX.TextFormat::RED." §aBạn Không Có Quyền Sử Dụng Lệnh!");
	return;
	}
	
	$shapes = ["helix", "crown", "cloud", "dhelix", "laser"];
	
	if(!isset($args[0])){
	$sender->sendMessage(Loader::PREFIX.TextFormat::RED." §aSử Dụng: /particles <add [Particles] [Tên]> | <remove [Tên]> | <list>");
	return;
	}
	
	$values = ["add", "remove", "list"];
	
	if(!in_array($args[0], $values)){
	$sender->sendMessage(Loader::PREFIX.TextFormat::RED." §aSử Dụng: /particles <add [Particle] [Tên]> | <remove [Tên] | <list>");
	return;
	}
	
	switch($args[0]){
	case "add":
	if(!isset($args[1])){
	$sender->sendMessage(Loader::PREFIX.TextFormat::GOLD." §aBạn Cần Chỉ Định Một Particle Bất Kỳ, Các Particle Hiện Có: helix, crown, cloud, dhelix, laser");
	return;
	}
	$shape = $args[1];
	if(!in_array($shape, $shapes)){
	$sender->sendMessage(Loader::PREFIX.TextFormat::RED." §aParticles Không Xác Định, Các Particles Hiện Có: ".TextFormat::GREEN.implode(", ", $shapes));
	return;
	}
	if(!isset($args[2])){
	$sender->sendMessage(Loader::PREFIX.TextFormat::GOLD." §aBạn Cần Đặt Một Tên Cho Particle Này...");
	return;
	}
	$name = strtolower($args[2]);
	if(is_numeric($name)){
	$sender->sendMessage(Loader::PREFIX.TextFormat::RED." §aBạn Cần Sử Dụng Chữ Cái Để Đặt Tên...");
	return;
	}
	switch($shape){
	case "helix":
	$this->getPlugin()->newHelix($sender->getLocation(), $sender->getLevel(), $name);
	break;
	case "crown":
	$this->getPlugin()->newCrown($sender->getLocation(), $sender->getLevel(), $name);
	break;
	case "cloud":
	$this->getPlugin()->newCloud($sender->getLocation(), $sender->getLevel(), $name);
	break;
	case "dhelix":
	$this->getPlugin()->newDoubleHelix($sender->getLocation(), $sender->getLevel(), $name);
	break;
	case "laser":
	$this->getPlugin()->newLaser($sender, $name);
	break;
	}
	$sender->sendMessage(Loader::PREFIX.TextFormat::GRAY." §aBạn Đã Triệu Hồi Particle Mới Có Tên Là: ".TextFormat::GREEN.$name.TextFormat::GRAY.", §aThuộc Particle: ".TextFormat::GREEN.$shape);
	return true;
	break;
	
	case "remove":
	if(!isset($args[1])){
	$sender->sendMessage(Loader::PREFIX.TextFormat::RED." §aBạn Cần Chỉ Định Tên Particle Đã Add Để Xóa, Có Thể Xem Danh Sách Tên Particle Đã Add Bằng Lệnh: /particles list...");
	return;
	}
	$name = strtolower($args[1]);
	if(!$this->getPlugin()->existsTask($name)){
	$sender->sendMessage(Loader::PREFIX.TextFormat::GRAY." §aKhông Có Particle Tên Này, Sử Dụng: ".TextFormat::GREEN."§a/particles list".TextFormat::GRAY." §aĐể Kiểm Tra Tất Cả Tên");
	return;
	}
	$this->getPlugin()->removeTask($this->getPlugin()->tasks[$name]);
	unset($this->getPlugin()->tasks[$name]);
	$sender->sendMessage(Loader::PREFIX.TextFormat::GREEN." §aBạn Đã Loại Bỏ Particle Có Tên: ".TextFormat::GOLD.$name);
	return true;
	break;
	
	case "list":
	$sender->sendMessage($this->getPlugin()->getTasks());
	return true;
	break;
	}
	}
	
    }