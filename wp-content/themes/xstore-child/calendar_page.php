<?php
/* Template Name: calendar page */

get_header();

$args = array(
    'post_type' => 'product',
    'post_status' => 'publish',

    'meta_query' => array(
        array(
            'key' => 'start_date',
            'compare' => 'EXISTS',
        )
    )
);
$query = new WP_Query($args);
$events = [];
foreach ($query->get_posts() as $post) {
    $events[] = [
        'title' => $post->post_title,
        'start' => date("Y-m-d", strtotime(get_post_meta($post->ID, 'start_date', true))),

    ];
}
?>
<style>
    /* Breadcrumb container */
    .breadcrumb {
        background-color: #033a54;
        padding: 60px 60px;
        height: 200px;
    }



    /* Breadcrumb list */
    .breadcrumb ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
    }

    /* Breadcrumb list items */
    .breadcrumb li {
        display: inline;
        margin-right: 10px;
        color: white;
        font-size: 14px;
    }

    /* Breadcrumb links */
    .breadcrumb a {
        text-decoration: none;
    }

    /* Separators (>) */
    .breadcrumb li:nth-child(odd) {
        content: " > ";
    }

    /* Current page (last item) */
    .current-page {
        color: white;
        padding: 10px 20px;
        /* Add space from left, top, and bottom */
        background-color: #333;
        /* Add background color to the current page */
    }


    .agileacademy .tab {
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        margin: 80px auto 64px;
        padding: 0 16px;
        width: 1300px;
        align-items: center;
    }

    .agileacademy .date {
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
    }

    .agileacademy .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 8px 16px;
        transition: 0.3s;
        font-size: 17px;
        text-transform: capitalize;
        border: 1px solid #033a54;
        border-radius: 8px;
    }

    .agileacademy .tab button:hover {
        background-color: #033a54;
        color: #fff;

    }

    .agileacademy .tab button.active {
        background-color: #033a54;
        color: #fff;
    }

    .agileacademy .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

    .agileacademy .topright {
        float: right;
        cursor: pointer;
        font-size: 28px;
    }

    .agileacademy .topright:hover {
        color: red;
    }

    .breadcrumb-home a {
        color: #c62828;
        font-size: 14px;
        font-family: Open Sans;
        font-weight: bold;
        line-height: 14px;
        word-wrap: break-word;
    }

    .breadcrumb-page-title a {
        color: white;
        font-size: 14px;
        font-family: Open Sans;
        font-weight: bold;
        line-height: 14px;
        word-wrap: break-word;
    }


    .breadcrumb .breadcrumb-title {
        color: #FFFFFF;
        font-family: "Open Sans", Sans-serif;
        font-size: 28px;
        font-weight: 700;
        margin: 20px 0;
    }

    /* Style the search container */
    .search-container {
        display: flex;
        align-items: center;
    }

    /* Style the search input */
    input[type="search"] {

        border: 1px solid #ccc;
        padding: 15px;
        margin-right: 20px;
        padding-left: 35px;
        /* Space for the icon */
        outline: none;
        width: 250px;
        border-radius: 8px;
        /* Adjust the width as needed */
    }

    .fc-button {
        margin: 0px 10px;
    }

    /* Style the search icon */
    .search-icon {
        position: absolute;
        margin-left: 8px;
        /* Position the icon to the left of the input */
        color: #888;
        cursor: pointer;
    }

    .agileacademy .card {
        padding-bottom: 40px;
        display: flex;
        justify-content: space-between;
    }

    .agileacademy .card>div:nth-child(2) {
        border: 1px solid #CAD6E0;
        border-radius: 8px;
        width: 80%;
        padding: 24px;
        display: flex;
        align-items: center;
    }

    .agileacademy .card .card-image {
        width: 150px;
        height: 150px;
        margin-right: 24px;
        margin-bottom: 0;
    }

    .agileacademy .card .card-text {
        font-size: 24px;
        color: #0F1F26;
        margin-bottom: 16px;
        font-family: Merriweather_Bold;
        font-weight:700;
    }

    .agileacademy .card .card-description {
        font-size: 16px;
        color: #4F5B66;
        padding-bottom: 24px;
        font-family: Open_Sans;
    }

    .agileacademy .card .card-button {
        color: #033A54;
        background-color: white;
        border: 2px solid #033A54;
        width: 132px;
        text-align: center;
        padding: 10px 24px;
        font-family: Open_Sans;
        border-radius: 4px;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: 14px; 
    }

    #list {
        width: 1300px !important;
        margin: 20px auto;
    }

    .tabcontent {
        border: none !important;
    }

    .fc-theme-standard .fc-scrollgrid {
        border: 0px solid;
    }

    table {
        border-collapse: inherit !important;
        border-spacing: 0;

    }

    .fc-theme-standard th {
        border: 0px solid;
    }

    table th:last-child,
    table td:last-child {
        text-align: left !important;
    }

    table th,
    table td {
        text-align: left !important;
    }

    .fc-daygrid-body-unbalanced {
        width: 1300px !important;
        margin: 0 auto !important;
    }

    .fc .fc-toolbar.fc-header-toolbar,
    .fc-col-header {
        margin: 20px auto !important;
        width: 1300px !important;
    }

    .fc .fc-scrollgrid-section-liquid>td {
        border: none !important;
    }

    .fc .fc-daygrid-day-top {
        flex-direction: column !important;
    }

    .agileacademy .fc-button-group {
        flex: 1;
    }

    @media (max-width: 768px) {
        .breadcrumb {
            margin: 0px 0px;
            text-align: center;
        }

        .tab {
            position: relative;
        }

        /* Breadcrumb container */
        .breadcrumb {
            padding: 60px 30px;
        }

        .breadcrumb .breadcrumb-title {
            margin-left: -80px;
        }

        .card {
            flex-direction: column;
            width: 100% !important;
        }

        .card-content {
            display: flex !important;
            flex-direction: column !important;
        }

        .card-content-text {
            text-align: center !important;

        }

        .card-content-text .card-text {
            /*text-align: left !important;*/
            padding: 10px 0;
        }

        .card-description {
            /*text-align: left !important;*/
            padding: 10px 0;
        }

        .agileacademy .card>div:nth-child(2) {
            width: 100% !important;
        }

        .card span {
            font-size: 16px;
            padding: 10px 0px;
            text-align: center;
        }

        .agileacademy .card .card-text {
            font-size: 16px;
        }

        #list {
            width: revert !important;
        }

        .agileacademy .tab {
            /* width: revert !important; */
            flex-direction: row;
            /* margin: 80px auto 0px 0px; */
            /* padding-bottom: 100px !important; */
            width: auto !important;
            margin: 80px auto 0px;
            padding: 6px 12px;
            flex-wrap: wrap;
        }

        .search-container {
            /* display: flex;
            align-items: center;
            margin-top: 30px;
            position: absolute;
            top: 99px;
            width: 95%;
            padding-bottom: 24px; */

        }

        input[type="search"] {
            width: 100% !important;
        }

        .agileacademy .card .card-image {
            width: 129px;
            height: 130px;
            margin-right: 20px;
            margin-bottom: 0;
        }

        .fc-button-group {
            margin: 0 auto !important;
            margin-bottom: 16px !important;
            order: 3;
        }

        .agileacademy .card .card-button {
            color: #033A54;
            width: 160px;
            padding: 10px 70px;
            font-size: 15px;
            font-weight: bold;
        }

        .tabs-button {
            /* width: 98%; */
            margin: 20px 0px;
        }

        input[type="search"] {
            margin-right: 0;
        }

        .agileacademy form {
            flex-basis: 100%;
        }

        .agileacademy .fc-button-group {
            flex: unset;
            margin: 20px 0px !important;
        }
    }

    .tabs-button .tablinks:nth-child(1) {
        border-radius: 9px 0 0px 8px;
    }

    .tabs-button .tablinks:nth-child(2) {
        border-radius: 0 8px 8px 0;
    }

    /* sweet */
    div:where(.swal2-container) h2:where(.swal2-title) {
        text-align: initial;
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
        line-height: 36px;
        font-family: 'Open Sans';
    }

    div:where(.swal2-container) .swal2-html-container {
        font-family: 'Open Sans';
        text-align: initial;
    }

    div:where(.swal2-container) input:where(.swal2-input) {
        margin: 1em 0 0;
    }

    div:where(.swal2-container) .swal2-html-container p {
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 14px;
        margin-bottom: 24px;
    }

    div:where(.swal2-container) .swal2-html-container p a {
        font-size: 14px;
        font-weight: 700;
        color: #C34849;
    }

    div:where(.swal2-container) .swal2-input,
    .swal2-html-container input[type="password"] {
        font-size: 1rem !important;
        font-weight: 400;
        height: 56px;
        width: 100%;
        border-radius: 4px;
    }

    .swal2-html-container>a {
        display: block;
        margin: 16px 0 40px;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: 14px;
        text-decoration-line: underline;
        color: #C34849;
    }

    .signin-popup input[type="submit"] {
        height: auto;
        width: 100%;
        height: 56px;
        background: #C34849;
        color: #fff;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: 18px;
    }

    .signin-popup .separator {
        text-transform: capitalize;
        position: relative;
        background-color: #ffffff;
        width: 100%;
        text-align: center;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        margin: 16px auto;
    }

    .signin-popup .separator::before,
    .signin-popup .separator::after {
        content: "";
        position: absolute;
        top: 13px;
        width: 45%;
        height: 1px;
        background-color: #CAD6E0;
    }

    .signin-popup .separator::before {

        left: 0;
    }

    .signin-popup .separator::after {
        right: 0;
    }

    #google-login,
    #facebook-login,
    #twitter-login {
        width: 100%;
        height: 56px;
        background-color: transparent;
        border-radius: 8xp;
        border: 1px solid #CAD6E0;
        color: #0F1F26;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: 18px;
        margin-bottom: 16px !important;
    }
