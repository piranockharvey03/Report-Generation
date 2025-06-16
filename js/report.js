document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('marksForm');
    const resultsTable = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
    const imageInput = document.getElementById('studentImageUrl');
    const imagePreview = document.getElementById('imagePreview');
  
    // Preview image when the user selects a file
    imageInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  
    // Handle form submission
    form.addEventListener('submit', (e) => {
      e.preventDefault(); // Stop default form submission
  
      const formData = new FormData(form);
  
      fetch('reports.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(data => {
          console.log('Success:', data);
        })
        .catch(error => {
          console.error('Error:', error);
        });
    });
  
    const studentName = document.getElementById('studentName').value;
    const classTeacherName = document.getElementById('classTeacherName').value; // Get class teacher's name
    const term = document.getElementById('term').value;
    const studentImageUrl = document.getElementById('imagePreview').src; // Get image URL
    const stage = document.getElementById('stage').value; // Get the selected stage
  
    const marks = {
      Mathematics: parseInt(document.getElementById('mathematics').value),
      Chemistry: parseInt(document.getElementById('chemistry').value),
      Biology: parseInt(document.getElementById('biology').value),
      Physics: parseInt(document.getElementById('physics').value),
      Geography: parseInt(document.getElementById('geography').value),
      History: parseInt(document.getElementById('history').value),
      Business: parseInt(document.getElementById('business').value),
      Economics: parseInt(document.getElementById('economics').value),
      ICT: parseInt(document.getElementById('ict').value),
      Global_P: parseInt(document.getElementById('globalP').value),
      Literature: parseInt(document.getElementById('literature').value),
      French: parseInt(document.getElementById('french').value),
      Mutoon: parseInt(document.getElementById('mutoon').value),
      Quran: parseInt(document.getElementById('qoran').value)
    };
  
    let totalMarks = 0;
    let totalSubjects = 0;
    let tableContent = '';
    for (const [subject, mark] of Object.entries(marks)) {
      if (mark < 0 || mark > 100) {
        alert(`Invalid marks for ${subject}. Please enter a value between 0 and 100.`);
        return;
      }
  
      const grade = getGrade(mark);
      totalMarks += mark;
      totalSubjects++;

      // Get grade from marks
    function getGrade(mark) {
      if (mark >= 80) return 'A';
      if (mark >= 75) return 'B+';
      if (mark >= 65) return 'B';
      if (mark >= 60) return 'C+';
      if (mark >= 50) return 'C';
      if (mark >= 45) return 'D+';
      if (mark >= 40) return 'D';
      return 'F';
    }
  
    // Get division from average marks
    function getDivision(average) {
      if (average >= 80) return 'First Division';
      if (average >= 65) return 'Second Division';
      if (average >= 50) return 'Third Division';
      if (average >= 40) return 'Pass Division';
      return 'Fail Division';
    }
  
      tableContent += `
        <tr>
          <td>${subject}</td>
          <td>${mark}</td>
          <td>${grade}</td>
        </tr>
      `;
    }
  
    // Calculate average marks
    const averageMarks = (totalMarks / totalSubjects).toFixed(2);
  
    // Calculate division
    const division = getDivision(averageMarks);
  
    // Display results in the table
    resultsTable.innerHTML = tableContent;
  
    // Handle Generate Report button
    document.getElementById('generateReport').addEventListener('click', () => {
      const tableContent = resultsTable.innerHTML;
  
      // Open a new window for the report
      const reportWindow = window.open('', 'Report', 'width=800,height=600');
      reportWindow.document.open();
      reportWindow.document.write(`
        <html>
          <head>
            <title>Student Report</title>
            <style>
              body {
                font-family: 'Times New Roman', Times, serif;
                background-color: rgb(42, 124, 111);
                padding: 10px;
                boder:100px solid black;
                margin-top:-40px;
              }
              .marks-section {
                margin-top: -70px;
              }
              .report-container {
                width: 80%;
                margin: 0 auto;
                background-color: white;
                padding: 20px; 
                display: flex;
                flex-direction: row-reverse;
              }
              .report-header {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                margin-bottom: 20px;
                margin-top: 30px;
              }
              .school-name {
                font-size: 25px;
                font-weight: bold;
                margin-top: 10px;
                margin-left: 70px;
              }
              .student-details {
                font-size: 20px;
                color: #333;
                margin-bottom: 20px;
                margin-top: -10px;
              }
              .marks-table {
                width: 70%;
                border-collapse: collapse;
                margin-right:100px;
              }
              .marks-table th, .marks-table td {
                border: 1px solid #ddd;
                text-align: center;
              }
              .marks-table th {
                background-color: #f4f4f4;
              }
              .logo {
                width: 230px;
                height: 70px;
              }
              .logopoint {
                margin-left: 60px;
              }
              .logo1 {
                width: 180px;
                height: 150px;
                margin-left: -530px;
                margin-bottom: -150px;
              }
              .logos {
                display: flex;
                gap: 15px;
                margin-left: -500px;
                margin-bottom:-800px;
              }
              .student-image {
                width: 150px;
                height: 150px;
                border-radius: 5%;
                margin-top: -100px;
                margin-bottom: 10px;
                margin-left: 400px;
              }
              .signatures {
                margin-top: 5px;
                display: flex;
                justify-content: space-between;
              }
              .signature {
                text-align: center;
                width: 45%;
              }
              .signature-line {
                width: 70%;
                border-top: 1px solid black;
                margin: 20px auto;
                margin-top: 50px;
              }
              .teacher-name {
                font-size: 20px;
                margin-top: -20px;
                font-weight: bold;
              }
              .total-marks {
                font-size: 20px;
                font-weight: bold;
                margin-top: 20px;
                text-align: right;
              }
              .average-marks {
                font-size: 20px;
                font-weight: bold;
                margin-top: 10px;
                text-align: right;
              }
              .division {
                font-size: 20px;
                font-weight: bold;
                margin-top: 10px;
                text-align: right;
              }
              .grading-scale {
                margin-top: -30px;
                font-size: 18px;
                font-weight: bold;
                margin-left:250px;
              }
              .scale-table {
                width: 20%;
                margin-top:-230px;
                margin-left:460px;
              }
              .scale-table th, .scale-table td {
                border: 1px solid #ddd;
                text-align: center;
              }
              .scale-table th {
                background-color: #f4f4f4;
              }
              .dashed-lines {
                margin-top: 20px;
                border-top: 1px dashed black;
                width: 80%;
                margin: 10px auto;
              }
              .dashed-lines:nth-of-type(2),
              .dashed-lines:nth-of-type(3),
              .dashed-lines:nth-of-type(4) {
                margin-top: 5px;
              }
              .weaknesses-label {
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                margin-top: -25px;
              }
              .strengths-label {
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                margin-top: 5px;
              }
              .additional-dashed-lines {
                margin-top: 5px;
                border-top: 1px dashed black;
                width: 80%;
                margin: 10px auto;
              }
              .Principal {
                margin-left: 520px;
                margin-top: -150px;
              }
              .schoolinfo1 {
                margin-top: -1px;
              }
              .schoolinfo2 {
                margin-top: -10px;
              }
              .schoolinfo3 {
                margin-top: -10px;
              }
              .schoolinfo4 {
                margin-top: -10px;
              }
              .studentD1 {
                margin-top: -15px;
              }
              .studentD2 {
                margin-top: -15px;
              }
              .summary {
                margin-left: 520px;
              }
              .P {
                font-size: 30px;
                margin-bottom: 10px;
              }
              .z {
                font-size: 30px;
                margin-top: 5px;
              }
              .image1 {
                margin-right: 300px;
                margin-bottom: -200px;
              }
              .image2 {
                margin-left: 380px;
                margin-top: 80px;
              }
            </style>
          </head>
          <body>
            <div class="report-container">
              <div class="report-header">
                <div class="logos">
                  <img class="logo" src="images/logo1.png" alt="School Logo" />
                  <img class="logo1" src="images/logo2.png" alt="School Logo" />
                </div>
                <h1 class="school-name">Harvey High School</h1>
                <p class="schoolinfo1">P.O Box 352, Jinja</p>
                <p class="schoolinfo2">Tel: +256 780 735 040, Email: info@harveyhighschool.ac.ug</p>
                <p class="schoolinfo3">Head Teacher: 041 454 239, P.O Box 159, Jinja</p>
                <p class="schoolinfo4">NCDC Center of Excellence - Kenya Advanced Certificate of Education (KACE)</p>
              </div>
  
              <div class="student-details">
                <img class="student-image" src="${studentImageUrl}" alt="Student Image" />
                <p class="studentD1">Student Name: <strong>${studentName}</strong></p>
                <p class="studentD2">Class Teacher: <strong>${classTeacherName}</strong></p>
                <p>Term: <strong>${term}</strong></p>
                <p>Stage: <strong>${stage}</strong></p>
              </div>
  
              <table class="marks-table">
                <thead>
                  <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Grade</th>
                  </tr>
                </thead>
                <tbody>
                  ${tableContent}
                </tbody>
              </table>
  
              <p class="total-marks">Total Marks: ${totalMarks}</p>
              <p class="average-marks">Average Marks: ${averageMarks}</p>
              <p class="division">Division: ${division}</p>
  
              <div class="grading-scale">
                <h2>Grading Scale</h2>
                <table class="scale-table">
                  <tr><th>Grade</th><th>Marks Range</th></tr>
                  <tr><td>A</td><td>80-100</td></tr>
                  <tr><td>B+</td><td>75-79</td></tr>
                  <tr><td>B</td><td>65-74</td></tr>
                  <tr><td>C+</td><td>60-64</td></tr>
                  <tr><td>C</td><td>50-59</td></tr>
                  <tr><td>D+</td><td>45-49</td></tr>
                  <tr><td>D</td><td>40-44</td></tr>
                  <tr><td>F</td><td>0-39</td></tr>
                </table>
              </div>
  
              <div class="signatures">
                <div class="signature">
                  <div class="signature-line"></div>
                  <p>Class Teacher</p>
                </div>
                <div class="signature">
                  <div class="signature-line"></div>
                  <p>Principal</p>
                </div>
              </div>
            </div>
          </body>
        </html>
      `);
      reportWindow.document.close();
      reportWindow.print();
    });
  });
  