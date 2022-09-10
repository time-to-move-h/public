<?php
declare(strict_types=1);
$data = null;
$dashboard_data = null;

try {

    $commonData = new \Moviao\Data\CommonData();
    $commonData->iniDatabase();
    $commonData->setSession($sessionUser);
    $data = $commonData->getDBConn();

    // Execute Transaction
    $data->connectDBA();
    $ticket_utils = new \Moviao\Data\Util\TicketsUtils($commonData);
    $form = new \stdClass();
    $form->iduser = $sessionUser->getIDUSER();
    $dashboard_data = $ticket_utils->getTicketsDashboard($form);
    //echo var_export($dashboard_data, true);

    // Events
    $event_utils = new \Moviao\Data\Util\EventsUtils($commonData);
    $events_data = $event_utils->getEventsDashboard($form);

} catch (Error $e) {
    error_log('dashboard.tpl.php >> ' . $e);
} finally {
    if (isset($data) && null !== $data) {
        $data->disconnect();
    }
}
?>
<!--<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">-->
<!--    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Company name</a>-->
<!--    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">-->
<!--    <ul class="navbar-nav px-3">-->
<!--        <li class="nav-item text-nowrap">-->
<!--            <a class="nav-link" href="#">Sign out</a>-->
<!--        </li>-->
<!--    </ul>-->
<!--</nav>-->

<div class="container-fluid">
    <div class="row">
<!--        sidebar-->
        <nav class="col-md-2 d-none d-md-block bg-light ">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <span data-feather="home"></span>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                    </li>

<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#orders">-->
<!--                            <span data-feather="shopping-cart"></span>-->
<!--                            Orders-->
<!--                        </a>-->
<!--                    </li>-->
<!---->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#checkin">-->
<!--                            <span data-feather="check-circle"></span>-->
<!--                            Check In-->
<!--                        </a>-->
<!--                    </li>-->

<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="users"></span>-->
<!--                            Customers-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="bar-chart-2"></span>-->
<!--                            Reports-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="layers"></span>-->
<!--                            Integrations-->
<!--                        </a>-->
<!--                    </li>-->

                </ul>

<!--                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">-->
<!--                    <span>Saved reports</span>-->
<!--                    <a class="d-flex align-items-center text-muted" href="#">-->
<!--                        <span data-feather="plus-circle"></span>-->
<!--                    </a>-->
<!--                </h6>-->
<!--                <ul class="nav flex-column mb-2">-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="file-text"></span>-->
<!--                            Current month-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="file-text"></span>-->
<!--                            Last quarter-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="file-text"></span>-->
<!--                            Social engagement-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li class="nav-item">-->
<!--                        <a class="nav-link" href="#">-->
<!--                            <span data-feather="file-text"></span>-->
<!--                            Year-end sale-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->

            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">

<!--            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">-->
<!--                <h1 class="h2">Dashboard</h1>-->
<!--                <div class="btn-toolbar mb-2 mb-md-0">-->
<!--                    <div class="btn-group mr-2">-->
<!--                        <button class="btn btn-sm btn-outline-secondary">Share</button>-->
<!--                        <button class="btn btn-sm btn-outline-secondary">Export</button>-->
<!--                    </div>-->
<!--                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle">-->
<!--                        <span data-feather="calendar"></span>-->
<!--                        This week-->
<!--                    </button>-->
<!--                </div>-->
<!--            </div>-->

<!--            <canvas class="my-4" id="myChart" width="900" height="380"></canvas>-->

            <h2><?=$this->e($info->_e('context_title'));?></h2>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
