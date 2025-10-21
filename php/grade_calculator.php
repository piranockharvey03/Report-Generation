<?php

class GradeCalculator {
    private $passingGrade = 40;
    private $totalSubjects = 14;
    private $maxMarksPerSubject = 100;

    public function __construct($passingGrade = 40) {
        $this->passingGrade = $passingGrade;
    }

    public function calculateResults($marks) {
        // Validate input
        if (!is_array($marks) || count($marks) !== $this->totalSubjects) {
            throw new Exception("Invalid marks data provided");
        }

        // Calculate basic statistics
        $total = array_sum($marks);
        $maxPossible = $this->totalSubjects * $this->maxMarksPerSubject;
        $average = $total / $this->totalSubjects;

        // Calculate grade
        $grade = $this->calculateGrade($average);

        // Calculate GPA
        $gpa = $this->calculateGPA($marks);

        // Calculate division
        $division = $this->calculateDivision($average);

        // Calculate subject grades
        $subjectGrades = $this->calculateSubjectGrades($marks);

        // Calculate percentile rank
        $percentile = $this->calculatePercentile($average);

        // Generate recommendations
        $recommendations = $this->generateRecommendations($marks, $average);

        return [
            'total_marks' => $total,
            'max_possible' => $maxPossible,
            'average' => round($average, 2),
            'grade' => $grade,
            'gpa' => round($gpa, 2),
            'division' => $division,
            'subject_grades' => $subjectGrades,
            'percentile' => round($percentile, 1),
            'recommendations' => $recommendations,
            'passed_subjects' => count(array_filter($marks, fn($mark) => $mark >= $this->passingGrade)),
            'failed_subjects' => count(array_filter($marks, fn($mark) => $mark < $this->passingGrade))
        ];
    }

    private function calculateGrade($average) {
        if ($average >= 90) return 'A+';
        elseif ($average >= 80) return 'A';
        elseif ($average >= 70) return 'B+';
        elseif ($average >= 60) return 'B';
        elseif ($average >= 50) return 'C+';
        elseif ($average >= 40) return 'C';
        elseif ($average >= 30) return 'D';
        else return 'F';
    }

    private function calculateGPA($marks) {
        $totalPoints = 0;
        foreach ($marks as $mark) {
            $totalPoints += $this->markToGPA($mark);
        }
        return $totalPoints / count($marks);
    }

    private function markToGPA($mark) {
        if ($mark >= 90) return 4.0;
        elseif ($mark >= 80) return 3.7;
        elseif ($mark >= 70) return 3.3;
        elseif ($mark >= 60) return 3.0;
        elseif ($mark >= 50) return 2.7;
        elseif ($mark >= 40) return 2.3;
        elseif ($mark >= 30) return 2.0;
        else return 0.0;
    }

    private function calculateDivision($average) {
        if ($average >= 80) return 'Division I (Distinction)';
        elseif ($average >= 60) return 'Division II (Credit)';
        elseif ($average >= 40) return 'Division III (Pass)';
        else return 'Fail';
    }

    private function calculateSubjectGrades($marks) {
        $grades = [];
        $subjects = [
            'Mathematics', 'Chemistry', 'Biology', 'Physics', 'Geography',
            'History', 'Business', 'Economics', 'ICT', 'Global Perspectives',
            'Literature', 'French', 'Mutoon', 'Qoran'
        ];

        foreach ($marks as $index => $mark) {
            $grades[$subjects[$index]] = [
                'marks' => $mark,
                'grade' => $this->calculateGrade($mark),
                'status' => $mark >= $this->passingGrade ? 'Pass' : 'Fail'
            ];
        }

        return $grades;
    }

    private function calculatePercentile($average) {
        // This would typically query historical data
        // For now, return a simulated percentile
        if ($average >= 90) return 95;
        elseif ($average >= 80) return 85;
        elseif ($average >= 70) return 75;
        elseif ($average >= 60) return 65;
        elseif ($average >= 50) return 55;
        elseif ($average >= 40) return 45;
        else return 20;
    }

