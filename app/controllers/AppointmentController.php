<?php

namespace App\Controllers;

use App\Database\Database;
use App\Models\Appointment;


class AppointmentController
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function index($success = false, $appointment_slot = null)
    {
        $appointments = new Appointment($this->conn);
        $appointments = $appointments->all();

        include __DIR__ . '/../../public/views/appointment/index.php';
    }

    public function create()
    {
        include '../views/appointment/create.php';
    }

    public function store()
    {
        $data = $this->parseData($_POST);

        $appointment = new Appointment($this->conn);
        [$success, $appointment_slot] = $appointment->store($data['name'], $data['dni'], $data['phone'], $data['email'], $data['appointment_type']);

        $this->index($success, $appointment_slot);
    }

    public function checkDni()
    {
        $dni = $_POST['dni'];

        $appointment = new Appointment($this->conn);
        $isDniAlreadyExists = $appointment->checkDni($dni);

        echo json_encode(['isDniAlreadyExists' => $isDniAlreadyExists]);
    }

    /**
     * Parses the given data by applying htmlspecialchars to each value.
     *
     * @param array $data The data to be parsed.
     * @return array The parsed data.
     */
    private function parseData($data): array
    {
        $parsedData = [];
        foreach ($data as $key => $value) {
            $parsedData[$key] = htmlspecialchars($value);
        }
        return $parsedData;
    }
}
