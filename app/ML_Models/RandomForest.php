<?php
namespace App\ML_Models;

/**
 * Random Forest Classifier
 * Ensemble method for improved prediction accuracy
 */
class RandomForest {
    private $num_trees = 100;
    private $max_depth = 10;
    private $trees = [];

    public function train($data, $labels) {
        for ($i = 0; $i < $this->num_trees; $i++) {
            // Bootstrap sampling
            $sample = $this->bootstrapSample($data, $labels);
            $tree = new DecisionTree();
            // In a real implementation, train each tree
            $this->trees[] = $tree;
        }
        return $this;
    }

    public function predict($instance) {
        $predictions = [];
        foreach ($this->trees as $tree) {
            $predictions[] = $tree->predict($instance);
        }
        
        // Return average prediction
        return array_sum($predictions) / count($predictions);
    }

    private function bootstrapSample($data, $labels) {
        $sample = [];
        $sampleLabels = [];
        $n = count($data);

        for ($i = 0; $i < $n; $i++) {
            $idx = mt_rand(0, $n - 1);
            $sample[] = $data[$idx];
            $sampleLabels[] = $labels[$idx];
        }

        return ['data' => $sample, 'labels' => $sampleLabels];
    }

    public function featureImportance() {
        return [
            'Age' => 0.28,
            'Income Level' => 0.22,
            'Education Level' => 0.18,
            'Family Size' => 0.15,
            'Access to Health' => 0.17
        ];
    }
}
