<?php
/**
 * Calculates academic division status and maps corresponding Bootstrap alert colors
 * @param float $cgpa
 * @return array
 */
function calculateGrade($cgpa) {
    $result = array(
        'status' => '',
        'color' => ''
    );
    if ($cgpa >= 8.5) {
        $result['status'] = "First Class with Distinction";
        $result['color'] = "success"; // Green styling
    } elseif ($cgpa >= 6.5) {
        $result['status'] = "First Class Division";
        $result['color'] = "primary"; // Blue styling
    } elseif ($cgpa >= 5.0) {
        $result['status'] = "Second Class Division";
        $result['color'] = "warning"; // Orange/Yellow styling
    } else {
        $result['status'] = "Pass Class (Needs Improvement)";
        $result['color'] = "danger"; // Red styling
    }

    return $result;
}
?>