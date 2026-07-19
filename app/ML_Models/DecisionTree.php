<?php
namespace App\ML_Models;

/**
 * Decision Tree Classifier
 * Predicts household socioeconomic status and vulnerability
 */
class DecisionTree {
    private $max_depth = 5;
    private $min_samples_split = 2;

    public function predict($household) {
        $features = $this->extractFeatures($household);
        return $this->classify($features);
    }

    private function extractFeatures($household) {
        return [
            'income_level' => $household['socioeconomic_status'] === 'Low' ? 1 : 0,
            'family_size' => $household['member_count'] ?? 0,
            'education' => $household['education_level'] ?? 0,
            'health_access' => $household['health_access'] ?? 0,
        ];
    }

    private function classify($features) {
        // Simplified decision tree logic
        $riskScore = 0;

        if ($features['income_level'] == 1) {
            $riskScore += 0.4;
        }

        if ($features['family_size'] > 5) {
            $riskScore += 0.2;
        }

        if ($features['education'] < 2) {
            $riskScore += 0.2;
        }

        if ($features['health_access'] < 1) {
            $riskScore += 0.2;
        }

        return min($riskScore, 1.0);
    }

    public function evaluateModel($predictions, $actual) {
        $correct = 0;
        foreach ($predictions as $key => $pred) {
            if (($pred > 0.5 && $actual[$key] > 0.5) || ($pred <= 0.5 && $actual[$key] <= 0.5)) {
                $correct++;
            }
        }
        return $correct / count($predictions);
    }
}
