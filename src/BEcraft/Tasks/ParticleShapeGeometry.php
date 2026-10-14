<?php

declare(strict_types=1);

namespace BEcraft\Tasks;

final class ParticleShapeGeometry{
	public const MAX_POINTS_PER_RUN = 100;

	/** @return list<array{0: float, 1: float, 2: float, 3: string}> */
	public static function points(string $shape) : array{
		return match($shape){
			"cloud" => self::cloud(),
			"crown" => self::ring("flame"),
			"helix" => self::helix(),
			"dhelix" => self::doubleHelix(),
			default => []
		};
	}

	/** @return list<array{0: float, 1: float, 2: float, 3: string}> */
	public static function laser(float $directionX, float $directionY, float $directionZ) : array{
		$points = [];
		for($index = 0; $index < 40; ++$index){
			$distance = (float) $index;
			$points[] = [$distance * $directionX, $distance * $directionY + 1.0, $distance * $directionZ, "flame"];
		}
		return $points;
	}

	/** @return list<array{0: float, 1: float, 2: float, 3: string}> */
	private static function cloud() : array{
		$points = [];
		foreach(self::ring("explosion") as [$x, $y, $z]){
			$points[] = [$x, $y, $z, "explosion"];
			$points[] = [$x, $y, $z, "water_drip"];
		}
		return $points;
	}

	/** @return list<array{0: float, 1: float, 2: float, 3: string}> */
	private static function ring(string $particle) : array{
		$points = [];
		$pi = M_PI;
		$radius = 1.0 + 0.1 / $pi;
		$height = exp(-0.1 * $radius) * sin($radius) + 1.5;
		for($index = 0; $index <= 16; ++$index){
			$angle = $index * $pi / 8;
			$points[] = [$radius * cos($angle), $height, $radius * sin($angle), $particle];
		}
		return $points;
	}

	/** @return list<array{0: float, 1: float, 2: float, 3: string}> */
	private static function helix() : array{
		$points = [];
		for($index = 0; $index < 50; ++$index){
			$height = $index / 5;
			$points[] = [cos($height), $height, sin($height), "flame"];
		}
		return $points;
	}

	/** @return list<array{0: float, 1: float, 2: float, 3: string}> */
	private static function doubleHelix() : array{
		$points = [];
		for($index = 0; $index < 50; ++$index){
			$step = 5.0 - $index / 10;
			$radius = $step / 3;
			$height = 5.0 - $step;
			$angle = 3 * $step;
			$x = $radius * cos($angle);
			$z = $radius * sin($angle);
			$points[] = [$x, $height, $z, "flame"];
			$points[] = [-$x, $height, -$z, "water_drip"];
		}
		return $points;
	}
}
