<?php
/* Template Name: test page */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="owlcarousel/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="owlcarousel/dist/assets/owl.theme.default.min.css">

    <title>Agile Academy</title>

</head>

<style>

/*# sourceMappingURL=style.css.map */


    body {
        box-sizing: border-box;
      }
      
      @font-face {
        font-family: Merriweather;
        src: url(assets/fonts/Merriweather/Merriweather-Regular.ttf);
      }
      @font-face {
        font-family: Merriweather_Bold;
        src: url(assets/fonts/Merriweather/Merriweather-Bold.ttf);
      }
      @font-face {
        font-family: Merriweather_Black;
        src: url(assets/fonts/Merriweather/Merriweather-Black.ttf);
      }
      @font-face {
        font-family: Open_Sans;
        src: url(assets/fonts/Open_Sans/static/OpenSans-Regular.ttf);
      }
      @font-face {
        font-family: Open_Sans_Bold;
        src: url(assets/fonts/Open_Sans/static/OpenSans-Bold.ttf);
      }
      body {
        font-family: Merriweather;
        /* background: var(--neutral-white, #FFF); */
        overflow-x: hidden;
      }
      
      .nav-container {
        position: fixed;
        z-index: 50;
        width: 100%;
        background: #033A54;
      }
      .nav-container .nav-child {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        padding: 20px 16px;
      }
      @media (min-width: 1024px) {
        .nav-container .nav-child {
          padding: 20px 40px;
        }
      }
      @media (min-width: 1280px) {
        .nav-container .nav-child {
          padding: 20px 80px;
        }
      }
      .nav-container .nav-child .nav-logo {
        display: flex;
      }
      .nav-container .nav-child .nav-logo div {
        height: 2rem;
      }
      @media (min-width: 640px) {
        .nav-container .nav-child .nav-logo div {
          width: 176px;
        }
      }
      @media (min-width: 768px) {
        .nav-container .nav-child .nav-logo div {
          margin-right: 0.5rem;
        }
      }
      @media (min-width: 1024px) {
        .nav-container .nav-child .nav-logo div {
          width: 236px;
        }
      }
      .nav-container .nav-child .nav-logo div .nav-img {
        height: 2rem;
      }
      @media (min-width: 640px) {
        .nav-container .nav-child .nav-logo div .nav-img {
          width: 176px;
        }
      }
      @media (min-width: 1024px) {
        .nav-container .nav-child .nav-logo div .nav-img {
          width: 236px;
        }
      }
      .nav-container .calender-section {
        padding-left: 16px;
        padding-right: 16px;
        padding-top: 48px;
        padding-bottom: 48px;
      }
      @media (min-width: 1280px) {
        .nav-container .calender-section {
          padding: 80px;
        }
      }
      @media (min-width: 1024px) {
        .nav-container .calender-section {
          padding-left: 80px;
          padding-right: 80px;
          padding-top: 40px;
          padding-bottom: 40px;
        }
      }
      .nav-container .calender-section .calender-list-header {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }
      @media (min-width: 1024px) {
        .nav-container .calender-section .calender-list-header {
          flex-direction: row;
        }
      }
      
      .gpt-style {
        padding-bottom: 48px;
        padding-left: 16px;
        padding-right: 16px;
        padding-top: 48px;
      }
      @media (min-width: 1024px) {
        .gpt-style {
          padding-left: 80px;
          padding-right: 80px;
          padding-bottom: 40px;
          padding-top: 40px;
        }
      }
      @media (min-width: 1280px) {
        .gpt-style {
          padding: 80px;
        }
      }
      .gpt-style .flex-container {
        display: flex;
      }
      .gpt-style .row {
        flex-direction: row;
      }
      .gpt-style .row-large {
        flex-direction: column;
      }
      @media (min-width: 1024px) {
        .gpt-style .row-large {
          flex-direction: row;
        }
      }
      .gpt-style .row-md {
        flex-direction: column;
      }
      @media (min-width: 768px) {
        .gpt-style .row-md {
          flex-direction: row;
        }
      }
      .gpt-style .justify-between {
        justify-content: space-between;
      }
      .gpt-style .button-image-container {
        margin-right: 5px;
      }
      @media (min-width: 768px) {
        .gpt-style .button-image-container {
          margin-right: 40px;
        }
      }
      .gpt-style .btn {
        display: flex;
        align-items: center;
        color: #0F1F26;
        font-size: 18px;
        margin-bottom: 24px;
        width: 100%;
        font-family: Merriweather_Bold;
      }
      @media (min-width: 768px) {
        .gpt-style .btn {
          margin-bottom: 0px;
        }
      }
      @media (min-width: 1280px) {
        .gpt-style .btn {
          font-size: 24px;
        }
      }
      .gpt-style .button-image {
        background-image: url("assets/images/left-arrow.svg");
        background-size: cover;
        width: 56px;
        height: 40px;
        margin-right: 16px;
      }
      .gpt-style .button-image:hover {
        background-image: url("assets/images/hover-left-arrow.svg");
        background-size: cover;
        width: 56px;
        height: 40px;
        margin-right: 16px;
      }
      .gpt-style .button-image-rotate {
        background-image: url("assets/images/right-arrow.svg");
        background-size: cover;
        width: 56px;
        height: 40px;
      }
      .gpt-style .button-image-rotate:hover {
        background-image: url("assets/images/hover-left-arrow.svg");
        background-size: cover;
        width: 56px;
        height: 40px;
        transform: rotate(180deg);
      }
      .gpt-style .dropdown-menu {
        z-index: 10;
        display: none;
        background-color: white;
        border-radius: 4px;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
      }
      .gpt-style .dropdown-list {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        width: 200px;
        font-size: 14px;
        color: #4F5B66;
        overflow: auto;
        height: 150px;
      }
      .gpt-style .search-container {
        position: relative;
        display: flex;
        align-items: center;
        margin-top: 16px;
        margin-bottom: 16px;
        margin-right: 16px;
      }
      @media (min-width: 768px) {
        .gpt-style .search-container {
          margin-left: 16px;
        }
      }
      @media (min-width: 1024px) {
        .gpt-style .search-container {
          margin-top: 0px;
          margin-bottom: 0px;
          display: block;
        }
      }
      .gpt-style .search-icon {
        position: absolute;
        left: 0;
        display: flex;
        align-items: center;
      }
      .gpt-style .search-input {
        width: 100%;
        padding: 8px;
        padding-left: 40px;
        border: 1px solid #CAD6E0;
        border-radius: 4px;
        color: #8195A6;
        background-color: white;
        font-size: 14px;
        font-family: Open_Sans;
        font-weight: 400;
      }
      @media (min-width: 1280px) {
        .gpt-style .search-input {
          width: 282px;
        }
      }
      @media (min-width: 1024px) {
        .gpt-style .search-input {
          width: 203px;
        }
      }
      .gpt-style .grid {
        display: grid;
        margin-top: 24px;
        margin-bottom: 24px;
        grid-template-columns: repeat(7, 1fr);
      }
      @media (min-width: 768px) {
        .gpt-style .grid {
          margin-top: 40px;
          margin-bottom: 40px;
        }
      }
      .gpt-style .grid-label {
        text-align: center;
        color: #4F5B66;
        font-size: 16px;
        font-family: Open_Sans;
      }
      .gpt-style .calendar {
        /* Your calendar styles go here */
      }
      .gpt-style .hr {
        border: 1px solid #CAD6E0;
        margin: 28px 0;
      }
      .gpt-style .text {
        color: #0F1F26;
        font-size: 16px;
        font-family: Merriweather_Bold;
        padding: 0 24px;
      }
      .gpt-style .card {
        border: 1px solid #CAD6E0;
        padding: 24px;
        display: flex;
      }
      .gpt-style .card-image {
        width: 150px;
        height: 150px;
        margin-right: 24px;
        margin-bottom: 0;
      }
      .gpt-style .card-text {
        font-size: 24px;
        color: #0F1F26;
        margin-bottom: 16px;
        font-family: Merriweather_Bold;
      }
      .gpt-style .card-description {
        font-size: 16px;
        color: #4F5B66;
        padding-bottom: 24px;
        font-family: Open_Sans;
      }
      .gpt-style .card-button {
        color: #033A54;
        background-color: white;
        border: 1px solid #033A54;
        width: 132px;
        text-align: center;
        padding: 10px 24px;
        font-family: Open_Sans_Bold;
      }
      .gpt-style .flex-container-calender {
        display: flex;
        border: 2px solid #033A54;
        width: 100%;
        height: 40px;
        border-radius: 4px;
        margin: 0;
      }
      @media (min-width: 1024px) {
        .gpt-style .flex-container-calender {
          width: 200px;
          margin-top: 0px;
          margin-bottom: 0px;
        }
      }
      .gpt-style .flex-container-calender .button {
        width: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
      }
      .gpt-style .flex-container-calender .button img.icon {
        width: 24px;
        height: 24px;
      }
      .gpt-style .flex-container-calender .button-label {
        color: #033A54;
        font-size: 14px;
        padding-left: 8px;
        text-align: center;
        font-family: "Open Sans Bold";
      }
      .gpt-style .flex-container-calender .button-label-white {
        background-color: #033A54;
        color: white;
        font-size: 14px;
        padding-left: 8px;
        text-align: center;
        font-family: "Open Sans Bold";
      }
      .gpt-style .xl-flex-row {
        flex-direction: column;
      }
      .gpt-style .search-container {
        position: relative;
        display: flex;
        align-items: center;
        margin-top: 16px;
        margin-bottom: 16px;
        margin-right: 16px;
        /* .search-container {
            position: relative;
            margin-right: 16px;
            margin-top: 16px;
        } */
      }
      @media (min-width: 768px) {
        .gpt-style .search-container {
          margin-left: 0px;
        }
      }
      @media (min-width: 1024px) {
        .gpt-style .search-container {
          margin-top: 0px;
          margin-bottom: 0px;
          display: block;
        }
      }
      .gpt-style .search-container .search-icon {
        position: absolute;
        left: 0;
        display: flex;
        align-items: center;
        padding-left: 10px;
        pointer-events: none;
      }
      .gpt-style .search-container .search-icon-svg {
        width: 20px;
        height: 20px;
        color: #8195A6;
      }
      .gpt-style .search-container .search-icon-path {
        stroke: currentColor;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 2;
      }
      .gpt-style .search-container .search-input {
        width: 100%;
        padding: 2px;
        padding-left: 10px;
        border: 1px solid #CAD6E0;
        border-radius: 4px;
        color: #8195A6;
        background-color: #fff;
        font-size: 14px;
        outline: none;
      }
      .gpt-style .search-container .search-input:focus {
        border-color: #033A54;
      }
      .gpt-style .search-container .dark .search-input {
        border-color: #033A54;
        color: #fff;
      }
      .gpt-style .search-container .search-icon {
        position: absolute;
        left: 0;
        display: flex;
        align-items: center;
        padding-left: 12px;
        pointer-events: none;
        top: 0;
        bottom: 0;
      }
      .gpt-style .search-container .search-icon-svg {
        width: 16px;
        height: 16px;
        color: #8195A6;
      }
      @media (prefers-color-scheme: dark) {
        .gpt-style .search-container .search-icon-svg {
          --tw-text-opacity: 1;
          color: white;
        }
      }
      .gpt-style .search-container .search-icon-path {
        stroke: currentColor;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-width: 2;
      }
      .gpt-style .search-container .search-input {
        width: 100%;
        padding: 8px;
        padding-left: 40px;
        border: 1px solid #CAD6E0;
        border-radius: 4px;
        color: #8195A6;
        background-color: #fff;
        font-size: 14px;
        outline: none;
      }
      @media (min-width: 1024px) {
        .gpt-style .search-container .search-input {
          width: 203px;
        }
      }
      @media (min-width: 1280px) {
        .gpt-style .search-container .search-input {
          width: 282px;
        }
      }
      @media (prefers-color-scheme: dark) {
        .gpt-style .search-container .search-input {
          border-color: #033A54;
          color: #fff;
        }
      }
      @media (prefers-color-scheme: dark) {
        .gpt-style .search-container .search-input {
          border-color: #033A54;
          color: #fff;
        }
      }
      .gpt-style .search-container .search-input:focus {
        border-color: #033A54;
      }
      .gpt-style .custom-content {
        display: flex;
        width: 100%;
        margin-top: 40px;
      }
      .gpt-style .custom-hr {
        border: 1px solid #CAD6E0;
        margin-top: 28px;
        width: 42%; /* You can adjust the width as needed */
      }
      .gpt-style .custom-text {
        font-family: Merriweather_Bold;
        font-size: 16px;
        color: #0F1F26;
        margin-top: 12px;
        padding-left: 16px;
      }
      @media (min-width: 1024px) {
        .gpt-style .custom-text {
          font-size: 24px; /* For md screens */
          padding-left: 24px;
        }
      }
      .gpt-style .custom-grid {
        display: grid;
        gap: 40px;
      }
      .gpt-style .custom-grid .custom-course {
        display: flex;
        flex-direction: column;
      }
      .gpt-style .custom-grid .custom-date {
        font-family: Open_Sans;
        font-size: 16px;
        color: #0F1F26;
        margin-bottom: 40px;
      }
      @media (min-width: 1280px) {
        .gpt-style .custom-grid .custom-date {
          margin-right: 40px;
        }
      }
      .gpt-style .custom-grid .custom-card {
        border: 1px solid #CAD6E0;
        padding: 24px;
        display: flex;
        flex-direction: column;
      }
      @media (min-width: 1024px) {
        .gpt-style .custom-grid .custom-card {
          flex-direction: row;
        }
      }
      .gpt-style .custom-grid .custom-image {
        width: 150px;
        height: 150px;
        margin-right: 24px;
        margin-bottom: 24px;
      }
      @media (min-width: 1280px) {
        .gpt-style .custom-grid .custom-content-course {
          width: 840px;
        }
      }
      .gpt-style .custom-grid .custom-title {
        font-family: Merriweather_Bold;
        font-size: 24px;
        color: #0F1F26;
        margin-bottom: 16px;
      }
      .gpt-style .custom-grid .custom-description {
        font-family: Open_Sans;
        font-size: 16px;
        color: #4F5B66;
        padding-bottom: 24px;
      }
      .gpt-style .custom-grid .custom-certification {
        color: #C34849;
      }
      .gpt-style .custom-grid .custom-button {
        color: #033A54;
        background-color: #fff;
        border: 1px solid #033A54;
        font-family: Open_Sans_Bold;
        font-size: 14px;
        border-radius: 4px;
        text-align: center;
        padding: 10px;
        width: 132px;
      }
      .gpt-style .custom-grid .custom-button:hover {
        background-color: #033A54;
        color: #fff;
      }
      .gpt-style .dayElement {
        padding: 16px;
        text-align: left;
        border: 1px solid #CAD6E0;
        height: 183px;
        color: #0F1F26;
      }
      .gpt-style .dayelementActive {
        cursor: pointer;
        position: relative;
        height: 183px;
        background-color: #F2FBFF;
        border: 1px solid #406986;
      }
      .gpt-style .eventGroup {
        position: relative;
        height: 140px;
        font-size: 0.875rem; /* Equivalent to text-sm in Tailwind */
        overflow: hidden;
      }
      .gpt-style .eventItem a {
        text-overflow: ellipsis;
        overflow: hidden;
      }
      .gpt-style .emptyCell {
        padding: 16px;
        text-align: left;
      }
      
      /* Your custom CSS styles go here */
      /* Add your custom CSS for other elements here */
      
      /*# sourceMappingURL=calender-style.css.map */
      