</style>
<script src="<?= get_stylesheet_directory_uri() ?>/custom/index.global.min.js"></script>
<div class="breadcrumb">
    <ul>
        <li class="breadcrumb-home"><a href="<?php echo home_url('/'); ?>">Home</a></li>
        <li>&gt;</li>
        <li class="breadcrumb-page-title"><a href="#">Courses Calendar </a></li>

    </ul>
    <div class="breadcrumb-title">
        Courses Calendar
    </div>
</div>


<div class="agileacademy">
    <div class="tab">
        <div class="fc-button-group" id="fc-button-group">
            <button type="button" title="Previous month" aria-pressed="false" class="fc-prev-button fc-button fc-button-primary">
                <span class="fc-icon fc-icon-chevron-left"></span>
            </button>
            <button type="button" title="Next month" aria-pressed="false" class="fc-next-button fc-button fc-button-primary">
                <span class="fc-icon fc-icon-chevron-right"></span>
            </button>
        </div>
        <!-- <form action="#" method="get" id="search-form">
            <div class="search-container">
                <input type="search" name="search" id="search" placeholder="Search...">
                <i class="fa fa-search search-icon"></i>
            </div>
        </form> -->

        <div class="tabs-button">
            <button class="tablinks" onclick="openTab(event, 'list')" id="defaultOpen"> <i class="eicon-editor-list-ul" style="margin-right: 5px"></i>list</button>
            <button class="tablinks" onclick="openTab(event, 'tcalendar')"> <i class="eicon-calendar" style="margin-right: 5px"></i>Month</button>

        </div>
    </div>
    <div id="tcalendar" style="height: fit-content;" class="tabcontent">
        <div id="calendar"></div>

    </div>
    <div id="list" class="tabcontent">

        <?php foreach ($query->get_posts() as $post) : ?>
            <div class="card">
                <span class="date">
                    <?= date("Y-m-d", strtotime(get_post_meta($post->ID, 'start_date', true))) ?>
                </span>
                <div class="card-content">
                    <img class="card-image" src="<?= get_the_post_thumbnail_url($post->ID) ?>" alt="">
                    <div class="card-content-text">
                        <h3 class="card-text">
                            <?= $post->post_title ?>
                        </h3>
                        <p class="card-description">
                            <?= $post->post_content ?>
                        </p>
                        <a href="<?= get_permalink($post->ID) ?>" class="card-button">Learn more</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>
