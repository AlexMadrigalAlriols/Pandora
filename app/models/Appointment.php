<?php

namespace App\Models;

use App\Database\Database;
use DateInterval;
use DateTime;

class Appointment
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    /**
     * Store a new appointment in the database.
     *
     * @param string $name The name of the appointment holder.
     * @param string $dni The DNI (identification number) of the appointment holder.
     * @param string $phone The phone number of the appointment holder.
     * @param string $email The email address of the appointment holder.
     * @param string $appointment_type The type of the appointment.
     * @return array An array containing a boolean value indicating if the appointment was successfully stored and the available slot if it was stored, otherwise null.
     */
    public function store($name, $dni, $phone, $email, $appointment_type): array
    {
        $available_slot = $this->findAvailableSlot();
        
        if ($available_slot) {
            if(!$this->checkDni($dni)) {
                $appointment_type = 'First Consultation';
            } else {
                $appointment_type = 'Follow-up';
            }

            $sql = 'INSERT INTO appointments (full_name, dni, phone, email, appointment_type, appointment_date, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$name, $dni, $phone, $email, $appointment_type, $available_slot]);

            return [true, $available_slot];
        }

        return [false, null];
    }

    /**
     * Retrieves all appointments from the database.
     *
     * @return array Returns an array containing all the appointments.
     */
    public function all()
    {
        $sql = 'SELECT * FROM appointments';
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Checks if a given DNI exists in the appointments table.
     *
     * @param string $dni The DNI to check.
     * @return bool Returns true if the DNI exists, false otherwise.
     */
    public function checkDni($dni): bool
    {
        $sql = 'SELECT COUNT(*) FROM appointments WHERE dni = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$dni]);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    /**
     * Finds the next available appointment slot.
     *
     * This method searches for the next available appointment slot within the specified time range.
     * It starts from the current date and time, and iterates through each hour until the end of the day.
     * If no appointments are found within a particular hour, the start time of that hour is returned as the available slot.
     * If no available slot is found within the current day, the search continues to the next day starting from 10:00 AM.
     *
     * @return string The start time of the next available appointment slot in the format 'Y-m-d H:i:s'.
     */
    public function findAvailableSlot(): string
    {
        $current_date = new DateTime();
        $current_date->setTime(10, 0);
    
        while (true) {
            $end_of_day = clone $current_date;
            $end_of_day->setTime(22, 0);
    
            while ($current_date <= $end_of_day) {
                $next_hour = clone $current_date;
                $next_hour->modify('+1 hour');
    
                $start = $current_date->format('Y-m-d H:i:s');
                $end = $next_hour->format('Y-m-d H:i:s');
                $sql = 'SELECT COUNT(*) FROM appointments WHERE appointment_date >= ? AND appointment_date < ?';
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$start, $end]);
                $count = $stmt->fetchColumn();
    
                if ($count == 0) {
                    return $start;
                }

                $current_date->modify('+1 hour');
            }

            $current_date->modify('+1 day');
            $current_date->setTime(10, 0);
        }
    }
}