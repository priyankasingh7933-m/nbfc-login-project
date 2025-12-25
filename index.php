<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NBFC Form</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background: #f3f5f9;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      font-family: 'Poppins', sans-serif;
      padding: 40px 0;
    }

    .form-container {
      background: #fff;
      border-radius: 10px;
      padding: 30px 40px;
      box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 1000px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 600;
      color: #333;
    }

    label {
      font-weight: 500;
      color: #333;
    }

    .input-group-text {
      background: #f1f1f1;
      color: #007bff;
      border: none;
    }

    .form-control, .form-select {
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 5px rgba(0,123,255,0.4);
      border-color: #007bff;
    }

    .btn-primary {
      background: #007bff;
      border: none;
      padding: 10px 25px;
      font-size: 1.1rem;
      border-radius: 6px;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: #0056b3;
      transform: scale(1.03);
    }

    .upload-label {
      font-weight: 500;
      margin-bottom: 6px;
      display: block;
    }

    @media (max-width: 768px) {
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <div class="form-container">
    <h2><i class="fa-solid fa-user-graduate me-2 text-primary"></i>NBFC Student Registration Form</h2>

    <form action="submit.php" method="POST" enctype="multipart/form-data">

      <div class="row g-3">

        <!-- Student Name -->
        <div class="col-md-4">
          <label>Student Name</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" name="studentName" class="form-control" placeholder="Enter Student Name" required>
          </div>
        </div>

        <!-- Course -->
        <div class="col-md-4">
          <label>Course</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-book-open"></i></span>
            <input type="text" name="courseName" class="form-control" placeholder="Enter Course" required>
          </div>
        </div>

        <!-- Batch -->
        <div class="col-md-4">
          <label>Batch</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-layer-group"></i></span>
            <input type="text" name="batch" class="form-control" placeholder="Enter Batch" required>
          </div>
        </div>

        <!-- Email -->
        <div class="col-md-4">
          <label>Email ID</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
            <input type="email" name="studentEmail" class="form-control" placeholder="Enter Email ID" required>
          </div>
        </div>

        <!-- Phone Number -->
        <div class="col-md-4">
          <label>Phone Number</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
            <input type="tel" name="studentNumber" class="form-control" placeholder="Enter Phone Number" required>
          </div>
        </div>

        <!-- Course Fee -->
        <div class="col-md-4">
          <label>Course Fee (₹)</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-money-bill-wave"></i></span>
            <input type="number" name="courseFee" class="form-control" placeholder="e.g. 10000" required>
          </div>
        </div>

        <!-- Advance Fee -->
        <div class="col-md-4">
          <label>Advance Fee (₹)</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-wallet"></i></span>
            <input type="number" name="advanceFee" class="form-control" placeholder="e.g. 2000">
          </div>
        </div>

        <!-- EMI Start Date -->
        <div class="col-md-4">
          <label>EMI Start Date</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
            <input type="date" name="emiStart" class="form-control" required>
          </div>
        </div>

        <!-- EMI Duration -->
        <div class="col-md-4">
          <label>EMI Duration (months)</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
            <select name="emiTenure" class="form-select" required>
              <option value="">Select Duration</option>
              <option value="3 Months">3 Months</option>
              <option value="6 Months">6 Months</option>
              <option value="9 Months">9 Months</option>
            </select>
          </div>
        </div>

        <!-- Parent Name -->
        <div class="col-md-4">
          <label>Parent's Name</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user-tie"></i></span>
            <input type="text" name="parentName" class="form-control" placeholder="Enter Parent's Name" required>
          </div>
        </div>

        <!-- Parent Mobile -->
        <div class="col-md-4">
          <label>Parent's Mobile</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
            <input type="tel" name="parentMobile" class="form-control" placeholder="Enter Parent's Mobile" required>
          </div>
        </div>

        <!-- Aadhar -->
        <div class="col-md-4">
          <label>Aadhar Number</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
            <input type="text" name="aadhar" class="form-control" placeholder="Enter Aadhar Number" required>
          </div>
        </div>

        <!-- PAN -->
        <div class="col-md-4">
          <label>PAN Number</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-address-card"></i></span>
            <input type="text" name="pan" class="form-control" placeholder="Enter PAN Number" required>
          </div>
        </div>

        <!-- Account -->
        <div class="col-md-4">
          <label>Account Number</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-piggy-bank"></i></span>
            <input type="text" name="account" class="form-control" placeholder="Enter Account Number" required>
          </div>
        </div>

        <!-- IFSC -->
        <div class="col-md-4">
          <label>IFSC Code</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-code"></i></span>
            <input type="text" name="ifsc" class="form-control" placeholder="Enter IFSC Code" required>
          </div>
        </div>

        <!-- Upload Document -->
        <div class="col-md-6">
          <label class="upload-label">Upload Document / ID Proof</label>
          <input type="file" name="document" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>

      </div>

      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-paper-plane me-2"></i>Save Student
        </button>
      </div>

    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
