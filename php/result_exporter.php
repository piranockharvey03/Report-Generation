<?php

class ResultExporter {

    public function exportToPDF($studentData, $filename = null) {
        if (!$filename) {
            $filename = 'student_report_' . $studentData['student_id'] . '.html';
        }

        $html = $this->generateHTMLReport($studentData);

        // For now, return HTML that can be printed as PDF
        // In a production system, you would use TCPDF or similar library
        return [
            'content' => $html,
            'filename' => $filename,
            'type' => 'text/html'
        ];
    }

    public function exportToExcel($studentData, $filename = null) {
        if (!$filename) {
            $filename = 'student_report_' . $studentData['student_id'] . '.csv';
        }

        $csv = $this->generateCSVReport($studentData);

        return [
            'content' => $csv,
            'filename' => $filename,
            'type' => 'text/csv'
        ];
    }

    private function generateHTMLReport($data) {
        $gradeClass = $this->getGradeClass($data['grade']);

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Academic Report - {$data['student_name']}</title>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'DejaVu Sans', sans-serif; margin: 20px; color: #333; }
                .header { text-align: center; border-bottom: 3px solid #2563eb; padding-bottom: 20px; margin-bottom: 30px; }
                .school-name { font-size: 24px; font-weight: bold; color: #1e40af; margin-bottom: 5px; }
                .report-title { font-size: 18px; color: #6b7280; }
                .student-info { background: #f8fafc; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
                .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
                .info-section { background: white; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb; }
                .info-section h4 { color: #1f2937; margin-bottom: 10px; font-size: 16px; }
                .info-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
                .results-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .results-table th, .results-table td { border: 1px solid #d1d5db; padding: 12px; text-align: center; }
                .results-table th { background: #f3f4f6; font-weight: 600; color: #374151; }
                .grade-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
                .grade-a { background: #dcfce7; color: #166534; }
                .grade-b { background: #dbeafe; color: #1e40af; }
                .grade-c { background: #fef3c7; color: #92400e; }
                .grade-f { background: #fee2e2; color: #991b1b; }
                .overall-grade { font-size: 48px; font-weight: bold; text-align: center; margin: 20px 0; }
                .footer { text-align: center; margin-top: 40px; color: #6b7280; font-size: 12px; }
                @media print { body { margin: 10px; } .no-print { display: none; } }
            </style>
        </head>
        <body>
            <div class='header'>
                <div class='school-name'>The International Academy</div>
                <div class='report-title'>Official Academic Report Card</div>
            </div>

            <div class='student-info'>
                <div class='info-grid'>
                    <div class='info-section'>
                        <h4>Student Details</h4>
                        <div class='info-row'><span>Student Name:</span> <span>{$data['student_name']}</span></div>
                        <div class='info-row'><span>Student ID:</span> <span>{$data['student_id']}</span></div>
                        <div class='info-row'><span>Class Teacher:</span> <span>{$data['class_teacher']}</span></div>
                        <div class='info-row'><span>Term:</span> <span>{$data['term']}</span></div>
                        <div class='info-row'><span>Academic Year:</span> <span>{$data['stage']}</span></div>
                    </div>

                    <div class='info-section'>
                        <h4>Academic Performance</h4>
                        <div class='info-row'><span>Total Marks:</span> <span>{$data['total_marks']}/1400</span></div>
                        <div class='info-row'><span>Average Score:</span> <span>{$data['average']}%</span></div>
                        <div class='info-row'><span>GPA:</span> <span>{$data['gpa']}/4.0</span></div>
                        <div class='info-row'><span>Grade:</span> <span class='grade-badge grade-{$gradeClass}'>{$data['grade']}</span></div>
                        <div class='info-row'><span>Division:</span> <span>{$data['division']}</span></div>
                    </div>
                </div>
            </div>

            <table class='results-table'>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Marks Obtained</th>
                        <th>Maximum Marks</th>
                        <th>Grade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";

        foreach ($data['subject_grades'] as $subject => $gradeData) {
            $subjectGradeClass = $this->getGradeClass($gradeData['grade']);
            $status = $gradeData['status'] == 'Pass' ? 'Pass' : 'Fail';

            $html .= "
                    <tr>
                        <td style='text-align: left;'>{$subject}</td>
                        <td>{$gradeData['marks']}</td>
                        <td>100</td>
                        <td><span class='grade-badge grade-{$subjectGradeClass}'>{$gradeData['grade']}</span></td>
                        <td>{$status}</td>
                    </tr>";
        }

        $html .= "
                </tbody>
            </table>

            <div class='overall-grade' style='color: " . $this->getGradeColor($data['grade']) . ";'>
                {$data['grade']}
            </div>

            <div class='footer'>
                <p>This is an official academic report from The International Academy.</p>
                <p>Report Generated: " . date('Y-m-d H:i:s') . "</p>
                <p>© 2025 The International Academy. All rights reserved.</p>
            </div>
        </body>
        </html>";

        return $html;
    }

    private function generateCSVReport($data) {
        $csv = "The International Academy - Academic Report\n";
        $csv .= "Student Report Card\n\n";

        $csv .= "Student Information\n";
        $csv .= "Name,{$data['student_name']}\n";
        $csv .= "Student ID,{$data['student_id']}\n";
        $csv .= "Class Teacher,{$data['class_teacher']}\n";
        $csv .= "Term,{$data['term']}\n";
        $csv .= "Stage,{$data['stage']}\n\n";

        $csv .= "Academic Performance\n";
        $csv .= "Total Marks,{$data['total_marks']}/1400\n";
        $csv .= "Average Score,{$data['average']}%\n";
        $csv .= "GPA,{$data['gpa']}/4.0\n";
        $csv .= "Grade,{$data['grade']}\n";
        $csv .= "Division,{$data['division']}\n\n";

        $csv .= "Subject-wise Breakdown\n";
        $csv .= "Subject,Marks,Grade,Status\n";

        foreach ($data['subject_grades'] as $subject => $gradeData) {
            $csv .= "\"{$subject}\",{$gradeData['marks']},{$gradeData['grade']},{$gradeData['status']}\n";
        }

        $csv .= "\nReport Generated," . date('Y-m-d H:i:s') . "\n";

        return $csv;
    }

    private function getGradeClass($grade) {
        return match($grade) {
            'A+', 'A' => 'a',
            'B+', 'B' => 'b',
            'C+', 'C' => 'c',
            default => 'f'
        };
    }

    private function getGradeColor($grade) {
        return match($grade) {
            'A+', 'A' => '#16a34a',
            'B+', 'B' => '#2563eb',
            'C+', 'C' => '#ca8a04',
            'D' => '#ea580c',
            default => '#dc2626'
        };
    }
}

// Export functionality for student results page
if (isset($_GET['export']) && isset($_GET['format']) && isset($_SESSION['current_student_data'])) {
    $exporter = new ResultExporter();
    $data = $_SESSION['current_student_data'];

    if ($_GET['format'] === 'pdf') {
        $export = $exporter->exportToPDF($data);
        header('Content-Type: ' . $export['type']);
        header('Content-Disposition: attachment; filename="' . $export['filename'] . '"');
        echo $export['content'];
    } elseif ($_GET['format'] === 'excel') {
        $export = $exporter->exportToExcel($data);
        header('Content-Type: ' . $export['type']);
        header('Content-Disposition: attachment; filename="' . $export['filename'] . '"');
        echo $export['content'];
    }
    exit();
}

?>
