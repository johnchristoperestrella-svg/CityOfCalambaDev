<?php
namespace App\ML_Models;

/**
 * Linear Regression Analysis
 * Forecasts future conditions and trends
 */
class RegressionAnalysis {
    private $slope = 0;
    private $intercept = 0;
    private $r_squared = 0;

    public function fit($x, $y) {
        $n = count($x);
        $meanX = array_sum($x) / $n;
        $meanY = array_sum($y) / $n;

        $numerator = 0;
        $denominator = 0;

        for ($i = 0; $i < $n; $i++) {
            $numerator += ($x[$i] - $meanX) * ($y[$i] - $meanY);
            $denominator += pow($x[$i] - $meanX, 2);
        }

        if ($denominator != 0) {
            $this->slope = $numerator / $denominator;
        }

        $this->intercept = $meanY - $this->slope * $meanX;
        
        // Calculate R-squared
        $this->calculateRSquared($x, $y);

        return $this;
    }

    public function predict($x) {
        return $this->slope * $x + $this->intercept;
    }

    public function forecast() {
        // Forecast population growth for next 12 months
        $currentPopulation = 548200;
        $growthRate = 0.02; // 2% monthly growth
        
        $forecast = [];
        $population = $currentPopulation;

        for ($month = 1; $month <= 12; $month++) {
            $population = $population * (1 + $growthRate);
            $forecast[] = [
                'month' => $month,
                'population' => round($population),
                'growth_rate' => $growthRate
            ];
        }

        return $forecast;
    }

    private function calculateRSquared($x, $y) {
        $n = count($x);
        $meanY = array_sum($y) / $n;
        
        $totalSumSquares = 0;
        $residualSumSquares = 0;

        for ($i = 0; $i < $n; $i++) {
            $predicted = $this->predict($x[$i]);
            $totalSumSquares += pow($y[$i] - $meanY, 2);
            $residualSumSquares += pow($y[$i] - $predicted, 2);
        }

        if ($totalSumSquares != 0) {
            $this->r_squared = 1 - ($residualSumSquares / $totalSumSquares);
        }
    }

    public function getRSquared() {
        return $this->r_squared;
    }

    public function identifyTrends($data) {
        $trends = [];
        
        // Analyze health risks
        if ($data['infant_mortality_rate'] > 25) {
            $trends[] = ['indicator' => 'Infant Mortality', 'risk' => 'High', 'action_required' => true];
        }

        // Analyze education gaps
        if ($data['education_access'] < 0.8) {
            $trends[] = ['indicator' => 'Education Access', 'risk' => 'Medium', 'action_required' => true];
        }

        return $trends;
    }
}
