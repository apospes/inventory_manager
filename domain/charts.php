<?php
$title = "Στατιστικά";
require('../templates/header.inc.php');
check_session();
check_admin_users();
try{
    //QUERY 1 START
    $monada_name = [];
    $sum_products = [];
    $stmt = mysqli_prepare($dbcon, 'SELECT count(*), monada_name FROM not_deleted_product_info GROUP BY monada_name ORDER BY count(*) DESC LIMIT 5;');
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $sum, $mon);
    $i = 0;
    while (mysqli_stmt_fetch($stmt)){
        $monada_name[$i] = $mon;
        $sum_products[$i] = $sum;
        $i++;
    };
    mysqli_stmt_close($stmt);
    //QUERY 1 END

    //QUERY 2 START
    $kathgoria_name = [];
    $sum_prod_kathgoria = [];
    $stmt = mysqli_prepare($dbcon, 'SELECT count(*), katigoria_prod_name FROM not_deleted_product_info GROUP BY katigoria_prod_name ORDER BY count(*) DESC  LIMIT 5;');
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $sum, $mon);
    $i = 0;
    while (mysqli_stmt_fetch($stmt)){
        $kathgoria_name[$i] = $mon;
        $sum_prod_kathgoria[$i] = $sum;
        $i++;
    };
    mysqli_stmt_close($stmt);
    //QUERY 2 END

    //QUERY 3 START
    $kathgoria_name_del = [];
    $sum_del = [];
    $stmt = mysqli_prepare($dbcon, 'SELECT count(*), katigoria_prod_name FROM deleted_product_info GROUP BY katigoria_prod_name ORDER BY count(*) DESC;');
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $sum, $mon);
    $i = 0;
    while (mysqli_stmt_fetch($stmt)){
        $kathgoria_name_del[$i] = $mon;
        $sum_del[$i] = $sum;
        $i++;
    };
    mysqli_stmt_close($stmt);
    //QUERY 3 END
}catch (mysqli_sql_exception $e) {
    var_dump($e);
    $errorpage_url = "errorpage.php?e=" . urlencode($e);
    header("Location: $errorpage_url");
    exit();
}// try-catch   


?>

 <!-- Panel Start -->
 <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <!--QUERY 1-->
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Μονάδες με τα περισσότερα υλικά</h6>
                            <canvas id="doughnut-chart"></canvas>
                        </div>
                    </div>

                    <!--QUERY 3-->
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Διεγραμμένα Προϊόντα ανά κατηγορία</h6>
                            <canvas id="pie-chart"></canvas>
                        </div>
                    </div>

                    <!--QUERY 2-->
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4 ">
                            <h6 class="mb-4">Περισσότερα υλικά ανά κατηγορία</h6>
                            <canvas id="bar-chart"></canvas>
                        </div>
                    </div>



                </div>
            </div>
<!-- Panel  End -->

    <!-- Doughnut Start -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var ctx = document.getElementById('doughnut-chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($monada_name); ?>,
                datasets: [{
                    label: 'Ποσότητα',
                    data: <?php echo json_encode($sum_products); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    // title: {
                    //     display: true,
                    //     text: 'Products by Monada Name'
                    // }
                }
            }
        });
    });
    </script>
    <!-- Doughnut End -->

    <!-- Bar Chart Script START -->
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var ctx = document.getElementById('bar-chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($kathgoria_name); ?>,
                datasets: [{
                    label: 'Ποσότητα',
                    data: <?php echo json_encode($sum_prod_kathgoria); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    // title: {
                    //     display: true,
                    //     text: 'Products by Category'
                    // }
                },
                scales: {
                    x: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Κατηγορία Προϊόντων'
                        }
                    },
                    y: {
                        stacked: true,
                        title: {
                            display: true,
                            text: 'Ποσότητα Προϊόντων'
                        }
                    }
                }
            }
        });
    });
    </script>
    <!-- Bar Chart Script END -->

    <!-- Pie Chart Script START -->
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        var ctx = document.getElementById('pie-chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($kathgoria_name_del); ?>,
                datasets: [{
                    label: 'Ποσότητα',
                    data: <?php echo json_encode($sum_del); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    // title: {
                    //     display: true,
                    //     text: 'Ποσότητα Προϊόντων ανά Κατηγορία (Διαγραμμένα Προϊόντα)'
                    // }
                }
            }
        });
    });
    </script>
    <!-- Pie Chart Script END -->



<?php
require('../templates/footer.inc.php');
?>