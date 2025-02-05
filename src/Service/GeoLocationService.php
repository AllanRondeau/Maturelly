<?php

namespace App\Service;

use App\Entity\User;

class GeoLocationService
{
    private const EARTH_RADIUS = 6371;

    public function calculateDistance(User $user1, User $user2): float
    {
        $lat1 = deg2rad((float)$user1->getLatitude());
        $lon1 = deg2rad((float)$user1->getLongitude());
        $lat2 = deg2rad((float)$user2->getLatitude());
        $lon2 = deg2rad((float)$user2->getLongitude());

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat/2) * sin($deltaLat/2) +
            cos($lat1) * cos($lat2) * 
            sin($deltaLon/2) * sin($deltaLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return self::EARTH_RADIUS * $c;
    }

    public function getNearbyUsers(User $user, float $maxDistance = 50): array
    {
        // Cette méthode sera implémentée dans le repository
        return [];
    }
}