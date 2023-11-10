<?php
        session_start();

class UserAuthentication {
    private $conn;
    private $email;
    private $userName; // Define userName as a class property

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;

        if (isset($_SESSION['auth'])) {
            $this->email = $_SESSION['auth'];
            $this->fetchUserData();
        }
    }

    private function fetchUserData() {
        $query = "SELECT name, email, role FROM users WHERE email = '$this->email'";
        $query_run = mysqli_query($this->conn, $query);

        if ($query_run && mysqli_num_rows($query_run) > 0) {
            $row = mysqli_fetch_array($query_run);

            $this->userName = $row['name']; // Assign to the class property
            $_SESSION['auth'] = $row['username'];
            if ($row['role'] == 1) {
                $_SESSION['admin'] = "Go To Admin Dashboard";
            } elseif ($row['role'] == 0) {
                unset($_SESSION['admin']);
                $_SESSION['user'] = "Go To Categories";
            }
        } else {
            $this->userName = "Unknown"; // Assign to the class property
        }
    }

    public function getUserName() {
        return $this->userName;
    }
}
