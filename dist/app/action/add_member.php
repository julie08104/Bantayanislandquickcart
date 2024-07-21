<?php 
require_once '../init.php';

if (isset($_POST)) {
    $name = $_POST['name'];
    $company = isset($_POST['company']) ? $_POST['company'] : ''; 
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $cus_open_balance = isset($_POST['cus_open_balance']) ? $_POST['cus_open_balance'] : ''; 
    $reg_date = $obj->convertDateMysql($_POST['reg_date']);
    $get_m_name = "C".time();

    if (!empty($name)) {
        $query = array(
            'member_id' => $get_m_name,
            'name' => $name,
            'company' => $company,
            'address' => $address,
            'con_num' => $contact,
            'email' => $email,
            'total_due' => $cus_open_balance,
            'reg_date' => $reg_date,
            'update_by' => 1
        );

        $res = $obj->create('member', $query);
        $last_id = $pdo->lastInsertId();

        if ($res) {
            $add_pay_query = array(
                'cus_id' => $last_id,
                'due_balance' => $cus_open_balance,
            );

            $res_pay = $obj->create('customer_balance', $add_pay_query);

            if ($res_pay) {
                echo "Member added successfully";
            } else {
                echo "Failed to add member's balance. Please try again.";
            }
        } else {
            echo "Failed to add member. Please try again.";
        }
    } else {
        echo "Name field required";
    }
}
?>
