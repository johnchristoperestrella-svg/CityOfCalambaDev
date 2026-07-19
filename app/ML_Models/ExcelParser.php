<?php
namespace App\ML_Models;

/**
 * Excel Data Parser & Processor
 * Handles bulk import of household and individual data from Excel files
 */
class ExcelParser {
    private $data = [];
    private $errors = [];
    private $warnings = [];
    private $processedRecords = 0;

    public function parseFile($filePath) {
        try {
            // Check file exists
            if (!file_exists($filePath)) {
                $this->errors[] = "File not found: $filePath";
                return false;
            }

            // Check file extension
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), ['xlsx', 'xls', 'csv'])) {
                $this->errors[] = "Invalid file format. Only Excel (.xlsx, .xls) and CSV files are supported.";
                return false;
            }

            // For Excel files, try parseExcel first; for CSV, use parseCSV directly
            $ext = strtolower($ext);
            if ($ext === 'csv') {
                return $this->parseCSV($filePath);
            } else {
                // Try Excel parsing for .xlsx and .xls
                $result = $this->parseExcel($filePath);
                if (!$result) {
                    // If Excel parsing fails, try CSV as fallback
                    return $this->parseCSV($filePath);
                }
                return $result;
            }
        } catch (Exception $e) {
            $this->errors[] = "Error parsing file: " . $e->getMessage();
            return false;
        }
    }

    private function parseExcel($filePath) {
        try {
            // For maximum compatibility, always try to convert Excel to CSV
            // This works with both .xlsx and .xls files
            
            if (extension_loaded('zip')) {
                // Try to extract Excel as ZIP and read XML for XLSX
                $zip = new \ZipArchive();
                if ($zip->open($filePath) === true) {
                    $xmlData = $zip->getFromName('xl/worksheets/sheet1.xml');
                    $sharedStrings = $zip->getFromName('xl/sharedStrings.xml');
                    $zip->close();
                    
                    if ($xmlData && $sharedStrings) {
                        return $this->parseExcelXML($xmlData, $sharedStrings, $filePath);
                    }
                }
            }

            // Fallback: treat as CSV (works for .xls and simple .xlsx exports)
            return $this->parseCSV($filePath);
        } catch (Exception $e) {
            // Silent catch - just fallback to CSV
            return $this->parseCSV($filePath);
        }
    }

    private function parseExcelXML($xmlData, $sharedStrings, $filePath) {
        try {
            $this->data = [];
            
            // Parse shared strings
            $sharedStringsXml = simplexml_load_string($sharedStrings);
            $strings = [];
            if ($sharedStringsXml) {
                $stringIndex = 0;
                foreach ($sharedStringsXml->si as $si) {
                    $strings[$stringIndex] = (string)$si->t;
                    $stringIndex++;
                }
            }
            
            // Parse worksheet
            $xml = simplexml_load_string($xmlData);
            if (!$xml) {
                return $this->parseCSV($filePath); // Fallback
            }

            $headers = null;
            foreach ($xml->sheetData->row as $row) {
                $rowData = [];
                foreach ($row->c as $cell) {
                    $value = '';
                    
                    // Check if it's a string reference
                    if ((string)$cell['t'] === 's') {
                        $stringIdx = (int)$cell->v;
                        if (isset($strings[$stringIdx])) {
                            $value = $strings[$stringIdx];
                        }
                    } else {
                        $value = (string)$cell->v;
                    }
                    
                    $rowData[] = $value;
                }
                
                if (!empty(array_filter($rowData))) {
                    if ($headers === null) {
                        // First row is headers - normalize them
                        $headers = array_map('trim', $rowData);
                        $headers = $this->normalizeHeaders($headers);
                    } else {
                        // Map to normalized headers
                        $mapped = [];
                        foreach ($headers as $idx => $header) {
                            $mapped[$header] = trim($rowData[$idx] ?? '');
                        }
                        if (!empty(array_filter($mapped))) {
                            $this->data[] = $mapped;
                        }
                    }
                }
            }

            return $this->validateData();
        } catch (Exception $e) {
            // If XML parsing fails, fallback to CSV
            return $this->parseCSV($filePath);
        }
    }

    private function parseCSV($filePath) {
        $this->data = [];
        $handle = fopen($filePath, 'r');

        if (!$handle) {
            $this->errors[] = "Unable to open CSV file";
            return false;
        }

        $headers = null;
        $rowNum = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // First row is headers
            if ($headers === null) {
                $headers = array_map('trim', $row);
                // Normalize header names - map template headers to internal names
                $headers = $this->normalizeHeaders($headers);
                continue;
            }

            // Map row data to headers
            $rowData = [];
            foreach ($headers as $idx => $header) {
                $rowData[$header] = trim($row[$idx] ?? '');
            }

            $this->data[] = $rowData;
        }

        fclose($handle);

        return $this->validateData();
    }

    private function normalizeHeaders($headers) {
        $normalized = [];
        foreach ($headers as $header) {
            $lower = strtolower(trim($header));
            
            // Map template headers to internal column names
            if (stripos($lower, 'household head') !== false || stripos($lower, 'name') !== false) {
                $normalized[] = 'name';
            } elseif (stripos($lower, 'weight') !== false) {
                $normalized[] = 'weight';
            } elseif (stripos($lower, 'address') !== false) {
                $normalized[] = 'address';
            } elseif (stripos($lower, 'salary') !== false) {
                $normalized[] = 'salary';
            } elseif (stripos($lower, 'family') !== false || stripos($lower, 'members') !== false) {
                $normalized[] = 'family_members';
            } elseif (stripos($lower, 'barangay') !== false) {
                $normalized[] = 'barangay';
            } else {
                // Keep other columns as-is
                $normalized[] = $lower;
            }
        }
        return $normalized;
    }

    private function validateData() {
        if (empty($this->data)) {
            $this->errors[] = "No data found in file";
            return false;
        }

        // Validate each record
        $validData = [];
        $rowNum = 2; // Account for header row

        foreach ($this->data as $record) {
            $rowNum++;
            
            // Ensure record is an array (handle both formats)
            if (!is_array($record)) {
                continue;
            }
            
            // Normalize keys to lowercase
            $record = array_change_key_case($record, CASE_LOWER);
            
            $errors = $this->validateRecord($record, $rowNum);

            if (!empty($errors)) {
                $this->errors = array_merge($this->errors, $errors);
                continue;
            }

            $validData[] = $this->normalizeRecord($record);
            $this->processedRecords++;
        }

        if (empty($validData) && !empty($this->errors)) {
            return false;
        }

        $this->data = $validData;
        return true;
    }

    private function validateRecord($record, $rowNum) {
        $errors = [];

        // Check name
        if (empty($record['name'] ?? '')) {
            $errors[] = "Row $rowNum: Household Head Name is required";
        }

        // Check weight
        if (empty($record['weight'] ?? '')) {
            $errors[] = "Row $rowNum: Weight is required";
        } elseif (!is_numeric($record['weight'])) {
            $errors[] = "Row $rowNum: Weight must be a number";
        }

        // Address is optional
        // Check address
        if (empty($record['address'] ?? '')) {
            $errors[] = "Row $rowNum: Address is required";
        }

        // Check salary
        if (empty($record['salary'] ?? '')) {
            $errors[] = "Row $rowNum: Salary is required";
        } elseif (!is_numeric(str_replace([',', '.'], '', $record['salary']))) {
            $errors[] = "Row $rowNum: Salary must be a number";
        }

        // Check family members
        if (empty($record['family_members'] ?? '')) {
            $errors[] = "Row $rowNum: Family Members is required";
        } elseif (!is_numeric($record['family_members']) || $record['family_members'] < 1) {
            $errors[] = "Row $rowNum: Family members must be a positive number";
        }

        return $errors;
    }

    private function normalizeRecord($record) {
        $name = $record['name'] ?? '';
        $address = $record['address'] ?? '';
        
        // Use sanitize_input if available, otherwise just trim
        if (function_exists('sanitize_input')) {
            $name = sanitize_input($name);
            $address = sanitize_input($address);
        } else {
            $name = trim(strip_tags($name));
            $address = trim(strip_tags($address));
        }
        
        return [
            'name' => $name,
            'weight' => (float)($record['weight'] ?? 0),
            'address' => $address,
            'salary' => (float)str_replace([',', ' '], '', $record['salary'] ?? 0),
            'family_members' => (int)($record['family_members'] ?? 0),
            'status' => 'pending_verification'
        ];
    }

    public function getData() {
        return $this->data;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getWarnings() {
        return $this->warnings;
    }

    public function getProcessedCount() {
        return $this->processedRecords;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }
}
