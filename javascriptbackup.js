/*document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('marksForm');
    const resultsTable = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
    const imageInput = document.getElementById('studentImageUrl');
    const imagePreview = document.getElementById('imagePreview');
  
    // Preview image when the user selects a file
    imageInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
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
                /* 
                .dashed-lines {
                  margin-top: 20px;
                  border-top: 1px dashed black;
                  width: 80%;
                  margin: 10px auto;
                }
                .dashed-lines:nth-of-type(2) {
                  margin-top: 5px;
                }
                .dashed-lines:nth-of-type(3) {
                  margin-top: 5px;
                }
                .dashed-lines:nth-of-type(4) {
                  margin-top: 5px;
                }
             
                .weaknesses-label
                 {
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
                  .Principal{
                  margin-left:520px;
                  margin-top:-150px;
                  }
                 
                  .schoolinfo1{
                  margin-top: -1px;
                  }
                   .schoolinfo2{
                  margin-top: -10px;
                  }
                   .schoolinfo3{
                 margin-top: -10px;
                  }
                   .schoolinfo4{
                  margin-top: -10px;
                  }
                  .studentD1{
                  margin-top: -15px;
                  }
                  .studentD2{
                  margin-top: -15px;
                  }
                  .summary{
                  margin-left:520px;
            
                  }
                
                  .P{
                  font-size: 30px;
                  margin-bottom:10pxpx;
                  }
                  .z{
                  font-size: 30px;
                  margin-top: 5px;
                   }
                  
                  .image1{
                  margin-right:300px;
                  margin-bottom:-200px;
                  }
                  
                  .image2{
                  margin-left:380px;
                  margin-top:80px;
                  }
   
              </style>
            </head>
            <body>
              <div class="report-container">
                <div style="flex: 2;">
                  <header class="report-header">
                    <img class="logo1" src="Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
                    <div class="school-name">
                      <b>HARVEY INTERNATIONAL ACADEMY</b>
                    </div>
                      <div class="school-info">
                      <p class="schoolinfo1">Location:Kampla,Uganda,EastAfrica<p/>
                      <p class="schoolinfo2">Email:harveyinternationalacademy@gmail.com</p>
                      <p class="schoolinfo3">www.harveyeduc.com</p>
                      <p class="schoolinfo4">Tel:(+250) 777 777 777/ (+777) 7777777</p>
                    </div>
                    
                    <div class="student-details">
                      <h3 >${term} REPORT CARD</h3>
                      <h3 class="studentD1"><b>${studentName}</b></h3>
                      <h4 class="studentD2"><b> ${stage}</b></h4> <!-- Display Stage -->
                    </div>
                    <img src="${studentImageUrl}" alt="Student Image" class="student-image">
                  </header>
  
                  <section class="marks-section">
                    <h3>SUBJECT RESULTS</h3>
                    <table class="marks-table">
                      <thead>
                        <tr>
                          <th>Subject</th>
                          <th>Marks</th>
                          <th>Grade</th>
                        </tr>
                        <tr>
                        <th>Every Subject is Marked out of 100</th>
                        </tr>
                      </thead>
                      <tbody>
                        ${tableContent}
                      </tbody>
                    </table>
                  </section>
                  <table id="resultsTable">
            <thead>
             <div class="gradingscale">
                  <div class="grading-scale">
                    <table class="scale-table">
                      <thead>
                       <tr>
                        <th>GRADING SCALE</th>
                        </tr>
                        <tr>
                          <th>GRADE</th>
                          <th>MARKS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>A</td>
                          <td>85 and above</td>
                        </tr>
                        <tr>
                          <td>B</td>
                          <td>70 - 84</td>
                        </tr>
                        <tr>
                          <td>C</td>
                          <td>50 - 69</td>
                        </tr>
                        <tr>
                          <td>D</td>
                          <td>35 - 49</td>
                        </tr>
                        <tr>
                          <td>F</td>
                          <td>Below 35</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>  
            </thead>
  
          <table class="summary">
        <thead>
         <tr>
         <th class"summary1">Total Marks</th>
         <th class"summary1">Average</th>
         <th class"summary1">Division</th>
         </tr>
       </thead>
      <tbody >
     <tr>
     <td>  ${totalMarks}</td>
     <td> ${averageMarks}</td>
     <td> ${division}</td>
     </tr>
     </tbody>
     </table>
        <div class="signatures">
          <div class="signature">
            <p class="weaknesses-label">WEAKNESSES</p>        
             <div class="additional-dashed-lines"></div>
                <p class="strengths-label">STRENGTHS</p>
                  <div class="additional-dashed-lines"></div>
                    <p class="z">Class Teacher</p>
                    <p class="teacher-name">${classTeacherName}</p>
                  <div class="signature-line"></div>
              <div class="Principal">
            <div class="signature">
          <p class="P">Principal</p>
        <p class="teacher-name">KAGANDA JAMES</p>
      </div>
    <img src="Principals Sign.png" alt="Principal's Signature" style="width: 100px; height: 80px; margin-left:-30px;margin-bottom:-30px;margin-top:-10px;">
  </div>             
        <div class="image1">
           <img  src="delf.png" alt="delf">
              </div>
              <div class="image2" >
              <img src="cambdrige.png" alt="cambridge" width:200px>
            </div>
          </body>
  </html>
        `);
        reportWindow.document.close();
  
        // Print after content is loaded
        reportWindow.onload = function() {
          setTimeout(()=>{
            reportWindow.print();
          },500); 
        };
      });
    });
  
    // Function to determine grade based on marks
    function getGrade(marks) {
      if (marks >= 85) return 'A';
      if (marks >= 70) return 'B';
      if (marks >= 50) return 'C';
      if (marks >= 35) return 'D';
      return 'F';
    }
  
    // Function to determine division based on average marks
    function getDivision(average) {
      if (average >= 90) return 'DIV1';
      if (average >= 80) return 'DIV2';
      if (average >= 70) return 'DIV3';
      if (average >= 60) return 'DIV4';
      return 'DIV5';
  };
  
  </div>
  */