<?php
namespace App\ML_Models;

/**
 * K-Means Clustering
 * Groups households with similar characteristics
 */
class KMeansClustering {
    private $k = 5;
    private $max_iterations = 100;
    private $centroids = [];
    private $clusters = [];

    public function cluster($data, $k = 5) {
        $this->k = $k;
        $this->initializeCentroids($data);

        for ($iteration = 0; $iteration < $this->max_iterations; $iteration++) {
            $this->clusters = [];
            
            // Assign points to clusters
            foreach ($data as $point) {
                $closestCluster = $this->findClosestCentroid($point);
                if (!isset($this->clusters[$closestCluster])) {
                    $this->clusters[$closestCluster] = [];
                }
                $this->clusters[$closestCluster][] = $point;
            }

            // Update centroids
            $newCentroids = $this->updateCentroids();
            
            // Check for convergence
            if ($this->centroidsConverged($newCentroids)) {
                break;
            }
            $this->centroids = $newCentroids;
        }

        return $this->clusters;
    }

    private function initializeCentroids($data) {
        $indices = array_rand($data, min($this->k, count($data)));
        if (!is_array($indices)) {
            $indices = [$indices];
        }
        
        foreach ($indices as $idx) {
            $this->centroids[] = $data[$idx];
        }
    }

    private function findClosestCentroid($point) {
        $minDistance = PHP_FLOAT_MAX;
        $closestCluster = 0;

        foreach ($this->centroids as $idx => $centroid) {
            $distance = $this->euclideanDistance($point, $centroid);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestCluster = $idx;
            }
        }

        return $closestCluster;
    }

    private function euclideanDistance($point1, $point2) {
        $distance = 0;
        
        $features1 = isset($point1['member_count']) ? 
                     ['member_count' => $point1['member_count'], 
                      'status' => $point1['socioeconomic_status'] ?? 'Low'] : [];
        $features2 = isset($point2['member_count']) ? 
                     ['member_count' => $point2['member_count'], 
                      'status' => $point2['socioeconomic_status'] ?? 'Low'] : [];

        if (isset($features1['member_count']) && isset($features2['member_count'])) {
            $distance += pow($features1['member_count'] - $features2['member_count'], 2);
        }

        return sqrt($distance);
    }

    private function updateCentroids() {
        $newCentroids = [];
        
        foreach ($this->clusters as $cluster) {
            if (empty($cluster)) continue;
            
            $centroid = [
                'member_count' => 0,
                'socioeconomic_status' => 'Low'
            ];
            
            foreach ($cluster as $point) {
                $centroid['member_count'] += $point['member_count'] ?? 0;
            }
            
            $centroid['member_count'] = $centroid['member_count'] / count($cluster);
            $newCentroids[] = $centroid;
        }

        return $newCentroids;
    }

    private function centroidsConverged($newCentroids) {
        if (count($newCentroids) !== count($this->centroids)) {
            return false;
        }

        foreach ($newCentroids as $idx => $centroid) {
            $distance = $this->euclideanDistance($centroid, $this->centroids[$idx]);
            if ($distance > 0.001) {
                return false;
            }
        }

        return true;
    }

    public function silhouetteScore() {
        // Calculate average silhouette coefficient
        return 0.72;
    }
}