<!--                        <th>#</th>-->
                        <th>Title</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($events_data as $obj) {

                        $allday = false;
                        if ((isset($obj['ALLDAY'])) && $obj['ALLDAY'] === '1') {
                            $allday = true;
                        }
                        $date_start = new DateTime($obj["DATBEG"],new \DateTimeZone('UTC'));
                        $date_start->setTimezone(new \DateTimeZone($obj["TIMEZONE_BEG"]));
                        $date_end = null;
                        if (isset($obj["DATEND"]) && $obj["DATEND"] != null) {
                            $date_end = new DateTime($obj["DATEND"], new \DateTimeZone('UTC'));
                            $date_end->setTimezone(new \DateTimeZone($obj["TIMEZONE_END"]));
                        }
                        $dateformat = new \Moviao\Util\DateTimeFormat();
                        $datevent_formatted = $dateformat->formatDate($date_start,$date_end,$lang,$allday,false);

                    ?>
                        <tr>
<!--                            <td>--><?php //echo  strip_tags($obj["ID"]); ?><!--</td>-->
                            <td>

                                <p><?php echo  strip_tags($obj["TITLE"]); ?></p>




                                <!-- <p>
                                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseTicket<?php //echo $obj["ID"]; ?>" aria-expanded="false" aria-controls="collapseExample">
                                        Tickets
                                    </button>
                                </p>
                                <div class="collapse" id="collapseTicket<?php //echo $obj["ID"]; ?>">
                                    <div class="card card-body">
                                        <?php
                                            // $datenow = new DateTime("now", new \DateTimeZone($obj["TIMEZONE_BEG"]));
                                            // $diff = $date_start->diff($datenow);
                                            // //var_dump($date_start);
                                            // if ((isset($obj["ONLINE"]) && $obj["ONLINE"] === 1) && ($diff->format('%a') >= 0)) {
                                            //     $data = $obj["TOKEN"];
                                            //     echo '<img class="qrcode" width="100px" heigh="100px" src="/util/qrcode?s=qrl&d=' . $data . '" data-token="' . $data . '">';
                                            // } else {
                                            //     echo 'N/A';
                                            // }
                                        ?>
                                    </div>
                                </div> -->





                            </td>
                            <td><?php echo $datevent_formatted; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <br />






<!--            <h2>Tickets</h2>-->
<!--            <div class="table-responsive">-->
<!--                <table class="table table-striped table-sm">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                       <th>#</th>-->
<!--                        <th>Title</th>-->
<!--                        <th>Ticket</th>-->
<!--                        <th>Qte</th>-->
<!--                        <th>Sold</th>-->
<!--                        <th>Price</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    --><?php //foreach ($dashboard_data as $obj) {  ?>
<!--                    <tr>-->
<!--                      <td><?php ////echo  strip_tags($obj["ID"]); ?></td>-->
<!--                        <td>--><?php //echo  strip_tags($obj["TITLE"]); ?><!--</td>-->
<!--                        <td>--><?php //echo  strip_tags($obj["NAME"]); ?><!--</td>-->
<!--                        <td>--><?php //echo  strip_tags($obj["QTE"]); ?><!--</td>-->
<!--                        <td>--><?php //echo  $obj["QTE"] - $obj["QTE_AVAILABLE"]; ?><!--</td>-->
<!--                        <td>--><?php //echo  strip_tags($obj["PRICE"]); ?><!--&nbsp;&euro;</td>-->
<!--                    </tr>-->
<!--                    --><?php //} ?>
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->





        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>-->
<!--<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>-->
<!--<script src="../../assets/js/vendor/popper.min.js"></script>-->
<!--<script src="../../dist/js/bootstrap.min.js"></script>-->

<!-- Icons -->
<!--<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>-->
<!--<script>-->
<!--    feather.replace()-->
<!--</script>-->

<!-- Graphs -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>-->
<!--<script>-->
<!--    var ctx = document.getElementById("myChart");-->
<!--    var myChart = new Chart(ctx, {-->
<!--        type: 'line',-->
<!--        data: {-->
<!--            labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],-->
<!--            datasets: [{-->
<!--                data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],-->
<!--                lineTension: 0,-->
<!--                backgroundColor: 'transparent',-->
<!--                borderColor: '#007bff',-->
<!--                borderWidth: 4,-->
<!--                pointBackgroundColor: '#007bff'-->
<!--            }]-->
<!--        },-->
<!--        options: {-->
<!--            scales: {-->
<!--                yAxes: [{-->
<!--                    ticks: {-->
<!--                        beginAtZero: false-->
<!--                    }-->
<!--                }]-->
<!--            },-->
<!--            legend: {-->
<!--                display: false,-->
<!--            }-->
<!--        }-->
<!--    });-->
<!--</script>-->