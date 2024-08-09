<!DOCTYPE html>
<html>
    <head>
        <meta name="author" content="Alex Madrigal">
        <title>Clínica Psicología</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container mt-5">
            <div class="card">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#inicio">Pedir Cita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#citas">Citas Activas</a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="inicio">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="card-title">Pedir Cita</h1>
                                <p class="text-muted">Completa el formulario para pedir una cita.</p>

                                <form action="/appointment/store" method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dni" class="form-label">DNI</label>
                                        <input type="text" class="form-control" id="dni" name="dni" maxlength="9" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="appointment_type" class="form-label">Appointment Type</label>
                                        <select class="form-select" id="appointment_type" name="appointment_type" required>
                                            <option value="" disabled selected>Select an option</option>
                                            <option value="First Consultation">First Consultation</option>
                                            <option value="Follow-up">Follow-up</option>
                                        </select>
                                    </div>
                                    <?php if (isset($success) && $success): ?>
                                        <div class="alert alert-success" role="alert">
                                            Your appointment has been scheduled successfully! Date: <?= $appointment_slot; ?>
                                        </div>
                                    <?php endif; ?>
                                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="citas">
                        <div class="card">
                            <div class="card-body">
                                <h1 class="card-title">Citas Activas</h1>
                                <p class="card-text">Aquí podrás ver todas tus citas agendadas.</p>
                            </div>

                            <?php if($appointments && is_array($appointments)): ?>
                                <ul class="list-group">
                                    <?php foreach ($appointments as $appointment): ?>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h5 class="card-title"><?= $appointment['full_name']; ?></h5>
                                                    <p class="text-muted"><?= $appointment['dni']; ?></p>
                                                    <p class="card-text"><span><b>Type:</b></span> <?= $appointment['appointment_type']; ?></p>
                                                    <p class="card-text"><b>Date:</b> <?= $appointment['appointment_date']; ?></p>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif ?>

                            <?php if (!$appointments): ?>
                                <div class="card-body">
                                    <p class="card-text">No appointments found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#dni').on('keyup', function() {
                var value = $(this).val();
                if (value.length === 9) {
                    $.ajax({
                        url: '/appointment/check-dni',
                        method: 'POST',
                        data: { dni: value },
                        success: function(response) {
                            var data = JSON.parse(response);

                            if (data.isDniAlreadyExists) {
                                $('#appointment_type option[value="Follow-up"]').prop('disabled', false);
                                $('#appointment_type').val('Follow-up');
                                $('#appointment_type option[value="First Consultation"]').prop('disabled', true);
                            }
                        }
                    });
                } else {
                    $('#appointment_type option[value="First Consultation"]').prop('disabled', false);
                    $('#appointment_type option[value="Follow-up"]').prop('disabled', true);
                    $('#appointment_type').val('First Consultation');
                }
            });
        });
    </script>
</html>