</style>


<body class="box-border" x-data="{ showCalendar: false }">

    <section class="gpt-style section">
        <div class="flex-container row-large justify-between">
            <!-- <h2 id="currentMonth" class="text-lg font-semibold"></h2> -->
            <div class="flex-container row-md justify-between" x-show="showCalendar">
                <div class="button-image-container">
                    <button id="currentMonth" data-dropdown-toggle="dropdownNavbar5" class="btn">
                        <!-- Your button content -->
                    </button>
                    <div id="dropdownNavbar5" class="dropdown-menu">
                        <ul id="monthList" class="dropdown-list" aria-labelledby="dropdownLargeButton">
                            <!-- List items go here -->
                        </ul>
                    </div>
                </div>
                <div class="flex items-center">
                    <button id="prevMonth" class="button-image"></button>
                    <button id="nextMonth" class="button-image-rotate"></button>
                </div>
            </div>
            <div class="flex-container row-md justify-between" x-show="!showCalendar">
                <div class="button-image-container md-margin-right-40 margin-right-5">
                    <button id="currentRange" data-dropdown-toggle="dropdownNavbar6" class="btn">
                        <!-- Your button content -->
                        4 Mar, 2022 - 9 May, 2022 <svg class="w-2.5 h-2.5 ml-2.5"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <div id="dropdownNavbar6" class="dropdown-menu">
                        <ul id="rangeList" class="dropdown-list"  aria-labelledby="dropdownLargeButton">
                            <!-- List items go here -->
                        </ul>
                    </div>
                </div>
                <div class="flex items-center">
                    <button id="prevRange" class="button-image"></button>
                    <button id="nextRange" class=" button-image-rotate"></button>
                </div>
            </div>
        <div style="display: flex; justify-content: space-between;" class="row-large">
            <div class="search-container">
                <div class="search-icon">
                    <svg class="search-icon-svg" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path class="search-icon-path" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search icon</span>
                </div>
                <input type="text" id="searchInput" class="search-input" placeholder="Search..." x-show="showCalendar">
                <input type="text" id="searchInputlist" class="search-input" placeholder="Search..." x-show="!showCalendar">
            </div>
            <div class="flex-container-calender">

                    <div class="button " x-show="showCalendar" @click="showCalendar=false">
                        <img src="assets/images/List-icon.svg" class="icon">
                        <p class="button-label list-label">List</p>
                    </div>
                    <div class="button button-label-white" x-show="!showCalendar" @click="showCalendar=false">
                        <img src="assets/images/List-icon-white.svg" class="icon">
                        <p class="button-label-white list-label-white">List</p>
                    </div>
                    <div class="button button-label-white" x-show="showCalendar" @click="showCalendar=true">
                        <img src="assets/images/Calendar-icon.svg" class="icon">
                        <p class="button-label-white ">Month</p>
                    </div>
                    <div class="button" x-show="!showCalendar" @click="showCalendar=true">
                        <img src="assets/images/Calendar-icon-blue.svg" class="icon">
                        <p class="button-label list-label">Month</p>
                    </div>

            </div>
        </div>
        </div>
        <div class="grid" x-show="showCalendar">
            <div class="grid-label">Sun</div>
            <div class="grid-label">Mon</div>
            <div class="grid-label">Tue</div>
            <div class="grid-label">Wed</div>
            <div class="grid-label">Thu</div>
            <div class="grid-label">Fri</div>
            <div class="grid-label">Sat</div>
        </div>
        <div id="calendar" class="grid calendar" x-show="showCalendar"></div>
        <div x-show="!showCalendar">
            <div class="custom-content">
                <hr class="custom-hr">
                <p class="custom-text">March 2022</p>
                <hr class="custom-hr">
            </div>
            <div class="custom-grid">
                <div class="custom-course">
                    <p class="custom-date">March 27, 2018 @ 9:00 am - March 29, 2018 @ 5:00 pm</p>
                    <div class="custom-card">
                        <img src="assets/images/course.png" class="custom-image">
                        <div class="custom-content-course">
                            <p class="custom-title">Fundamentals of Agile Software Development (ICP)</p>
                            <p class="custom-description">This course is an efficient and effective way to introduce you to the fundamentals of the Agile mindset, values, principles as well as a broad overview of popular methods and practices. Upon attending this course, you’ll earn the <span class="custom-certification">ICAgile Certified Professional (ICP)</span> certificate.</p>
                            <button type="button" class="custom-button">Learn more</button>
                        </div>
                    </div>
                </div>
            
                <div class="custom-course">
                    <p class="custom-date">March 31, 2018 @ 9:00 am - 5:00 pm</p>
                    <div class="custom-card">
                        <img src="assets/images/course.png" class="custom-image">
                        <div class="custom-content-course">
                            <p class="custom-title">Agile Team Facilitation (ICP-ATF)</p>
                            <p class="custom-description">Agile teams are self-organizing and highly collaborative. Team leaders or scrum masters should understand the group dynamics and possess facilitation skills to effectively facilitate group activities. This course provides an in-depth understanding of how collaborative activities can be planned, organized, and run. Upon attending this course, you’ll earn the <span class="custom-certification">ICAgile Certified Professional for Agile Team Facilitation (ICP-ATF)</span> certificate.</p>
                            <button type="button" class="custom-button">Learn more</button>
                        </div>
                    </div>
                </div>
            
                <div class="custom-course">
                    <p class="custom-date">March 27, 2018 @ 9:00 am - March 29, 2018 @ 5:00 pm</p>
                    <div class="custom-card">
                        <img src="assets/images/course.png" class="custom-image">
                        <div class="custom-content-course">
                            <p class="custom-title">Agile Professional Programming (ICP-PRG)</p>
                            <p class="custom-description">This course covers the engineering skills and tools required to become a professional programmer. It empowers agile programmers to develop clean code and produce quality products, taking into consideration product changes and technical innovation. Upon attending this course, you’ll earn the <span class="custom-certification">ICAgile Certified Professional in Agile Programming (ICP-PRG)</span> certificate.</p>
                            <button type="button" class="custom-button">Learn more</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="owlcarousel/dist/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>

    <!-- JavaScript for handling calendar functionality and search -->
    <script>
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');
        const currentMonthDisplay = document.getElementById('currentMonth');
        const currentRangeDisplay = document.getElementById('currentRange');
        const monthDropdown = document.getElementById('monthDropdown');
        const calendar = document.getElementById('calendar');
        const searchInput = document.getElementById('searchInput');
        // var selectedRange="10 sep, 2023 - 15 Dec, 2023";
        // Sample events data (you can replace this with your event data)
        const events = [
            { start: '2023-10-10', end: '2023-10-12', startTime: "9:00 am", endTime: "5:00 pm", title: 'Fundamentals of Agile Software Development (ICP)', details: 'This course is an efficient and effective way to introduce you to the fundamentals of the Agile mindset, values, principles as well as a broad overview of popular methods and practices. Upon attending this course, you’ll earn the ICAgile Certified Professional (ICP) certificate.' },
            { start: '2023-10-25', end: '2023-10-25', startTime: "9:00 am", endTime: "5:00 pm", title: 'Agile Team Facilitation (ICP-ATF)', details: 'Agile teams are self-organizing and highly collaborative. Team leaders or scrum masters should understand the group dynamics and possess facilitation skills to effectively facilitate group activities. This course provides an in-depth understanding of how collaborative activities can be planned, organised and run. Upon attending this course, you’ll earn the ICAgile Certified Professional for Agile Team Facilitation (ICP-ATF) certificate.' },
            // Add more events here
        ];
        // function selectRange(range){
        //     // console.log(range);

        //     selectedRange=range;
        //     currentRangeDisplay.innerHTML=` ${selectedRange} <svg class="w-2.5 h-2.5 ml-2.5"
        //                             aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
        //                             viewBox="0 0 10 6">
        //                             <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
        //                                 stroke-width="2" d="m1 1 4 4 4-4" />
        //                         </svg>`;

        // }

        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        function updateCalendar(searchTerm = '') {
            // Clear previous calendar content
            calendar.innerHTML = '';

            // Set the calendar header to display the current month and year
            // currentMonthDisplay.textContent = `${new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' })} ${currentYear} `;
            currentMonthDisplay.innerHTML = `${new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' })} ${currentYear} <svg class="w-2.5 h-2.5 ml-2.5"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 4 4 4-4" />
                        </svg>`
            // currentRangeDisplay.innerHTML=` ${selectedRange} <svg class="w-2.5 h-2.5 ml-2.5"
            //     aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            //     viewBox="0 0 10 6">
            //     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
            //         stroke-width="2" d="m1 1 4 4 4-4" />
            // </svg>`
            // Calculate the first day of the month
            const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();

            // Calculate the number of days in the current month
            const lastDay = new Date(currentYear, currentMonth + 1, 0).getDate();

            // Generate calendar days, including leading empty cells
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'emptyCell p-[16px] text-start ';
                calendar.appendChild(emptyCell);
            }

            // Generate calendar days
            for (let day = 1; day <= lastDay; day++) {
                const date = new Date(currentYear, currentMonth, day + 1).toISOString().split('T')[0];
                const filteredEvents = events.filter(event => event.start <= date && event.end >= date);

                // Filter events by search term
                const filteredEventsByName = filteredEvents.filter(event => event.title.toLowerCase().includes(searchTerm.toLowerCase()));

                const dayElement = document.createElement('div');
                dayElement.id = `${day}-${currentMonth}-${currentYear}`;
                dayElement.className = ' dayElement p-[16px] text-start border border-[#CAD6E0] h-[183px] text-[#0F1F26]';
                dayElement.style.fontFamily = "Open_Sans";

                // Highlight today's date
                // if (currentMonth === today.getMonth() && currentYear === today.getFullYear() && day === today.getDate()) {
                //     dayElement.classList.add('bg-[#F2FBFF]');
                // }

                // Check if there are events on this day

                if (filteredEventsByName.length > 0) {
                    const eventGroup = document.createElement('div');
                    eventGroup.className = ' eventGroup relative h-[140px] text-sm overflow-hidden  ';
                    eventGroup.style.cursor = 'pointer';


                    filteredEventsByName.forEach(event => {
                        const eventItem = document.createElement('a');
                        eventItem.className = 'eventItem';
                        
                        eventItem.textContent = `${event.title} \n ${event.startTime} - ${event.endTime}`;
                        eventItem.href=`course.html`;
                        eventItem.style.textOverflow='ellipsis';
                        eventItem.style.overflow='hidden';
                        eventGroup.appendChild(eventItem);
                    });



                    dayElement.textContent = day;

                    dayElement.classList.add('dayelementActive','cursor-pointer', 'relative', 'h-[183px]', 'bg-[#F2FBFF]', 'border', 'border-[#406986]');




                    dayElement.appendChild(eventGroup);



                } else {
                    dayElement.textContent = day;
                }

                calendar.appendChild(dayElement);

            }


        }

        // Initial calendar update
        updateCalendar();
        // console.log(today.getMonth.currentMonth);
        // console.log(currentMonth);
        // console.log(currentYear);
        // console.log(document.getElementById('10-9-2023'));
        function updateCalendarByMonth(month) {
            const selectedDate = new Date(currentYear, month, 1);
            currentMonth = selectedDate.getMonth();
            currentYear = selectedDate.getFullYear();
            updateCalendar(searchInput.value);
        }

        // Function to populate the month list
        function populateMonthList() {
            const monthList = document.getElementById('monthList');
            monthList.innerHTML = ''; // Clear existing months

            for (let i = 0; i < 12; i++) {
                const year = currentYear;
                const month = new Date(currentYear, i, 1).toLocaleString('default', { month: 'long' });
                const listItem = document.createElement('li');
                const anchor = document.createElement('a');
                anchor.textContent = `${month} ${year}`;
                // anchor.href = ''; // Add a href attribute if needed
                anchor.classList.add('block'); // 
                anchor.classList.add('py-2');
                anchor.classList.add('px-2');
                anchor.classList.add('hover:bg-[#F2FBFF]');
                // anchor.classList.add('hover:text-[#033A54]');
                anchor.style.fontFamily = 'Open_Sans';
                anchor.textContent = `${month} ${year}`;
                listItem.appendChild(anchor);
                monthList.appendChild(listItem);

                // Add click event listener to select the month
                listItem.addEventListener('click', () => {
                    updateCalendarByMonth(i); // Update the calendar based on the selected month
                    document.getElementById('monthDropdown').classList.remove('show'); // Close the dropdown
                });
            }
        }

        // Set the default selected month and year in the month list
        populateMonthList();

        // Event listener for navigating to previous month
        prevMonthBtn.addEventListener('click', () => {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            populateMonthList(); // Update month list
            updateCalendar(searchInput.value);
        });

        // Event listener for navigating to next month
        nextMonthBtn.addEventListener('click', () => {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            populateMonthList(); // Update month list
            updateCalendar(searchInput.value);
        });

        // Event listener for search input change
        searchInput.addEventListener('input', () => {
            updateCalendar(searchInput.value);
        });
        
        </script>









</body>