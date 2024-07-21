<?php

/**
 * The user class
 */
class Objects {
    protected $pdo;

    // Constructor to initialize PDO connection
    function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Function to create a new record
    public function create($table, $fields = array()) {
        $columns = implode(',', array_keys($fields));
        $placeholders = implode(',', array_fill(0, count($fields), '?')); 
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = $this->pdo->prepare($sql);
        if ($stmt) {
            $values = array_values($fields);
            if ($stmt->execute($values)) {
                return $this->pdo->lastInsertId();
            } else {
                die("Error: " . implode(" ", $stmt->errorInfo()));
            }
        } else {
            die("Error: " . implode(" ", $this->pdo->errorInfo()));
        }
    }

    // Function to update a record
    public function update($table, $column_name, $id, $fields = array()) {
        $set_clause = '';
        $i = 1;
        foreach ($fields as $name => $value) {
            $set_clause .= "{$name} = ?";
            if ($i < count($fields)) {
                $set_clause .= ', ';
            }
            $i++;
        }

        $sql = "UPDATE {$table} SET {$set_clause} WHERE {$column_name} = ?";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt) {
            $values = array_values($fields);
            $values[] = intval($id);
            if ($stmt->execute($values)) {
                return $stmt->rowCount();
            } else {
                die("Error: " . implode(" ", $stmt->errorInfo()));
            }
        } else {
            die("Error: " . implode(" ", $this->pdo->errorInfo()));
        }
    }

    // Function to delete records
    public function delete($table, $array) {
        $where_clause = '';
        $i = 1;
        foreach ($array as $key => $value) {
            $where_clause .= "{$key} = ?";
            if ($i < count($array)) {
                $where_clause .= ' AND ';
            }
            $i++;
        }

        $sql = "DELETE FROM {$table} WHERE {$where_clause}";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt) {
            $values = array_values($array);
            if ($stmt->execute($values)) {
                return true;
            } else {
                die("Error: " . implode(" ", $stmt->errorInfo()));
            }
        } else {
            die("Error: " . implode(" ", $this->pdo->errorInfo()));
        }
    }

    // Function to fetch all records from a table
    public function all($table) {
        $sql = "SELECT * FROM {$table}";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                die("Error: " . implode(" ", $stmt->errorInfo()));
            }
        } else {
            die("Error: " . implode(" ", $this->pdo->errorInfo()));
        }
    }

    // Function to find a specific record
    public function find($table, $column, $value) {
        $sql = "SELECT * FROM {$table} WHERE {$column} = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$value])) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                die("Error: " . implode(" ", $stmt->errorInfo()));
            }
        } else {
            die("Error: " . implode(" ", $this->pdo->errorInfo()));
        }
    }

    // Function to find records based on a condition
    public function findWhere($table, $column, $value) {
        $sql = "SELECT * FROM {$table} WHERE {$column} = ? ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([$value])) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                die("Error: " . implode(" ", $stmt->errorInfo()));
            }
        } else {
            die("Error: " . implode(" ", $this->pdo->errorInfo()));
        }
    }

    public function total_count($table) {
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt) {
            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row['count'];
            } else {
                die("Error: " . implode(" ", $stmt->errorInfo()));
            }
        } else {
            die("Error: " . implode(" ", $this->pdo->errorInfo()));
        }
    }

    // Function to shorten content
    public function shortSummary($content, $len) {
        return substr($content, 0, $len) . '....';
    }

    // Function to upload an image
    public function uploadImage($file, $folderPath) {
        $fileName = basename($file['name']);
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $error = $file['error'];

        $ext = explode(".", $fileName);
        $ext = strtolower(end($ext));

        $allowedExt = array('jpg', 'png', 'jpeg');

        if (in_array($ext, $allowedExt) === true) {
            if ($fileSize <= (1024 * 2) * 1024) {
                $fileRoot = 'img_' . time() . '_' . $fileName;
                move_uploaded_file($fileTmp, $_SERVER["DOCUMENT_ROOT"] . '/client/fiver/drive/admin/upload/' . $folderPath . '/' . $fileRoot);
                return $fileRoot;
            } else {
                $GLOBALS['imageError'] = "This file size is too large";
            }
        } else {
            $GLOBALS['imageError'] = "This file type is not allowed";
        }
    }

    // Function to display a message
    public function message() {
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
    }

    // Function to convert date format
    public function convertDate($value = '') {
        return date("d M Y", strtotime($value));
    }

    // Function to convert date to MySQL format
    public function convertDateMysql($value = '') {
        return date("Y-m-d", strtotime($value));
    }

    // Function to store customer order invoice
    public function storeCustomerOrderInvoice($invoice_number, $customer_name, $orderdate, $find_customer_name, $total_quantity, $orderQuantity, $price, $totalPrice, $pro_name, $pid, $subtotal, $discount, $prev_due, $netTotal, $paidBill, $dueBill, $payMethode) {
        $stmt = $this->pdo->prepare("INSERT INTO `invoice`(`invoice_number`,`customer_id`,`customer_name`, `order_date`, `sub_total`, `discount`,`pre_cus_due`, `net_total`, `paid_amount`, `due_amount`, `payment_type`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$invoice_number, $customer_name, $find_customer_name, $orderdate, $subtotal, $discount, $prev_due, $netTotal, $paidBill, $dueBill, $payMethode]);
        $invoice_no = $this->pdo->lastInsertId();

        if ($invoice_no != null) {
            for ($i = 0; $i < count($price); $i++) {
                $remain_quantity = $total_quantity[$i] - $orderQuantity[$i];
                if ($remain_quantity < 0) {
                    return "Sorry ! you haven't the quantity";
                } else {
                    $stmt = $this->pdo->prepare("UPDATE `products` SET `quantity` = ? WHERE `id` = ?");
                    $stmt->execute([$remain_quantity, $pid[$i]]);

                    $stmt = $this->pdo->prepare("INSERT INTO `invoice_details`(`invoice_no`,`pid`, `product_name`, `price`, `quantity`) VALUES (?,?,?,?,?)");
                    $stmt->execute([$invoice_no, $pid[$i], $pro_name[$i], $totalPrice[$i], $orderQuantity[$i]]);
                }
            }
            return true;
        }
    }
}

?>
