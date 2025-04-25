@extends('layouts.master.admin-template-v2.layout')
@section('content')
<div class="puc-date-topbar">
    <button class="btn btn-link active">
        All
    </button>
    <!-- <button class="btn btn-link">
        Today
    </button> -->
    <button  type="button" data-filter="today"
                class="btn btn-link  text-nowrap px-3 mx-1 filter-btns filter_today d-flex flex-column align-items-center justify-content-center" >
                Today
            </button>
    <button class="btn btn-link">
        Yesterday
    </button>
    <button class="btn btn-link">
        Last Week
    </button>
    <button class="btn btn-link">
        Last Month
    </button>
    <!-- <button class="btn btn-link">
        August
    </button> -->
    <button class="btn btn-link">
        Date Range
    </button>
</div>
<div class="progress-cards-row">
    <div class="progress-card">
        <p>
            Pending Retailers
        </p>
        <h1>
            85
        </h1>
        <img src="{{asset('assets-v2')}}/images/arrow-up-line.svg" class="arrow" alt="">
    </div>
    <div class="progress-card">
        <p>
            Pending CP
        </p>
        <h1 id="c_total_puc">
           0
        </h1>
        <img src="{{asset('assets-v2')}}/images/arrow-up-line.svg" class="arrow" alt="">
    </div>
    <!-- <div class="progress-card">
        <p>
            Referral Pending
        </p>
        <h1>
            85
        </h1>
        <img src="{{asset('assets-v2')}}/images/arrow-up-line.svg" class="arrow" alt="">
    </div> -->
    <div class="progress-card">
        <p>
            Pending Orders
        </p>
        <h1>
            65
        </h1>
        <img src="{{asset('assets-v2')}}/images/arrow-up-line.svg" class="arrow" alt="">
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <div class="payments-card"
            style="background: conic-gradient(from 123.98deg at 52.63% 56.59%, #000000 0deg, #363636 360deg);">
            <h6>
                Total Revenue
            </h6>
            <h2>
                ₹6,00,000
            </h2>
            <div class="time">
                <svg width="15" height="8" viewBox="0 0 15 8" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.2861 0.5H14.2861V4.5" stroke="#16FF00" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M14.2861 0.5L8.63613 6.15C8.54267 6.24161 8.41701 6.29293 8.28613 6.29293C8.15526 6.29293 8.0296 6.24161 7.93613 6.15L5.63613 3.85C5.54267 3.75839 5.41701 3.70707 5.28613 3.70707C5.15526 3.70707 5.0296 3.75839 4.93613 3.85L1.28613 7.5"
                        stroke="#16FF00" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>
                    <span>
                        8.2%
                    </span>
                    from last week
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="payments-card"
            style="background: conic-gradient(from 123.98deg at 52.63% 56.59%, #138808 0deg, #053800 360deg);">
            <h6>
                Total Fees
            </h6>
            <h2>
                ₹1,25,000
            </h2>
            <div class="time">
                <svg width="15" height="8" viewBox="0 0 15 8" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.2861 0.5H14.2861V4.5" stroke="#16FF00" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M14.2861 0.5L8.63613 6.15C8.54267 6.24161 8.41701 6.29293 8.28613 6.29293C8.15526 6.29293 8.0296 6.24161 7.93613 6.15L5.63613 3.85C5.54267 3.75839 5.41701 3.70707 5.28613 3.70707C5.15526 3.70707 5.0296 3.75839 4.93613 3.85L1.28613 7.5"
                        stroke="#16FF00" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>
                    <span>
                        8.2%
                    </span>
                    from last week
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="payments-card"
            style="background: conic-gradient(from 123.98deg at 52.63% 56.59%, #6800F9 0deg, #2C0563 360deg);">
            <h6>
                PUC Revenue
            </h6>
            <h2>
                ₹2,44,000
            </h2>
            <div class="time">
                <svg width="15" height="8" viewBox="0 0 15 8" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.2861 0.5H14.2861V4.5" stroke="#16FF00" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M14.2861 0.5L8.63613 6.15C8.54267 6.24161 8.41701 6.29293 8.28613 6.29293C8.15526 6.29293 8.0296 6.24161 7.93613 6.15L5.63613 3.85C5.54267 3.75839 5.41701 3.70707 5.28613 3.70707C5.15526 3.70707 5.0296 3.75839 4.93613 3.85L1.28613 7.5"
                        stroke="#16FF00" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>
                    <span>
                        8.2%
                    </span>
                    from last week
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="payments-card"
            style="background: conic-gradient(from 123.98deg at 52.63% 56.59%, #0080F9 0deg, #011683 360deg);">
            <h6>
                Reg. Revenue
            </h6>
            <h2>
                ₹45,000
            </h2>
            <div class="time">
                <svg width="15" height="8" viewBox="0 0 15 8" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.2861 0.5H14.2861V4.5" stroke="#16FF00" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M14.2861 0.5L8.63613 6.15C8.54267 6.24161 8.41701 6.29293 8.28613 6.29293C8.15526 6.29293 8.0296 6.24161 7.93613 6.15L5.63613 3.85C5.54267 3.75839 5.41701 3.70707 5.28613 3.70707C5.15526 3.70707 5.0296 3.75839 4.93613 3.85L1.28613 7.5"
                        stroke="#16FF00" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p>
                    <span>
                        8.2%
                    </span>
                    from last week
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <div class="ucp-card">
            <h6>
                PUC Created
            </h6>
            <h2>
                3520
            </h2>
            <div class="time">
                <svg width="15" height="14" viewBox="0 0 15 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_136_8460)">
                        <path d="M10.2861 3.5H14.2861V7.5" stroke="#0D9E00" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M14.2861 3.5L8.63613 9.15C8.54267 9.24161 8.41701 9.29293 8.28613 9.29293C8.15526 9.29293 8.0296 9.24161 7.93613 9.15L5.63613 6.85C5.54267 6.75839 5.41701 6.70707 5.28613 6.70707C5.15526 6.70707 5.0296 6.75839 4.93613 6.85L1.28613 10.5"
                            stroke="#0D9E00" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_136_8460">
                            <rect width="14" height="14" fill="white" transform="translate(0.786133)" />
                        </clipPath>
                    </defs>
                </svg>
                <p>
                    <span>
                        8.2%
                    </span>
                    from last week
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ucp-card">
            <h6>
                Completed Retailers
            </h6>
            <h2>
                300
            </h2>
            <div class="time">
                <svg width="15" height="14" viewBox="0 0 15 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_136_8460)">
                        <path d="M10.2861 3.5H14.2861V7.5" stroke="#0D9E00" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M14.2861 3.5L8.63613 9.15C8.54267 9.24161 8.41701 9.29293 8.28613 9.29293C8.15526 9.29293 8.0296 9.24161 7.93613 9.15L5.63613 6.85C5.54267 6.75839 5.41701 6.70707 5.28613 6.70707C5.15526 6.70707 5.0296 6.75839 4.93613 6.85L1.28613 10.5"
                            stroke="#0D9E00" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_136_8460">
                            <rect width="14" height="14" fill="white" transform="translate(0.786133)" />
                        </clipPath>
                    </defs>
                </svg>
                <p>
                    <span>
                        8.2%
                    </span>
                    from last week
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="ucp-card">
            <h6>
                Completed CP
            </h6>
            <h2>
                300
            </h2>
            <div class="time">
                <svg width="15" height="14" viewBox="0 0 15 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_136_8460)">
                        <path d="M10.2861 3.5H14.2861V7.5" stroke="#0D9E00" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M14.2861 3.5L8.63613 9.15C8.54267 9.24161 8.41701 9.29293 8.28613 9.29293C8.15526 9.29293 8.0296 9.24161 7.93613 9.15L5.63613 6.85C5.54267 6.75839 5.41701 6.70707 5.28613 6.70707C5.15526 6.70707 5.0296 6.75839 4.93613 6.85L1.28613 10.5"
                            stroke="#0D9E00" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_136_8460">
                            <rect width="14" height="14" fill="white" transform="translate(0.786133)" />
                        </clipPath>
                    </defs>
                </svg>
                <p>
                    <span>
                        8.2%
                    </span>
                    from last week
                </p>
            </div>
        </div>
    </div>
</div>




<script src="{{ asset('assets_admin/customjs/script_admindashboard.js') }}"></script>
@endsection

