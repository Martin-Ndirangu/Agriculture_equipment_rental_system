<?php
/**
 * Admin M-Pesa Transactions View
 * View and manage all M-Pesa payment transactions
 */
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']==0)) {
  header('location:logout.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>AERMS || M-Pesa Transactions</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .badge-completed { background-color: #28a745; }
        .badge-pending { background-color: #ffc107; }
        .badge-failed { background-color: #dc3545; }
        .table-responsive { margin-top: 20px; }
        .stats-card {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stats-card h3 { margin: 0; color: #666; font-size: 14px; }
        .stats-card .value { font-size: 28px; font-weight: bold; margin-top: 10px; }
        .stats-card.success .value { color: #28a745; }
        .stats-card.warning .value { color: #ffc107; }
        .stats-card.danger .value { color: #dc3545; }
    </style>
</head>
<body>

<?php include_once('includes/header.php');?>
<?php include_once('includes/sidebar.php');?>

<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-md-12">
                    <h2>M-Pesa Transactions</h2>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <?php
            // Get statistics
            $totalQuery = mysqli_query($con, "SELECT 
                COUNT(*) as total_count,
                SUM(CASE WHEN TransactionStatus = 'Completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN TransactionStatus = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN TransactionStatus = 'Failed' THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN TransactionStatus = 'Completed' THEN Amount ELSE 0 END) as total_amount
                FROM tblmpesa_transactions
                WHERE DATE(CreatedAt) = CURDATE()
            ");
            $stats = mysqli_fetch_array($totalQuery);
            
            $allTimeQuery = mysqli_query($con, "SELECT 
                SUM(CASE WHEN TransactionStatus = 'Completed' THEN Amount ELSE 0 END) as total_revenue
                FROM tblmpesa_transactions
            ");
            $allTime = mysqli_fetch_array($allTimeQuery);
            ?>
            
            <div class="col-md-3">
                <div class="stats-card success">
                    <h3>Today's Revenue</h3>
                    <div class="value">KSH <?php echo number_format($stats['total_amount'], 2); ?></div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card success">
                    <h3>Completed Today</h3>
                    <div class="value"><?php echo $stats['completed_count']; ?></div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card warning">
                    <h3>Pending Today</h3>
                    <div class="value"><?php echo $stats['pending_count']; ?></div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card danger">
                    <h3>Failed Today</h3>
                    <div class="value"><?php echo $stats['failed_count']; ?></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="stats-card">
                    <h3>All Time Revenue</h3>
                    <div class="value" style="color: #28a745;">KSH <?php echo number_format($allTime['total_revenue'], 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Recent Transactions</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Filter Options -->
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-md-3">
                                <select class="form-control" id="statusFilter" onchange="filterTransactions()">
                                    <option value="">All Status</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Failed">Failed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" id="dateFilter" onchange="filterTransactions()">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="searchFilter" placeholder="Search booking/receipt number..." onkeyup="filterTransactions()">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" onclick="exportToCSV()">Export to CSV</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="transactionsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking Number</th>
                                        <th>Phone Number</th>
                                        <th>Amount</th>
                                        <th>M-Pesa Receipt</th>
                                        <th>Status</th>
                                        <th>Transaction Date</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($con, "SELECT * FROM tblmpesa_transactions ORDER BY CreatedAt DESC LIMIT 100");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($query)) {
                                        $statusClass = '';
                                        switch($row['TransactionStatus']) {
                                            case 'Completed': $statusClass = 'badge-completed'; break;
                                            case 'Pending': $statusClass = 'badge-pending'; break;
                                            case 'Failed': $statusClass = 'badge-failed'; break;
                                        }
                                    ?>
                                    <tr data-status="<?php echo $row['TransactionStatus']; ?>" data-date="<?php echo date('Y-m-d', strtotime($row['CreatedAt'])); ?>" data-search="<?php echo strtolower($row['BookingNumber'] . ' ' . $row['MpesaReceiptNumber']); ?>">
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo $row['BookingNumber']; ?></td>
                                        <td><?php echo $row['PhoneNumber']; ?></td>
                                        <td>KSH <?php echo number_format($row['Amount'], 2); ?></td>
                                        <td><?php echo $row['MpesaReceiptNumber'] ? $row['MpesaReceiptNumber'] : '-'; ?></td>
                                        <td><span class="badge <?php echo $statusClass; ?>"><?php echo $row['TransactionStatus']; ?></span></td>
                                        <td><?php echo $row['TransactionDate'] ? date('Y-m-d H:i', strtotime($row['TransactionDate'])) : '-'; ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($row['CreatedAt'])); ?></td>
                                        <td>
                                            <a href="view-booking.php?bookingid=<?php echo $row['BookingNumber']; ?>" class="btn btn-sm btn-info">View Booking</a>
                                        </td>
                                    </tr>
                                    <?php $cnt++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php');?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
function filterTransactions() {
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
    const table = document.getElementById('transactionsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const status = row.getAttribute('data-status');
        const date = row.getAttribute('data-date');
        const search = row.getAttribute('data-search');

        let showRow = true;

        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }

        if (dateFilter && date !== dateFilter) {
            showRow = false;
        }

        if (searchFilter && search.indexOf(searchFilter) === -1) {
            showRow = false;
        }

        row.style.display = showRow ? '' : 'none';
    }
}

function exportToCSV() {
    const table = document.getElementById('transactionsTable');
    const rows = table.querySelectorAll('tr:not([style*="display: none"])');
    let csv = [];

    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length - 1; j++) { // Exclude action column
            row.push('"' + cols[j].innerText + '"');
        }
        
        csv.push(row.join(','));
    }

    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'mpesa_transactions_' + new Date().toISOString().slice(0,10) + '.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Auto refresh every 30 seconds for pending transactions
setInterval(function() {
    const pendingCount = <?php echo $stats['pending_count']; ?>;
    if (pendingCount > 0) {
        location.reload();
    }
}, 30000);
</script>

</body>
</html>
<?php } ?>
