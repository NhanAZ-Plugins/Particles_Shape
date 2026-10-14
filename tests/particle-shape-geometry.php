<?php

declare(strict_types=1);

require __DIR__ . "/../src/BEcraft/Tasks/ParticleShapeGeometry.php";

use BEcraft\Tasks\ParticleShapeGeometry;

$expectedCounts = [
	"cloud" => 34,
	"crown" => 17,
	"helix" => 50,
	"dhelix" => 100
];

foreach($expectedCounts as $shape => $expectedCount){
	$points = ParticleShapeGeometry::points($shape);
	if(count($points) !== $expectedCount || count($points) > ParticleShapeGeometry::MAX_POINTS_PER_RUN){
		throw new RuntimeException("Unexpected point count for {$shape}: " . count($points));
	}
	foreach($points as [$x, $y, $z, $particle]){
		if(!is_finite($x) || !is_finite($y) || !is_finite($z) || max(abs($x), abs($y), abs($z)) > 40){
			throw new RuntimeException("Unbounded coordinate in {$shape}");
		}
		if(!in_array($particle, ["flame", "explosion", "water_drip"], true)){
			throw new RuntimeException("Unknown particle type in {$shape}");
		}
	}
}

$laser = ParticleShapeGeometry::laser(0.0, 0.0, 1.0);
if(count($laser) !== 40 || count($laser) > ParticleShapeGeometry::MAX_POINTS_PER_RUN){
	throw new RuntimeException("Laser exceeded its particle bound");
}
foreach($laser as [$x, $y, $z, $particle]){
	if(!is_finite($x) || !is_finite($y) || !is_finite($z) || abs($x) > 40 || abs($z) > 40 || $y !== 1.0 || $particle !== "flame"){
		throw new RuntimeException("Unexpected laser point");
	}
}

if(ParticleShapeGeometry::points("invalid") !== []){
	throw new RuntimeException("Unknown shapes must not generate particles");
}

echo "Passed 5 bounded particle geometry cases\n";
