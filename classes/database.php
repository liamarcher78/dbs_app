<?php
class database{
 
 
    function opencon(): PDO{
        return new PDO(
            'mysql:host=localhost;
            dbname=loria',
            username: 'root',
            password: '');
    }

    function insertUser($email, $password_hash, $is_active) {
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Users (username,user_password_hash,is_active) VALUES (?,?,?)');
            $stmt->execute([$email, $password_hash, $is_active]);
            $user_id = $con->lastInsertId();
            $con->commit();
            return $user_id;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
}

}

    function insertBorrower($firstname, $lastname,$email,$phone,$member_since,$is_active) {
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Borrowers (borrower_firstname,borrower_lastname,borrower_email,borrower_phone_number,borrower_member_since,is_active) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$firstname, $lastname, $email, $phone, $member_since, $is_active]);
            $borrower_id = $con->lastInsertId();
            $con->commit();
            return $borrower_id;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
}
}

    function insertBorrowerUser($user_id, $borrower_id) {
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO BorrowerUsers (user_id,borrower_id) VALUES (?,?)');
            $stmt->execute([$user_id, $borrower_id]);
            $con->commit();
            return true;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
}
}

function insertBorrowerAddress($borrower_id, $house_number, $street, $barangay, $city, $province, $postal, $is_primary) {
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO BorrowerAddress (borrower_id, ba_house_number, ba_street, ba_barangay, ba_city, ba_province, ba_postal_code, is_primary) VALUES (?,?,?,?,?,?,?,?)');
            $stmt->execute([$borrower_id, $house_number, $street, $barangay, $city, $province, $postal, $is_primary]);
            $ba_id = $con->lastInsertId();
            $con->commit();
            return $ba_id;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
}
}
function insertBook($title, $isbn, $year, $edition, $publisher){
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Books (book_title, book_isbn, book_publication_year, book_edition, book_publisher) VALUES (?,?,?,?,?)');
            $stmt->execute([$title, $isbn, $year, $edition, $publisher]);
            $book_id = $con->lastInsertId();
            $con->commit();
            return $book_id;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;

}
}

function insertBookCopy($book_id, $status){
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO BookCopy (book_id, bc_status) VALUES (?,?)');
            $stmt->execute([$book_id, $status]);
            $copy_id = $con->lastInsertId();
            $con->commit();
            return $copy_id;
        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;

}
}

function viewBorrowerUser(){
    $con = $this->opencon();
    return $con->query("SELECT * from Borrowers")->fetchAll();
}

function viewCopies(){
    $con = $this->opencon();
    return $con->query("SELECT books.book_id,
books.book_title,
books.book_isbn,
books.book_publication_year,
books.book_publisher,
COUNT(bookcopy.copy_id) AS Copies,
SUM(bookcopy.bc_status - 'Available') as Available_Copies
FROM books
JOIN bookcopy ON bookcopy.book_id = books.book_id
GROUP BY 1")->fetchAll();
}

function viewBook(){
    $con = $this->opencon();
    return $con->query("SELECT * from Books")->fetchAll();

}
}