<?php
    class User{
        private $id;
        private $db;
        private $firstName;
        private $lastName;
        private $age;
        private $email;
        private $password;

        public function __construct($id, $db, $firstName, $lastName, $age, $email, $password)
        {
            $this->id = $id;
            $this->db = $db;
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->age = $age;
            $this->email = $email;
            $this->password = $password;
        }


        public function login($username, $password) {
            $password = md5($password);
            $conn = $this->db->getConnection();
            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                // Ustawienie sesji lub ciasteczka zalogowanego użytkownika
                $_SESSION['username'] = $username;
                header("Location: index.php");
            } else {
                echo("window.alert(Nieprawidłowy login lub hasło)");
            }
        }

        function getId(){
            return $this->id;
        }
        public function getFirstName()
        {
            return $this->firstName;
        }
        public function setFirstName($firstName)
        {
            $this->firstName = $firstName;
        }
        public function getLastName()
        {
            return $this->lastName;
        }
        public function setLastName($lastName)
        {
            $this->lastName = $lastName;
        }
        public function getAge()
        {
            return $this->age;
        }
        public function setAge($age)
        {
            $this->age = $age;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function setPassword($password)
        {
            $this->password = $password;
        }


    }
?>
