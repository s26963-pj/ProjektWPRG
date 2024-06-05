<?php
    class Post{
        private $id;
        private $db;
        private $date;
        private $owner;
        private $title;
        private $text;

        public function __construct($id, $db, $date,$owner,$title,$text) {
            $this->id=$id;
            $this->db=$db;
            $this->date=$date;
            $this->owner=$owner;
            $this->title=$title;
            $this->text=$text;
        }

        public function addPost($title, $text, $owner, $date)
        {
            if (empty($title) || empty($text)) {
                echo '<script language="javascript">';
                echo 'alert("Tytul oraz opis musza byc uzupelnione")';
                echo '</script>';
            }

            if (empty($owner)) {
                echo '<script language="javascript">';
                echo 'alert("Tylko zalogowani użytkownicy mogą dodawać wpisy.")';
                echo '</script>';
            }

            $stmt_check = $this->db->prepare("SELECT * FROM entries WHERE title = ?");
            $stmt_check->bind_param("s", $title);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            if($result_check->num_rows > 0) {
                echo '<script language="javascript">';
                echo 'alert("Wpis o podanym tytule już istnieje. Podaj inny tytul")';
                echo '</script>';
            }

            $stmt = $this->db->prepare("INSERT INTO entries (title, content, author_id, date) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("ssi", $title, $text, $owner);
            if($stmt->execute()) {
                return true;
            } else {
                echo '<script language="javascript">';
                echo 'alert("Wystąpił błąd podczas dodawania wpisu.")';
                echo '</script>';
            }
        }

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }
        public function getDate()
        {
            return $this->date;
        }
        public function setDate($date)
        {
            $this->date = $date;
        }
        public function getOwner()
        {
            return $this->owner;
        }

        public function setOwner($owner)
        {
            $this->owner = $owner;
        }

        public function getTitle()
        {
            return $this->title;
        }
        public function setTitle($title)
        {
            $this->title = $title;
        }
        public function getText()
        {
            return $this->text;
        }

        public function setText($text)
        {
            $this->text = $text;
        }


    }
?>