    private function generateRecommendations($marks, $average) {
        $recommendations = [];

        // Overall performance recommendations
        if ($average >= 80) {
            $recommendations[] = "Excellent performance! Keep up the outstanding work.";
        } elseif ($average >= 60) {
            $recommendations[] = "Good performance. Focus on weak subjects to achieve excellence.";
        } elseif ($average >= 40) {
            $recommendations[] = "Satisfactory performance. Work harder on difficult subjects.";
        } else {
            $recommendations[] = "Needs significant improvement. Seek additional help and tutoring.";
        }

        // Subject-specific recommendations
        $weakSubjects = array_filter($marks, fn($mark) => $mark < 50);
        if (count($weakSubjects) > 0) {
            $recommendations[] = "Pay special attention to: " . implode(", ", array_keys($weakSubjects));
        }

        // Strength identification
        $strongSubjects = array_filter($marks, fn($mark) => $mark >= 80);
        if (count($strongSubjects) > 0) {
            $recommendations[] = "Strong performance in: " . implode(", ", array_keys($strongSubjects));
        }

        return $recommendations;
    }

    public function exportToPDF($studentData, $filename = 'student_report.pdf') {
        // PDF export functionality would be implemented here
        // This is a placeholder for PDF generation
        return "PDF export functionality would be implemented";
    }

    public function exportToExcel($studentData, $filename = 'student_report.xlsx') {
        // Excel export functionality would be implemented here
        // This is a placeholder for Excel generation
        return "Excel export functionality would be implemented";
    }

    public function generateReportCard($studentData) {
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Report Card</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .info-section { margin-bottom: 20px; }
                .results-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                .results-table th, .results-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
                .results-table th { background-color: #f2f2f2; }
                .grade-{strtolower($studentData['grade'])} { font-weight: bold; color: " .
                    match($studentData['grade']) {
                        'A+', 'A' => '#22c55e',
                        'B+', 'B' => '#3b82f6',
                        'C+', 'C' => '#eab308',
                        'D' => '#f97316',
                        default => '#ef4444'
                    } . ";
                }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>The International Academy</h1>
                <h2>Academic Report Card</h2>
            </div>

            <div class='info-section'>
                <h3>Student Information</h3>
                <p><strong>Name:</strong> {$studentData['student_name']}</p>
                <p><strong>Term:</strong> {$studentData['term']}</p>
                <p><strong>Stage:</strong> {$studentData['stage']}</p>
            </div>

            <div class='info-section'>
                <h3>Academic Performance</h3>
                <p><strong>Total Marks:</strong> {$studentData['total_marks']}/{$studentData['max_possible']}</p>
                <p><strong>Average:</strong> {$studentData['average']}%</p>
                <p><strong>Grade:</strong> <span class='grade-{$studentData['grade']}'>{$studentData['grade']}</span></p>
                <p><strong>GPA:</strong> {$studentData['gpa']}</p>
                <p><strong>Division:</strong> {$studentData['division']}</p>
            </div>

            <table class='results-table'>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($studentData['subject_grades'] as $subject => $data) {
            $html .= "
                    <tr>
                        <td>{$subject}</td>
                        <td>{$data['marks']}</td>
                        <td>{$data['grade']}</td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>
        </body>
        </html>";

        return $html;
    }
}

// Usage example:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $calculator = new GradeCalculator();

    try {
        $marks = [
            $_POST['mathematics'] ?? 0,
            $_POST['chemistry'] ?? 0,
            $_POST['biology'] ?? 0,
            $_POST['physics'] ?? 0,
            $_POST['geography'] ?? 0,
            $_POST['history'] ?? 0,
            $_POST['business'] ?? 0,
            $_POST['economics'] ?? 0,
            $_POST['ict'] ?? 0,
            $_POST['globalP'] ?? 0,
            $_POST['literature'] ?? 0,
            $_POST['french'] ?? 0,
            $_POST['mutoon'] ?? 0,
            $_POST['qoran'] ?? 0
        ];

        $results = $calculator->calculateResults($marks);

        // Store in session for later use
        $_SESSION['student_results'] = $results;

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($results);

    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
}

?>
