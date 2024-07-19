<?php
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $stmt = $pdo->prepare("INSERT INTO suppliar (name, company, address, con_num, email, total_buy, total_paid, total_due) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['company'], $_POST['address'], $_POST['con_num'], $_POST['email'], 0, 0, 0]); // Initial totals set to 0
    } elseif (isset($_POST['update'])) {
        $stmt = $pdo->prepare("UPDATE suppliar SET name = ?, company = ?, address = ?, con_num = ?, email = ? WHERE id = ?");
        $stmt->execute([$_POST['name'], $_POST['company'], $_POST['address'], $_POST['con_num'], $_POST['email'], $_POST['id']]);
    } elseif (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM suppliar WHERE id = ?");
        $stmt->execute([$_POST['id']]);
    }
}

// Fetch all suppliers
$stmt = $pdo->query("SELECT id, suppliar_id, name, company, address, con_num, email, total_buy, total_paid, total_due, reg_date, update_by, update_at, create_at FROM suppliar");
$suppliars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<br>
<div class="container mt-5">
    <h2>Rider List</h2>
    
    <div class="table-responsive">
        <table id="suppliar_table" class="table table-bordered table-striped">
            <thead>
            <tr>
          
                <th>ID</th>
                <th>Name</th>
                <th>Company</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Total Paid</th>
                <th>Total Due</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($suppliars as $suppliar): ?>
                <tr>
                
                    <td><?= $suppliar['id'] ?></td>
                    <td><?= $suppliar['name'] ?></td>
                    <td><?= $suppliar['company'] ?></td>
                    <td><?= $suppliar['address'] ?></td>
                    <td><?= $suppliar['con_num'] ?></td>
                    <td><?= $suppliar['email'] ?></td>
                    <td><?= $suppliar['total_paid'] ?></td>
                    <td><?= $suppliar['total_due'] ?></td>
                    <td><?= $suppliar['reg_date'] ?></td>
                   <td>
    <button class="btn btn-success btn-sm add-btn" data-toggle="modal" data-target="#addSupplierModal">
        <i class="fas fa-plus"></i> Add
    </button>
    <button class="btn btn-warning btn-sm edit-btn" data-toggle="modal" data-target="#editSupplierModal" 
            data-id="<?= $suppliar['id'] ?>" data-name="<?= $suppliar['name'] ?>" 
            data-company="<?= $suppliar['company'] ?>" data-address="<?= $suppliar['address'] ?>" 
            data-con_num="<?= $suppliar['con_num'] ?>" data-email="<?= $suppliar['email'] ?>">
        <i class="fas fa-edit"></i> Edit
    </button>
    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
        <input type="hidden" name="id" value="<?= $suppliar['id'] ?>">
        <button type="submit" name="delete" class="btn btn-danger "   >
            <i class="fas fa-trash-alt" 
></i> Delete
        </button>
    </form>
</td>


                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" name="company" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="con_num" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="add" class="btn btn-primary">Add Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" name="company" id="edit-company" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" id="edit-address" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="con_num" id="edit-con_num" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-warning">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>