<div class="test-btn">
    <button class="btn" onclick="sweetFire()">sign in</button>
</div>
<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        if (tabName === "list") {
            document.getElementById("fc-button-group").style.display = "block";
        } else {
            document.getElementById("fc-button-group").style.display = "none";
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {

            navLinks: true, // can click day/week names to navigate views
            editable: true,
            events: <?= json_encode($events) ?>
        });
        calendar.render();

    });
</script>
<script>
    $(document).ready(function() {
        // Check if the window width is greater than or equal to 768 pixels (adjust as needed)
        if ($(window).width() >= 768) {
            // Find all elements with class 'fc-col-header-cell-cushion'
            $('.fc-col-header-cell-cushion').each(function() {
                const dayNameMap = {
                    'Sun': 'Sunday',
                    'Mon': 'Monday',
                    'Tue': 'Tuesday',
                    'Wed': 'Wednesday',
                    'Thu': 'Thursday',
                    'Fri': 'Friday',
                    'Sat': 'Saturday'
                };

                const abbreviatedDay = $(this).text();
                const fullDay = dayNameMap[abbreviatedDay];
                if (fullDay) {
                    $(this).text(fullDay);
                }
            });
        }
    });
</script>

<script>
    function sweetFire() {
        new swal({
            title: 'Log in',
            html: `
        <p>Don't have an account? <a href='#'>Create account</a></p>
        <input id="swal-input1" class="swal2-input" placeholder="Email">
        <input id="swal-input2" class="swal2-input" placeholder="Password" type="password">
        <a href="#">Forgot password?</a>
        <input type="submit" value="Log in">
        <div class="separator">or</div>
        <button id="google-login" class="swal2-confirm swal2-styled"><span>icon</span> Continue with Google</button>
        <button id="facebook-login" class="swal2-confirm swal2-styled">Continue with Facebook</button>
        <button id="twitter-login" class="swal2-confirm swal2-styled">Continue with Twitter</button>
    `,
            focusConfirm: false,
            showConfirmButton: false,
            showCancelButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'signin-popup',
            },
        })
    }
</script>

<?php
get_footer();
