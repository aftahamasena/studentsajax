<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD with AJAX and External API</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        h1 {
            font-weight: 700;
            color: #343a40;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            border-radius: 8px;
            border-color: #dee2e6;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #4e555b;
        }

        .table-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table {
            background-color: #ffffff;
            margin-bottom: 0;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .table td {
            font-size: 14px;
            color: #495057;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .table img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .table img:hover {
            transform: scale(1.1);
        }

        .table-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .table-actions .btn {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Student Management</h1>

        <button class="btn btn-primary mb-3" id="add-student-btn">Add New Student</button>

        <form id="student-form" class="form-container d-none">
            <h4 id="form-title" class="form-title">Add New Student</h4>
            <input type="hidden" id="nis" name="nis">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo URL</label>
                <input type="url" class="form-control" id="photo" name="photo" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <button type="submit" class="btn btn-primary" id="submit-btn">Add Student</button>
            <button type="button" class="btn btn-secondary" id="cancel-btn">Cancel</button>
        </form>

        <div class="table-container mt-4">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>NIS</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="students-table">
                </tbody>
            </table>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            const apiUrl = 'https://66c5fd49134eb8f434966100.mockapi.io/practiceapi/students';

            function loadStudents() {
                $.get(apiUrl, function(data) {
                    let rows = '';
                    data.forEach(student => {
                        rows += `
                            <tr>
                                <td><img src="${student.photo}" alt="Student Photo"></td>
                                <td>${student.nis}</td>
                                <td>${student.name}</td>
                                <td>${student.email}</td>
                                <td>${student.address}</td>
                                <td>${student.phone}</td>
                                <td class="table-actions">
                                    <button class="btn btn-warning btn-sm" onclick="editStudent('${student.nis}')"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteStudent('${student.nis}')"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#students-table').html(rows);
                });
            }

            $('#student-form').submit(function(event) {
                event.preventDefault();

                const nis = $('#nis').val();
                const studentData = {
                    name: $('#name').val(),
                    photo: $('#photo').val(),
                    email: $('#email').val(),
                    address: $('#address').val(),
                    phone: $('#phone').val(),
                };

                const method = nis ? 'PUT' : 'POST';
                const url = nis ? `${apiUrl}/${nis}` : apiUrl;

                $.ajax({
                    url: url,
                    method: method,
                    contentType: 'application/json',
                    data: JSON.stringify(studentData),
                    success: function() {
                        loadStudents();
                        $('#student-form').addClass('d-none');
                        $('#student-form')[0].reset();
                        $('#nis').val('');
                        $('#submit-btn').text('Add Student');
                        $('#form-title').text('Add New Student');
                    }
                });
            });

            function editStudent(nis) {
                $.get(`${apiUrl}/${nis}`, function(student) {
                    $('#nis').val(student.nis);
                    $('#name').val(student.name);
                    $('#photo').val(student.photo);
                    $('#email').val(student.email);
                    $('#address').val(student.address);
                    $('#phone').val(student.phone);
                    $('#student-form').removeClass('d-none');
                    $('#submit-btn').text('Update Student');
                    $('#form-title').text('Edit Student');
                });
            }

            function showAddForm() {
                $('#student-form').removeClass('d-none');
                $('#student-form')[0].reset();
                $('#submit-btn').text('Add Student');
                $('#form-title').text('Add New Student');
            }

            function deleteStudent(nis) {
                if (confirm('Are you sure you want to delete this student?')) {
                    $.ajax({
                        url: `${apiUrl}/${nis}`,
                        method: 'DELETE',
                        success: function() {
                            loadStudents();
                        }
                    });
                }
            }

            $('#add-student-btn').click(showAddForm);
            $('#cancel-btn').click(function() {
                $('#student-form').addClass('d-none');
                $('#student-form')[0].reset();
                $('#nis').val('');
            });

            loadStudents();
        </script>
    </div>
</body>

</html>
