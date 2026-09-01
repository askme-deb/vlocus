

<?php $__env->startSection('title','Dashboard'); ?>

<?php $__env->startSection('css'); ?>
<!-- Chart.js CDN (v4) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
    :root {
        --bg: #f6f8fb;
        --card: #ffffff;
        --muted: #6b7280;
        --primary: #2563eb;
        --accent: #06b6d4;
        --radius: 12px;
    }

    * {
        box-sizing: border-box
    }

    body {
        margin: 0;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        color: #0f172a;
        background: linear-gradient(180deg, #fbfdff, var(--bg));
        padding: 20px
    }

    .wrap {
        margin: 0 auto
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px
    }

    .brand {
        display: flex;
        gap: 12px;
        align-items: center
    }

    .logo {
        width: 125px;
        height: 56px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    h1 {
        margin: 0;
        font-size: 20px
    }

    .subtitle {
        margin: 0;
        color: var(--muted);
        font-size: 13px
    }

    /* controls */
    .controls {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap
    }

    .control {
        background: var(--card);
        padding: 8px;
        border-radius: 10px;
        border: 1px solid rgba(15, 23, 42, 0.05);
        display: flex;
        gap: 8px;
        align-items: center
    }

    input[type=date],
    select {
        border: 0;
        background: transparent;
        font-size: 13px
    }

    button.btn,
    a.btn {
        background: var(--primary);
        color: #fff;
        border: 0;
        padding: 8px 12px;
        border-radius: 10px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        line-height: 1.5;
    }

    button.ghost,
    a.ghost {
        background: transparent;
        border: 1px solid rgba(15, 23, 42, 0.06);
        padding: 8px 10px;
        border-radius: 10px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        color: inherit;
        line-height: 1.5;
    }

    /* layout */
    .layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 16px
    }

    @media (max-width:980px) {
        .layout {
            grid-template-columns: 1fr;
        }
    }

    .sidebar {
        background: var(--card);
        padding: 12px;
        border-radius: var(--radius);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04)
    }

    .tabs {
        display: flex;
        gap: 6px;
        margin-bottom: 10px
    }

    .tab {
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        color: var(--muted);
        background: transparent;
        border: 1px solid transparent
    }

    .tab.active {
        background: linear-gradient(90deg, rgba(37, 99, 235, 0.08), rgba(6, 182, 212, 0.03));
        color: var(--primary);
        border: 1px solid rgba(37, 99, 235, 0.08)
    }

    .card-list {
        display: flex;
        flex-direction: column;
        gap: 8px
    }

    .menu-card {
        display: flex;
        gap: 10px;
        align-items: center;
        padding: 10px;
        border-radius: 10px;
        cursor: pointer
    }

    .menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(15, 23, 42, 0.04)
    }

    .muted {
        color: var(--muted);
        font-size: 13px
    }

    .content {
        display: flex;
        flex-direction: column;
        gap: 12px
    }

    .panel {
        background: var(--card);
        padding: 12px;
        border-radius: var(--radius);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04)
    }

    footer {
        color: var(--muted);
        font-size: 13px;
        text-align: center;
        margin-top: 10px
    }
    .bg-purple { background-color: #6f42c1 !important; }
    .bg-orange { background-color: #fd7e14 !important; }
    .text-purple { color: #6f42c1 !important; }
    .text-orange { color: #fd7e14 !important; }

    .apexcharts-tooltip-box {
        background: #fff;
        padding: 8px;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .apexcharts-tooltip-box.bg-danger {
        background: #dc3545 !important;
        color: white !important;
    }
    .das_warp {
    /*padding: 20px;*/
}
.menu-card:hover {
    background: #2563eb2e;
}
.menu-card.active {
    background: #2563eb2e;
}

/*--------------------------new 3/7/26-------------------*/
:root {
    --primary: #4f46e5;
    --bg-body: #f8fafc;
    --card-bg: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
}

body {
    background-color: var(--bg-body);
    font-family: 'Segoe UI', Roboto, sans-serif;
    color: var(--text-main);
}

.main-wrapper {
    padding: 0px;
}

header {
    background: var(--card-bg);
    padding: 15px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.controls {
    display: flex;
    gap: 15px;
    /*align-items: flex-end;*/
}

.control label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 5px;
}

.control input, .control select {
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    outline: none;
}

.btn {
    background: var(--primary);
    color: white;
    padding: 8px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}

.layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 20px;
}

.sidebar {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.menu-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s;
}

.menu-card:hover {
    background: #f1f5f9;
}

.menu-card.active {
    background: #eef2ff;
    border-left: 4px solid var(--primary);
}
/
.card {
    border-radius: 12px !important;
    padding: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
}

.panel {
    background: transparent;
}

.das_warp h1 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
}

.card-1 {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.muted {
    font-size: 12px;
    color: var(--text-muted);
}

h5{
    font-size: 17px !important;
}
.main-wrapper .main-content {
        padding: 0px 0px !important;
}


.pace-done {
    background: #eff1f3;
}

.pace-done main.main-wrapper {
    padding: 0px !important;
}


/* ========================================================================== */
/* FIXED MOBILE RESPONSIVE FIX FOR HEADER & FILTERS                           */
/* ========================================================================== */

@media (max-width: 768px) {
    /* Header container-ke column direction-e ana */
    header {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 15px !important;
        padding: 15px !important;
        height: auto !important;
    }

    /* Logo ba brand section-ke proper spacing dewa */
    header .brand {
        display: flex !important;
        justify-content: center !important;
        width: 100% !important;
        margin-bottom: 5px;
    }

    /* Controls form-ke vertical stack kora */
    header form.controls {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        gap: 12px !important;
        margin: 0 !important;
    }

    /* Prottekta individual control field-ke 100% width kora */
    header form.controls .control {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100% !important;
        box-sizing: border-box !important;
        padding: 10px !important;
    }

    /* Input field ebong select dropdown gulo jate baire na beroy */
    header form.controls .control input[type="date"],
    header form.controls .control select,
    header form.controls .control input[type="text"] {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    /* Button and Reset field alignment */
    header form.controls button#applyFilters,
    header form.controls a#resetFilters {
        width: 100% !important;
        text-align: center !important;
        margin: 5px 0 !important;
    }

    /* Layout grid fix */
    .layout {
        grid-template-columns: 1fr !important;
        gap: 15px !important;
    }

    .sidebar {
        position: relative !important;
        top: 0 !important;
        height: auto !important;
        width: 100% !important;
    }
    header {
    display: block;
}

.primary-menu {
    visibility: hidden;
}

.layout {
    display: block;
}

.main-wrapper {
    margin-top: 4px !important;
}

.dropdown-menu-end[data-bs-popper] {
    left: 0;
    right: auto;
}

}







</style>


<style>
  /* ==========================================================
     CLEAN & BRIGHT PREMIUM SADA (LIGHT) THEME (ONLY CSS)
     ========================================================== */

  :root {
    --light-bg: #f8fafc;
    --light-card: #ffffff;
    --light-border: #e2e8f0;
    --light-text-main: #0f172a;
    --light-text-muted: #64748b;
    --light-primary: #2563eb;
    --light-primary-gradient: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    --light-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    --light-radius: 18px;
  }

  /* Body & Background */
  body {
    background-color: var(--light-bg) !important;
    color: var(--light-text-main) !important;
    background-image: none !important;
  }

  .wrap {
    max-width: 100% !important;
  }

  /* Header & Filters Card */
  header {
    background: var(--light-card) !important;
    backdrop-filter: none !important;
    border: 1px solid var(--light-border) !important;
    border-radius: var(--light-radius) !important;
    padding: 20px 28px !important;
    box-shadow: var(--light-shadow) !important;
    margin-bottom: 24px !important;
  }

  .logo {
    background: #f1f5f9;
    border: 1px solid var(--light-border);
  }

  .controls {
    gap: 12px !important;
  }

  .control {
    background: #f8fafc !important;
    border: 1px solid var(--light-border) !important;
    border-radius: 12px !important;
    padding: 8px 16px !important;
    transition: all 0.25s ease;
  }

  .control:focus-within {
    border-color: var(--light-primary) !important;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
    background: #ffffff !important;
  }

  .control label, .control .small {
    color: var(--light-text-muted) !important;
    font-size: 11px !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
  }

  input[type=date], select {
    color: var(--light-text-main) !important;
    font-weight: 500;
  }

  /* Action Buttons */
  header form.controls button.btn {
    background: var(--light-primary-gradient) !important;
    border-radius: 12px !important;
    padding: 10px 24px !important;
    font-weight: 600 !important;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25);
    transition: all 0.3s ease;
  }

  header form.controls button.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
  }

  header form.controls a.ghost {
    background: #f1f5f9 !important;
    border: 1px solid var(--light-border) !important;
    border-radius: 12px !important;
    padding: 10px 20px !important;
    color: var(--light-text-muted) !important;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  header form.controls a.ghost:hover {
    background: #e2e8f0 !important;
    color: var(--light-text-main) !important;
  }

  /* Sidebar Navigation */
  .layout {
    gap: 24px !important;
  }

  .sidebar {
    background: var(--light-card) !important;
    backdrop-filter: none !important;
    border: 1px solid var(--light-border) !important;
    padding: 20px !important;
    border-radius: var(--light-radius) !important;
    box-shadow: var(--light-shadow) !important;
  }

  .tabs {
    background: #f1f5f9;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid var(--light-border);
    margin-bottom: 16px;
  }

  .tab {
    border-radius: 8px !important;
    color: var(--light-text-muted);
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s ease;
  }

  .tab.active {
    background: #ffffff !important;
    color: var(--light-primary) !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
    border: none !important;
  }

  .menu-card {
    border-radius: 12px !important;
    padding: 14px !important;
    margin-bottom: 8px;
    border: 1px solid transparent;
    transition: all 0.25s ease;
  }

  .menu-card:hover {
    background: #f8fafc !important;
    border-color: var(--light-border);
    transform: translateX(4px);
  }

  .menu-card.active {
    background: #eff6ff !important;
    border-color: #bfdbfe !important;
    border-left: 4px solid var(--light-primary) !important;
  }

  .menu-icon {
    background: #f8fafc !important;
    border: 1px solid var(--light-border) !important;
    border-radius: 10px !important;
  }

  .menu-card strong {
    color: var(--light-text-main) !important;
  }

  .muted {
    color: var(--light-text-muted) !important;
  }

  /* Main Panels & Cards */
  .panel {
    background: transparent !important;
    padding: 0 !important;
  }

  .card, .card-1 {
    background: var(--light-card) !important;
    backdrop-filter: none !important;
    border: 1px solid var(--light-border) !important;
    border-radius: var(--light-radius) !important;
    box-shadow: var(--light-shadow) !important;
    color: var(--light-text-main) !important;
    transition: all 0.3s ease;
  }

  .card:hover, .card-1:hover {
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08) !important;
    transform: translateY(-2px);
  }

  /* Metric KPI Cards Styling */
  .panel .row.mb-3 .card,
  .panel .row.mb-4 .card {
    border-radius: 16px !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
  }

  /* Headings & Texts */
  .card-title, .card-header, .das_warp h1 {
    color: var(--light-text-main) !important;
    font-weight: 700 !important;
    letter-spacing: -0.02em;
  }

  /* ApexCharts Clean Light Theme Tooltip Override */
  .apexcharts-tooltip.apexcharts-theme-light {
    background: #ffffff !important;
    border-color: var(--light-border) !important;
    color: var(--light-text-main) !important;
    box-shadow: 0 20px 30px rgba(15, 23, 42, 0.12) !important;
  }

  .apexcharts-tooltip-title {
    background: #f8fafc !important;
    border-bottom: 1px solid var(--light-border) !important;
    color: var(--light-text-muted) !important;
  }

  .apexcharts-legend-text {
    color: var(--light-text-muted) !important;
  }

  /* Mobile Responsive Spacing */
  @media (max-width: 768px) {
    body {
      padding: 8px !important;
    }
    header {
      padding: 15px !important;
      border-radius: 12px !important;
    }
  }
  
  /* ==========================================================
     COLORFUL GRADIENT CARDS STYLING (ONLY CSS)
     ========================================================== */

  /* Prottekta card-ke alada colorful gradient background dewa */
  .panel .row .col-md-4:nth-child(1) .card,
  .col-xl-3:nth-child(1) .card,
  .row > div:nth-child(1) .card {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%) !important;
    color: #ffffff !important;
  }

  .panel .row .col-md-4:nth-child(2) .card,
  .col-xl-3:nth-child(2) .card,
  .row > div:nth-child(2) .card {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
    color: #ffffff !important;
  }

  .panel .row .col-md-4:nth-child(3) .card,
  .col-xl-3:nth-child(3) .card,
  .row > div:nth-child(3) .card {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%) !important;
    color: #ffffff !important;
  }

  .col-xl-3:nth-child(4) .card,
  .row > div:nth-child(4) .card {
    background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%) !important;
    color: #ffffff !important;
  }

  /* Card-er vitorer text ebong icons gulo white kora jate clear dekha jay */
  .card, .card-1 {
    border: none !important;
    border-radius: 18px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important;
  }

  .card h2, .card h4, .card h5, .card h6, .card p, .card i {
    color: #ffffff !important;
  }

  .card .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
  }
</style>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="wrap">

    <header>
        <div class="brand">
            <div class="logo"><img src="<?php echo e(asset('assets/logo.png')); ?>" alt=""></div>
        </div>

        <form method="GET" action="<?php echo e(route('dashboard')); ?>" class="controls" aria-label="filters">
            <div class="control">
                <label class="small" for="dateFrom">From</label>
                <input type="date" id="dateFrom" name="start_date" value="<?php echo e($filters['start_date']); ?>" />
            </div>
            <div class="control">
                <label class="small" for="dateTo">To</label>
                <input type="date" id="dateTo" name="end_date" value="<?php echo e($filters['end_date']); ?>" />
            </div>
            <div class="control">
                <label class="small" for="vehicleSelect">Vehicle</label>
                <select id="vehicleSelect" name="vehicle_id">
                    <option value="">All Vehicles</option>
                    <?php $__currentLoopData = $vehicle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($v->id); ?>" <?php echo e((string) $filters['vehicle_id'] === (string) $v->id ? 'selected' : ''); ?>><?php echo e($v->vehicle_number); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="control">
                <label class="small" for="driverSelect">Driver</label>
                <select id="driverSelect" name="driver_id">
                    <option value="">All Drivers</option>
                    <?php $__currentLoopData = $driver; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($dr->id); ?>" <?php echo e((string) $filters['driver_id'] === (string) $dr->id ? 'selected' : ''); ?>><?php echo e($dr->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="btn" id="applyFilters">Apply</button>
            <a href="<?php echo e(route('dashboard')); ?>" class="ghost" id="resetFilters">Reset</a>
        </form>
    </header>

    <div class="layout">
        <aside class="sidebar" aria-label="report menu">
            <!-- Static nav tabs (same design) -->
            <div class="tabs" role="tablist">
                <div class="tab active" data-tab="reports" role="tab">Reports</div>
                <div class="tab" data-tab="safety" role="tab">Driver Behaviour</div>
            </div>

            <!-- Reports List -->
            <div id="reportsList" class="card-list">
                <div class="menu-card" data-report="trip">
                    <div class="menu-icon">🗺️</div>
                    <div><strong>Trip Summary</strong>
                        <div class="muted">Overview of trips, durations, and stops.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="route">
                    <div class="menu-icon">📈</div>
                    <div><strong>Route History</strong>
                        <div class="muted">Playback and route taken over time.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="runidle">
                    <div class="menu-icon">⏱️</div>
                    <div><strong>Run & Idle</strong>
                        <div class="muted">Engine run vs idle analysis.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="distance">
                    <div class="menu-icon">📏</div>
                    <div><strong>Distance</strong>
                        <div class="muted">Distance traveled per vehicle.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="geofence">
                    <div class="menu-icon">🎯</div>
                    <div><strong>Geo-fence</strong>
                        <div class="muted">Geofence enter/exit events & alerts.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="overstay">
                    <div class="menu-icon">⏳</div>
                    <div><strong>Overstay</strong>
                        <div class="muted">Time spent beyond thresholds.</div>
                    </div>
                </div>
            </div>

            <!-- Safety List -->
            <div id="safetyList" class="card-list" style="display:none;margin-top:12px">
                <div class="menu-card" data-report="attendance">
                    <div class="menu-icon">📅</div>
                    <div><strong>Monthly Attendance</strong>
                        <div class="muted">Driver attendance calendar.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="loginlogout">
                    <div class="menu-icon">🔐</div>
                    <div><strong>Login/Logout</strong>
                        <div class="muted">Driver login and logout records.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="logintime">
                    <div class="menu-icon">🕒</div>
                    <div><strong>Login Time</strong>
                        <div class="muted">Login durations and trends.</div>
                    </div>
                </div>
                <div class="menu-card" data-report="sos">
                    <div class="menu-icon">🆘</div>
                    <div><strong>Emergency SOS</strong>
                        <div class="muted">SOS events and response history.</div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="content">
            <div class="panel" style="max-width:100%;" id="trip">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white text-center border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-white">Total Trips</h6>
                                <h2 class="fw-bold text-white"><?php echo e($trip_summary['totalTrips']->sum()); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white text-center border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-white">Total Distance</h6>
                                <h2 class="fw-bold text-white"><?php echo e(number_format($trip_summary['totalDistance']->sum(), 2)); ?> km</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white text-center border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="text-white">Total Amount</h6>
                                <h2 class="fw-bold text-white">₹<?php echo e(number_format($trip_summary['totalAmount']->sum(), 2)); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-0">
                    <div class="card-header fw-bold bg-transparent">Trip Summary Overview</div>
                    <div class="card-body">
                        <div id="tripSummaryChart" style="height:380px;"></div>
                    </div>
                </div>
            </div>

            <div class="panel" style="max-width:100%;" id="route">
                <!-- Summary Cards -->
                <div class="row mb-4">
                       <div class="das_warp">
                <h1>Route History</h1>
            </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-primary bg-opacity-10 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Total Distance</p>
                                        <h4 class="mb-0"><?php echo e(number_format($routeHistory['summary']['total_distance'], 1)); ?> km</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-map text-primary h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-success bg-opacity-10 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Total Stops</p>
                                        <h4 class="mb-0"><?php echo e(number_format($routeHistory['summary']['total_stops'])); ?></h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-store text-success h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-info bg-opacity-10 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Total Routes</p>
                                        <h4 class="mb-0"><?php echo e($routeHistory['summary']['total_routes']); ?></h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-trip text-info h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card bg-warning bg-opacity-10 border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">Avg Efficiency</p>
                                        <h4 class="mb-0"><?php echo e($routeHistory['summary']['avg_efficiency']); ?> stops/km</h4>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <i class="bx bx-trending-up text-warning h1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Chart -->
                <div class="row">
                    <div class="col-xl-8">
                        <div class="card border-0 shadow-none">
                            <div class="card-body">
                                <h6 class="card-title mb-4">Route Performance Overview</h6>
                                <div id="routeHistoryChart" style="min-height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="card border-0 shadow-none">
                            <div class="card-body">
                                <h6 class="card-title mb-4">Efficiency Metrics</h6>
                                <div id="efficiencyGauge" style="min-height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel" style="max-width:100%;" id="runidle">
                <!-- Run & Idle Analytics Section -->
                <div class="row">
                    <div class="das_warp">
                <h1>Run & Idle</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <!-- Summary Cards -->
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-run h3 text-success mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($runIdleAnalytics['summary']['total_run_time']); ?></h5>
                                                <p class="text-muted mb-0">Run Time</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-time h3 text-warning mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($runIdleAnalytics['summary']['total_idle_time']); ?></h5>
                                                <p class="text-muted mb-0">Idle Time</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-station h3 text-info mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($runIdleAnalytics['summary']['total_stops']); ?></h5>
                                                <p class="text-muted mb-0">Total Stops</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trending-up h3 text-primary mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($runIdleAnalytics['summary']['overall_efficiency']); ?>%</h5>
                                                <p class="text-muted mb-0">Efficiency</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user h3 text-success mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($runIdleAnalytics['summary']['total_drivers']); ?></h5>
                                                <p class="text-muted mb-0">Drivers</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-car h3 text-info mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($runIdleAnalytics['summary']['total_vehicles']); ?></h5>
                                                <p class="text-muted mb-0">Vehicles</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📈 Efficiency Trend</h6>
                                                <div id="efficiencyTrendChart" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">⏱️ Time Distribution</h6>
                                                <div id="timeDistributionChart" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🛑 Daily Stops Analysis</h6>
                                                <div id="stopsAnalysisChart" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🎯 Performance Score</h6>
                                                <div id="performanceGauge" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- distance modal -->
            <div class="panel" style="max-width:100%;" id="distance">
                <div class="row">
                    <div class="das_warp">
                <h1>Distance</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <!-- Summary Cards -->
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-map h3 text-primary mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($distanceAnalytics['summary']['total_distance']); ?> km</h5>
                                                <p class="text-muted mb-0">Total Distance</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trip h3 text-success mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($distanceAnalytics['summary']['total_trips']); ?></h5>
                                                <p class="text-muted mb-0">Total Trips</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-tachometer h3 text-info mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($distanceAnalytics['summary']['avg_speed']); ?> km/h</h5>
                                                <p class="text-muted mb-0">Avg Speed</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-car h3 text-warning mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($distanceAnalytics['summary']['top_vehicle']); ?></h5>
                                                <p class="text-muted mb-0">Top Vehicle</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user h3 text-success mb-2"></i>
                                                <h5 class="mb-1"><?php echo e(Str::limit($distanceAnalytics['summary']['top_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Top Driver</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-calendar-star h3 text-info mb-2"></i>
                                                <h5 class="mb-1"><?php echo e($distanceAnalytics['summary']['most_active_day']); ?></h5>
                                                <p class="text-muted mb-0">Peak Day</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📊 Daily Distance Trend</h6>
                                                <div id="distanceTrendChart" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🚗 Top Vehicles by Distance</h6>
                                                <div id="vehicleDistanceChart" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">👨‍💼 Top Drivers by Distance</h6>
                                                <div id="driverDistanceChart" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">⚡ Speed Analysis</h6>
                                                <div id="speedAnalysisChart" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- geofence -->
            <div class="panel" style="max-width:100%;" id="geofence">
                <div class="row">
                    <div class="das_warp">
                <h1>Geo-fence</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-map-pin h3 text-primary mb-2"></i>
                                                <h5 class="mb-1" data-card="total-entries"><?php echo e($geofenceAnalytics['summary']['total_entries']); ?></h5>
                                                <p class="text-muted mb-0">Total Entries</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-current-location h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="unique-zones"><?php echo e($geofenceAnalytics['summary']['unique_zones']); ?></h5>
                                                <p class="text-muted mb-0">Zones</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-time h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="avg-duration"><?php echo e($geofenceAnalytics['summary']['avg_duration']); ?>m</h5>
                                                <p class="text-muted mb-0">Avg Stay</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-car h3 text-warning mb-2"></i>
                                                <h5 class="mb-1" data-card="active-vehicle"><?php echo e($geofenceAnalytics['summary']['most_active_vehicle']); ?></h5>
                                                <p class="text-muted mb-0">Top Vehicle</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-building h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="active-zone"><?php echo e(Str::limit($geofenceAnalytics['summary']['most_active_zone'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Top Zone</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trending-up h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="peak-hour"><?php echo e($geofenceAnalytics['summary']['peak_hour']); ?></h5>
                                                <p class="text-muted mb-0">Peak Hour</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📈 Daily Zone Entries (Stick Graph)</h6>
                                                <div id="dailyEntriesStickChart" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🏢 Zone Activity Heatmap</h6>
                                                <div id="zoneActivityChart" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🚗 Vehicle Entry Frequency</h6>
                                                <div id="vehicleActivityChart" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🕒 Peak Hours Analysis</h6>
                                                <div id="peakHoursChart" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- overstay -->
            <div class="panel" style="max-width:100%;" id="overstay">
                <div class="row">
                    <div class="das_warp">
                <h1>Overstay</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-danger bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-time-five h3 text-danger mb-2"></i>
                                                <h5 class="mb-1" data-card="total-overstays"><?php echo e($overstayAnalytics['summary']['total_overstays']); ?></h5>
                                                <p class="text-muted mb-0">Total Overstays</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-timer h3 text-warning mb-2"></i>
                                                <h5 class="mb-1" data-card="total-duration"><?php echo e($overstayAnalytics['summary']['total_duration']); ?></h5>
                                                <p class="text-muted mb-0">Total Duration</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-stopwatch h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="avg-duration"><?php echo e($overstayAnalytics['summary']['avg_duration']); ?>m</h5>
                                                <p class="text-muted mb-0">Avg Duration</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-car h3 text-primary mb-2"></i>
                                                <h5 class="mb-1" data-card="problem-vehicle"><?php echo e($overstayAnalytics['summary']['most_problematic_vehicle']); ?></h5>
                                                <p class="text-muted mb-0">Problem Vehicle</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="problem-driver"><?php echo e(Str::limit($overstayAnalytics['summary']['most_problematic_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Problem Driver</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-map-pin h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="peak-hour"><?php echo e($overstayAnalytics['summary']['peak_overstay_hour']); ?></h5>
                                                <p class="text-muted mb-0">Peak Hour</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-8">
                                        <div id="dailyOverstaysStickChart"></div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div id="vehicleOverstayChart"></div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-6">
                                        <div id="driverOverstayChart"></div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div id="locationOverstayChart"></div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-6">
                                        <div id="durationTrendChart"></div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div id="peakHoursChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- attendance -->
            <div class="panel" style="max-width:100%;" id="attendance">
                <div class="row">
                    <div class="das_warp">
                <h1>Monthly Attendance</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <!-- Summary Cards -->
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user h3 text-primary mb-2"></i>
                                                <h5 class="mb-1" data-card="total-drivers"><?php echo e($attendanceAnalytics['summary']['total_drivers']); ?></h5>
                                                <p class="text-muted mb-0">Total Drivers</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-calendar-check h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="total-present"><?php echo e($attendanceAnalytics['summary']['total_present_days']); ?></h5>
                                                <p class="text-muted mb-0">Present Days</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-danger bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-calendar-x h3 text-danger mb-2"></i>
                                                <h5 class="mb-1" data-card="total-absent"><?php echo e($attendanceAnalytics['summary']['total_absent_days']); ?></h5>
                                                <p class="text-muted mb-0">Absent Days</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trending-up h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="avg-attendance"><?php echo e($attendanceAnalytics['summary']['avg_attendance_rate']); ?>%</h5>
                                                <p class="text-muted mb-0">Avg Attendance</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-star h3 text-warning mb-2"></i>
                                                <h5 class="mb-1" data-card="best-driver"><?php echo e(Str::limit($attendanceAnalytics['summary']['best_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Best Driver</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trending-down h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="worst-driver"><?php echo e(Str::limit($attendanceAnalytics['summary']['worst_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Needs Improvement</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Charts Row -->
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📈 Driver Attendance Overview (Stick Chart)</h6>
                                                <div id="attendanceStickChart" style="min-height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🎯 Attendance Rate Distribution</h6>
                                                <div id="attendanceRateChart" style="min-height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📅 Monthly Attendance Trend</h6>
                                                <div id="monthlyTrendChart" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">⚖️ Present vs Absent Days</h6>
                                                <div id="presentAbsentChart" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--loginlogout  -->
            <div class="panel" style="max-width:100%;" id="loginlogout">
                <div class="row">
                    <div class="das_warp">
                <h1>Login/Logout</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <!-- Summary Cards -->
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-log-in h3 text-primary mb-2"></i>
                                                <h5 class="mb-1" data-card="total-logins"><?php echo e($loginLogoutAnalytics['summary']['total_logins']); ?></h5>
                                                <p class="text-muted mb-0">Total Logins</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-log-out h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="total-logouts"><?php echo e($loginLogoutAnalytics['summary']['total_logouts']); ?></h5>
                                                <p class="text-muted mb-0">Total Logouts</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-time h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="avg-session"><?php echo e($loginLogoutAnalytics['summary']['avg_session_duration']); ?></h5>
                                                <p class="text-muted mb-0">Avg Session</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user h3 text-warning mb-2"></i>
                                                <h5 class="mb-1" data-card="active-driver"><?php echo e(Str::limit($loginLogoutAnalytics['summary']['most_active_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Most Active</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trending-up h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="peak-hour"><?php echo e($loginLogoutAnalytics['summary']['peak_login_hour']); ?></h5>
                                                <p class="text-muted mb-0">Peak Hour</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-mobile h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="used-device"><?php echo e(Str::limit($loginLogoutAnalytics['summary']['most_used_device'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Top Device</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Charts Row -->
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📊 Daily Login/Logout Activity Timeline</h6>
                                                <div id="loginActivityTimeline" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🔥 Session Duration Heatmap</h6>
                                                <div id="sessionHeatmap" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🎯 Driver Activity Radar</h6>
                                                <div id="driverActivityRadar" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🕒 24-Hour Login Distribution</h6>
                                                <div id="loginTimeDistribution" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📱 Device Usage Breakdown</h6>
                                                <div id="deviceUsagePie" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📈 Active Sessions Trend</h6>
                                                <div id="activeSessionsTrend" style="min-height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- logintime -->
            <div class="panel" style="max-width:100%;" id="logintime">
                <div class="row">
                    <div class="das_warp">
                <h1>Login Time</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-log-in h3 text-primary mb-2"></i>
                                                <h5 class="mb-1" data-card="total-logins"><?php echo e($loginTimeAnalytics['summary']['total_logins']); ?></h5>
                                                <p class="text-muted mb-0">Total Logins</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="unique-drivers"><?php echo e($loginTimeAnalytics['summary']['unique_drivers']); ?></h5>
                                                <p class="text-muted mb-0">Unique Drivers</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-trending-up h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="peak-hour"><?php echo e($loginTimeAnalytics['summary']['peak_login_hour']); ?></h5>
                                                <p class="text-muted mb-0">Peak Hour</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-sun h3 text-warning mb-2"></i>
                                                <h5 class="mb-1" data-card="earliest-driver"><?php echo e(Str::limit($loginTimeAnalytics['summary']['earliest_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Early Bird</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-moon h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="latest-driver"><?php echo e(Str::limit($loginTimeAnalytics['summary']['latest_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Night Owl</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-calendar-star h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="busy-day"><?php echo e($loginTimeAnalytics['summary']['busiest_day']); ?></h5>
                                                <p class="text-muted mb-0">Busiest Day</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Charts Row -->
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📊 Login Time Distribution (Candlestick Chart)</h6>
                                                <div id="loginTimeCandlestick" style="min-height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🕒 24-Hour Login Pattern</h6>
                                                <div id="hourlyLoginDistribution" style="min-height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📈 Daily Login Activity</h6>
                                                <div id="dailyLoginTrend" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🌅 Early Birds & Night Owls</h6>
                                                <div id="earlyNightComparison" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🎯 Login Consistency Score</h6>
                                                <div id="loginConsistency" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">⏱️ Average Login Time Trend</h6>
                                                <div id="avgLoginTimeTrend" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- sos -->
            <div class="panel" style="max-width:100%;" id="sos">
                <div class="row">
                    <div class="das_warp">
                <h1>Emergency SOS</h1>
            </div>
                    <div class="col-12">
                        <div class="card-1">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-danger bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-first-aid h3 text-danger mb-2"></i>
                                                <h5 class="mb-1" data-card="total-sos"><?php echo e($sosAnalytics['summary']['total_sos_alerts']); ?></h5>
                                                <p class="text-muted mb-0">Total SOS Alerts</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-warning bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user h3 text-warning mb-2"></i>
                                                <h5 class="mb-1" data-card="unique-drivers"><?php echo e($sosAnalytics['summary']['unique_drivers_affected']); ?></h5>
                                                <p class="text-muted mb-0">Drivers Affected</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-timer h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="response-rate"><?php echo e($sosAnalytics['summary']['response_rate']); ?>%</h5>
                                                <p class="text-muted mb-0">Response Rate</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-primary bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-time h3 text-primary mb-2"></i>
                                                <h5 class="mb-1" data-card="peak-hour"><?php echo e($sosAnalytics['summary']['peak_sos_hour']); ?></h5>
                                                <p class="text-muted mb-0">Peak SOS Hour</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-success bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-user-voice h3 text-success mb-2"></i>
                                                <h5 class="mb-1" data-card="frequent-driver"><?php echo e(Str::limit($sosAnalytics['summary']['most_frequent_driver'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Frequent Reporter</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-4 col-6">
                                        <div class="card bg-info bg-opacity-10 border-0">
                                            <div class="card-body text-center">
                                                <i class="bx bx-map h3 text-info mb-2"></i>
                                                <h5 class="mb-1" data-card="common-location"><?php echo e(Str::limit($sosAnalytics['summary']['most_common_location'], 12)); ?></h5>
                                                <p class="text-muted mb-0">Hotspot Location</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Main Charts Row -->
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🚨 SOS Alert Timeline (Bubble Chart)</h6>
                                                <div id="sosBubbleTimeline" style="min-height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">⚠️ Alert Severity Distribution</h6>
                                                <div id="severityDonut" style="min-height: 400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">🔥 24-Hour SOS Heatmap</h6>
                                                <div id="hourlyHeatmap" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none mb-4">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📊 Driver Alert Frequency (Treemap)</h6>
                                                <div id="driverTreemap" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">📍 SOS Location Clusters</h6>
                                                <div id="locationRadar" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">
                                        <div class="card border-0 shadow-none">
                                            <div class="card-body">
                                                <h6 class="card-title mb-4">⏱️ Response Time Trend</h6>
                                                <div id="responseTimeTrend" style="min-height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 


<script>


    // Tab Switching Functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Get all tab elements
        const tabs = document.querySelectorAll('.tab');
        const reportsList = document.getElementById('reportsList');
        const safetyList = document.getElementById('safetyList');

        // Add click event listeners to all tabs
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabType = this.getAttribute('data-tab');
                
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show/hide the appropriate list
                if (tabType === 'reports') {
                    reportsList.style.display = 'block';
                    safetyList.style.display = 'none';
                } else if (tabType === 'safety') {
                    reportsList.style.display = 'none';
                    safetyList.style.display = 'block';
                }
            });
        });

        // Add click event listeners to menu cards (optional - for navigation)
        const menuCards = document.querySelectorAll('.menu-card');
        menuCards.forEach(card => {
            card.addEventListener('click', function() {
                const reportType = this.getAttribute('data-report');
                // You can add navigation logic here based on reportType
                console.log('Clicked on:', reportType);
                // Example: window.location.href = `/reports/${reportType}`;
            });
        });
    });
    // Open Every Tabs
    document.addEventListener("DOMContentLoaded", function() {
        const menuCards = document.querySelectorAll(".menu-card");
        const panels = document.querySelectorAll(".panel");

        function hideAllPanels() {
            panels.forEach(panel => panel.style.display = "none");
        }

        menuCards.forEach(card => {
            card.addEventListener("click", function() {
                const report = this.getAttribute("data-report");
                const targetPanel = document.getElementById(report);

                if (targetPanel) {
                    hideAllPanels();
                    targetPanel.style.display = "block";
                }

                menuCards.forEach(c => c.classList.remove("active"));
                this.classList.add("active");
            });
        });

        hideAllPanels();
        const defaultPanel = document.getElementById("trip");
        if (defaultPanel) defaultPanel.style.display = "block";
        const defaultCard = document.querySelector('.menu-card[data-report="trip"]');
        if (defaultCard) defaultCard.classList.add("active");
    });


    // Trip Summary Chart (ApexCharts)
    let trip_summary = <?php echo json_encode($trip_summary, 15, 512) ?>;
    const dates = trip_summary.dates;
    const trips = trip_summary.totalTrips;
    const distance = trip_summary.totalDistance;
    const amount = trip_summary.totalAmount;

    const options = {
        chart: {
            height: 380,
            type: 'line',
            toolbar: {
                show: false
            }
        },
        series: [{
                name: 'Trips',
                type: 'bar',
                data: trips
            },
            {
                name: 'Distance (km)',
                type: 'line',
                data: distance
            },
            {
                name: 'Revenue (₹)',
                type: 'line',
                data: amount
            }
        ],
        colors: ['#4e73df', '#1cc88a', '#f6c23e'],
        stroke: {
            width: [0, 3, 3],
            curve: 'smooth'
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: dates,
            labels: {
                rotate: -45
            }
        },
        grid: {
            borderColor: '#eee',
            strokeDashArray: 4
        },
        legend: {
            position: 'top'
        },
        tooltip: {
            shared: true,
            intersect: false
        }
    };

    new ApexCharts(document.querySelector("#tripSummaryChart"), options).render();


    // Route History Chart Js Code
    document.addEventListener('DOMContentLoaded', function() {
        // Main Line Chart
        var routeHistoryOptions = {
            series: [{
                name: 'Total Distance (km)',
                type: 'line',
                data: <?php echo json_encode($routeHistory['total_distance'], 15, 512) ?>
            }, {
                name: 'Total Stops',
                type: 'column',
                data: <?php echo json_encode($routeHistory['total_stops'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'line',
                stacked: false,
                toolbar: {
                    show: true
                },
                zoom: {
                    enabled: true
                }
            },
            colors: ['#5D87FF', '#49BEFF'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [3, 1],
                curve: 'smooth'
            },
            fill: {
                opacity: [0.85, 0.25]
            },
            markers: {
                size: 0
            },
            xaxis: {
                categories: <?php echo json_encode($routeHistory['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        colors: '#6c757d',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: [{
                title: {
                    text: 'Distance (km)',
                    style: {
                        color: '#5D87FF'
                    }
                },
                labels: {
                    style: {
                        colors: '#5D87FF'
                    }
                }
            }, {
                opposite: true,
                title: {
                    text: 'Stops',
                    style: {
                        color: '#49BEFF'
                    }
                },
                labels: {
                    style: {
                        colors: '#49BEFF'
                    }
                }
            }],
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(1);
                        }
                        return y;
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left'
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        var routeHistoryChart = new ApexCharts(document.querySelector("#routeHistoryChart"), routeHistoryOptions);
        routeHistoryChart.render();

        // Efficiency Gauge Chart
        var avgEfficiency = <?php echo e($routeHistory['summary']['avg_efficiency']); ?>;
        var efficiencyGaugeOptions = {
            series: [avgEfficiency * 10], // Scale for better visualization
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                            color: undefined,
                            offsetY: 120
                        },
                        value: {
                            offsetY: 76,
                            fontSize: '22px',
                            color: undefined,
                            formatter: function (val) {
                                return (val / 10).toFixed(2) + ' stops/km';
                            }
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    shadeIntensity: 0.15,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 50, 65, 91]
                },
            },
            stroke: {
                dashArray: 4
            },
            labels: ['Route Efficiency'],
            colors: ['#FFAE1F']
        };

        var efficiencyGauge = new ApexCharts(document.querySelector("#efficiencyGauge"), efficiencyGaugeOptions);
        efficiencyGauge.render();
    });


    // Run
    document.addEventListener('DOMContentLoaded', function() {
        // Efficiency Trend Chart
        var efficiencyTrendOptions = {
            series: [{
                name: 'Efficiency %',
                data: <?php echo json_encode($runIdleAnalytics['efficiency_ratio'], 15, 512) ?>
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#5D87FF'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: <?php echo json_encode($runIdleAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        colors: '#6c757d',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Efficiency %',
                    style: {
                        color: '#5D87FF'
                    }
                },
                min: 0,
                max: 100,
                labels: {
                    formatter: function(val) {
                        return val + '%';
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + '%';
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        var efficiencyTrendChart = new ApexCharts(document.querySelector("#efficiencyTrendChart"), efficiencyTrendOptions);
        efficiencyTrendChart.render();

        // Time Distribution Chart
        var timeDistributionOptions = {
            series: [{
                name: 'Run Time',
                data: <?php echo json_encode($runIdleAnalytics['run_time_minutes'], 15, 512) ?>
            }, {
                name: 'Idle Time',
                data: <?php echo json_encode($runIdleAnalytics['idle_time_minutes'], 15, 512) ?>
            }],
            chart: {
                type: 'bar',
                height: 300,
                stacked: true,
                toolbar: {
                    show: true
                }
            },
            colors: ['#00E396', '#FEB019'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadius: 5
                },
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: <?php echo json_encode($runIdleAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Minutes'
                }
            },
            legend: {
                position: 'top'
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        var timeDistributionChart = new ApexCharts(document.querySelector("#timeDistributionChart"), timeDistributionOptions);
        timeDistributionChart.render();

        // Stops Analysis Chart
        var stopsAnalysisOptions = {
            series: [{
                name: 'Total Stops',
                data: <?php echo json_encode($runIdleAnalytics['total_stops'], 15, 512) ?>
            }],
            chart: {
                height: 300,
                type: 'area',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#FF4560'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($runIdleAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Stops'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            },
            markers: {
                size: 4
            }
        };

        var stopsAnalysisChart = new ApexCharts(document.querySelector("#stopsAnalysisChart"), stopsAnalysisOptions);
        stopsAnalysisChart.render();

        // Performance Gauge
        var performanceGaugeOptions = {
            series: [<?php echo e($runIdleAnalytics['summary']['overall_efficiency']); ?>],
            chart: {
                height: 300,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                            color: undefined,
                            offsetY: 120
                        },
                        value: {
                            offsetY: 76,
                            fontSize: '22px',
                            color: undefined,
                            formatter: function (val) {
                                return val + "%";
                            }
                        }
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    shadeIntensity: 0.15,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 50, 65, 91]
                },
            },
            stroke: {
                dashArray: 4
            },
            labels: ['Overall Efficiency'],
            colors: ['#5D87FF']
        };

        var performanceGauge = new ApexCharts(document.querySelector("#performanceGauge"), performanceGaugeOptions);
        performanceGauge.render();
    });


    document.addEventListener('DOMContentLoaded', function() {
        
        var distanceTrendOptions = {
            series: [{
                name: 'Distance (km)',
                data: <?php echo json_encode($distanceAnalytics['daily_distance'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($distanceAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Distance (km)'
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' km';
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        distanceTrendChart = new ApexCharts(document.querySelector("#distanceTrendChart"), distanceTrendOptions);
        distanceTrendChart.render();

        // Vehicle Distance Chart (Donut Chart)
        var vehicleDistanceOptions = {
            series: <?php echo json_encode($distanceAnalytics['vehicle_distance']['data'], 15, 512) ?>,
            chart: {
                type: 'donut',
                height: 350
            },
            labels: <?php echo json_encode($distanceAnalytics['vehicle_distance']['labels'], 15, 512) ?>,
            colors: ['#5D87FF', '#49BEFF', '#FFAE1F', '#FF5A5A', '#13D8AA', '#2E93fA', '#F86624', '#8E54E9', '#FF4560', '#00E396'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Distance',
                                formatter: function(w) {
                                    return <?php echo json_encode($distanceAnalytics['summary']['total_distance'], 15, 512) ?> + ' km';
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex].toFixed(1) + ' km';
                }
            }
        };

        vehicleDistanceChart = new ApexCharts(document.querySelector("#vehicleDistanceChart"), vehicleDistanceOptions);
        vehicleDistanceChart.render();

        // Driver Distance Chart (Horizontal Bar Chart)
        var driverDistanceOptions = {
            series: [{
                name: 'Distance (km)',
                data: <?php echo json_encode($distanceAnalytics['driver_distance']['data'], 15, 512) ?>
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    columnWidth: '60%',
                }
            },
            colors: ['#49BEFF'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + ' km';
                }
            },
            xaxis: {
                categories: <?php echo json_encode($distanceAnalytics['driver_distance']['labels'], 15, 512) ?>,
                title: {
                    text: 'Distance (km)'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        driverDistanceChart = new ApexCharts(document.querySelector("#driverDistanceChart"), driverDistanceOptions);
        driverDistanceChart.render();

        // Speed Analysis Chart (Line + Column Combo)
        var speedAnalysisOptions = {
            series: [{
                name: 'Avg Speed',
                type: 'line',
                data: <?php echo json_encode($distanceAnalytics['avg_speed'], 15, 512) ?>
            }, {
                name: 'Trips',
                type: 'column',
                data: <?php echo json_encode($distanceAnalytics['trip_count'], 15, 512) ?>
            }],
            chart: {
                height: 300,
                type: 'line',
                stacked: false,
                toolbar: {
                    show: true
                }
            },
            colors: ['#FF4560', '#00E396'],
            stroke: {
                width: [3, 0],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                    borderRadius: 5
                }
            },
            xaxis: {
                categories: <?php echo json_encode($distanceAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: [{
                title: {
                    text: 'Speed (km/h)'
                }
            }, {
                opposite: true,
                title: {
                    text: 'Trips'
                }
            }],
            tooltip: {
                shared: true,
                intersect: false
            },
            legend: {
                position: 'top'
            }
        };

        speedAnalysisChart = new ApexCharts(document.querySelector("#speedAnalysisChart"), speedAnalysisOptions);
        speedAnalysisChart.render();
    });

    // Geo-fence Charts ...................
    document.addEventListener('DOMContentLoaded', function() {
        // Daily Entries Stick Graph
        var dailyEntriesStickOptions = {
            series: [{
                name: 'Zone Entries',
                data: <?php echo json_encode($geofenceAnalytics['daily_entries'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#5D87FF'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 5,
                curve: 'straight'
            },
            markers: {
                size: 6,
                hover: {
                    size: 8
                }
            },
            xaxis: {
                categories: <?php echo json_encode($geofenceAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Entries'
                },
                min: 0
            },
            grid: {
                borderColor: '#f1f1f1',
                row: {
                    colors: ['#f8f9fa', 'transparent'],
                    opacity: 0.5
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' entries';
                    }
                }
            }
        };

        dailyEntriesStickChart = new ApexCharts(document.querySelector("#dailyEntriesStickChart"), dailyEntriesStickOptions);
        dailyEntriesStickChart.render();

        // Zone Activity Heatmap (Radial Bar)
        var zoneActivityOptions = {
            series: <?php echo json_encode($geofenceAnalytics['zone_activity']['entries'], 15, 512) ?>,
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '14px',
                        },
                        value: {
                            fontSize: '16px',
                            formatter: function(val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total Entries',
                            formatter: function(w) {
                                return <?php echo json_encode($geofenceAnalytics['summary']['total_entries'], 15, 512) ?>;
                            }
                        }
                    }
                }
            },
            labels: <?php echo json_encode($geofenceAnalytics['zone_activity']['labels'], 15, 512) ?>,
            colors: ['#5D87FF', '#49BEFF', '#FFAE1F', '#FF5A5A', '#13D8AA', '#8E54E9'],
            legend: {
                position: 'bottom'
            }
        };

        zoneActivityChart = new ApexCharts(document.querySelector("#zoneActivityChart"), zoneActivityOptions);
        zoneActivityChart.render();

        // Vehicle Activity Chart (Bar Chart)
        var vehicleActivityOptions = {
            series: [{
                name: 'Entries',
                data: <?php echo json_encode($geofenceAnalytics['vehicle_activity']['entries'], 15, 512) ?>
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: ['#49BEFF'],
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($geofenceAnalytics['vehicle_activity']['labels'], 15, 512) ?>,
                position: 'bottom',
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: true,
                    formatter: function(val) {
                        return val;
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        vehicleActivityChart = new ApexCharts(document.querySelector("#vehicleActivityChart"), vehicleActivityOptions);
        vehicleActivityChart.render();

        // Peak Hours Chart (Area Chart)
        var peakHoursData = <?php echo json_encode($geofenceAnalytics['peak_hours'], 15, 512) ?>;
        var peakHoursOptions = {
            series: [{
                name: 'Entries',
                data: peakHoursData.map(item => item.count)
            }],
            chart: {
                height: 300,
                type: 'area',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#FF4560'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: peakHoursData.map(item => item.hour),
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Entries'
                }
            },
            tooltip: {
                x: {
                    format: 'HH:mm'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        peakHoursChart = new ApexCharts(document.querySelector("#peakHoursChart"), peakHoursOptions);
        peakHoursChart.render();
    });

    // Overstay Charts .....................
    document.addEventListener('DOMContentLoaded', function() {
        // Daily Overstays Stick Graph
        var dailyOverstaysStickOptions = {
            series: [{
                name: 'Overstays',
                data: <?php echo json_encode($overstayAnalytics['daily_overstays'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#FF4560'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 5,
                curve: 'straight'
            },
            markers: {
                size: 6,
                colors: ['#FF4560'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 8
                }
            },
            xaxis: {
                categories: <?php echo json_encode($overstayAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Overstays'
                },
                min: 0
            },
            grid: {
                borderColor: '#f1f1f1',
                row: {
                    colors: ['#f8f9fa', 'transparent'],
                    opacity: 0.5
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' overstays';
                    }
                }
            }
        };

        dailyOverstaysStickChart = new ApexCharts(document.querySelector("#dailyOverstaysStickChart"), dailyOverstaysStickOptions);
        dailyOverstaysStickChart.render();

        // Vehicle Overstay Chart (Radial Bar)
        var vehicleOverstayOptions = {
            series: <?php echo json_encode($overstayAnalytics['vehicle_overstays']['count'], 15, 512) ?>,
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '14px',
                        },
                        value: {
                            fontSize: '16px',
                            formatter: function(val) {
                                return val;
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total Overstays',
                            formatter: function(w) {
                                return <?php echo json_encode($overstayAnalytics['summary']['total_overstays'], 15, 512) ?>;
                            }
                        }
                    }
                }
            },
            labels: <?php echo json_encode($overstayAnalytics['vehicle_overstays']['labels'], 15, 512) ?>,
            colors: ['#FF4560', '#FF6B6B', '#FF8E8E', '#FFB3B3', '#FFD8D8', '#FFEBEB'],
            legend: {
                position: 'bottom'
            }
        };

        vehicleOverstayChart = new ApexCharts(document.querySelector("#vehicleOverstayChart"), vehicleOverstayOptions);
        vehicleOverstayChart.render();

        // Driver Overstay Chart (Bar Chart)
        var driverOverstayOptions = {
            series: [{
                name: 'Overstays',
                data: <?php echo json_encode($overstayAnalytics['driver_overstays']['count'], 15, 512) ?>
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                }
            },
            colors: ['#FF6B6B'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($overstayAnalytics['driver_overstays']['labels'], 15, 512) ?>,
                position: 'bottom',
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                title: {
                    text: 'Overstay Count'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        driverOverstayChart = new ApexCharts(document.querySelector("#driverOverstayChart"), driverOverstayOptions);
        driverOverstayChart.render();

        // Location Overstay Chart (Horizontal Bar)
        var locationOverstayOptions = {
            series: [{
                name: 'Overstays',
                data: <?php echo json_encode($overstayAnalytics['location_overstays']['count'], 15, 512) ?>
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            colors: ['#FF8E53'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                }
            },
            xaxis: {
                categories: <?php echo json_encode($overstayAnalytics['location_overstays']['labels'], 15, 512) ?>,
                title: {
                    text: 'Overstay Count'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        locationOverstayChart = new ApexCharts(document.querySelector("#locationOverstayChart"), locationOverstayOptions);
        locationOverstayChart.render();

        // Duration Trend Chart (Area Chart)
        var durationTrendOptions = {
            series: [{
                name: 'Avg Duration (min)',
                data: <?php echo json_encode($overstayAnalytics['duration_trend'], 15, 512) ?>
            }],
            chart: {
                height: 300,
                type: 'area',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($overstayAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Minutes'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        durationTrendChart = new ApexCharts(document.querySelector("#durationTrendChart"), durationTrendOptions);
        durationTrendChart.render();

        // Peak Hours Chart (Line Chart)
        var peakHoursData = <?php echo json_encode($overstayAnalytics['peak_hours'], 15, 512) ?>;
        var peakHoursOptions = {
            series: [{
                name: 'Duration (min)',
                data: peakHoursData.map(item => item.duration)
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#5D87FF'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: peakHoursData.map(item => item.hour),
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Total Duration (min)'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        peakHoursChart = new ApexCharts(document.querySelector("#peakHoursChart"), peakHoursOptions);
        peakHoursChart.render();
    });

    window.addEventListener('resize', function() {
        if (dailyOverstaysStickChart) {
            setTimeout(() => {
                dailyOverstaysStickChart.updateOptions({
                    chart: {
                        width: '100%'
                    }
                });
            }, 300);
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        // Attendance Stick Chart
        var attendanceStickOptions = {
            series: [
                {
                    name: 'Present Days',
                    data: <?php echo json_encode($attendanceAnalytics['present_days'], 15, 512) ?>
                },
                {
                    name: 'Absent Days',
                    data: <?php echo json_encode($attendanceAnalytics['absent_days'], 15, 512) ?>
                }
            ],
            chart: {
                height: 400,
                type: 'line',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#00E396', '#FF4560'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 4,
                curve: 'straight'
            },
            markers: {
                size: 6,
                hover: {
                    size: 8
                }
            },
            xaxis: {
                categories: <?php echo json_encode($attendanceAnalytics['drivers'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Days'
                },
                min: 0
            },
            grid: {
                borderColor: '#f1f1f1',
                row: {
                    colors: ['#f8f9fa', 'transparent'],
                    opacity: 0.5
                }
            },
            legend: {
                position: 'top'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + ' days';
                    }
                }
            }
        };

        attendanceStickChart = new ApexCharts(document.querySelector("#attendanceStickChart"), attendanceStickOptions);
        attendanceStickChart.render();

        // Attendance Rate Chart (Radial Bar)
        var attendanceRateOptions = {
            series: <?php echo json_encode($attendanceAnalytics['attendance_rate'], 15, 512) ?>,
            chart: {
                height: 400,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '14px',
                        },
                        value: {
                            fontSize: '16px',
                            formatter: function(val) {
                                return val + '%';
                            }
                        },
                        total: {
                            show: true,
                            label: 'Avg Attendance',
                            formatter: function(w) {
                                return <?php echo json_encode($attendanceAnalytics['summary']['avg_attendance_rate'], 15, 512) ?> + '%';
                            }
                        }
                    }
                }
            },
            labels: <?php echo json_encode($attendanceAnalytics['drivers'], 15, 512) ?>,
            colors: ['#00E396', '#00B894', '#00A085', '#008B74', '#007563'],
            legend: {
                position: 'bottom',
                fontSize: '12px'
            }
        };

        attendanceRateChart = new ApexCharts(document.querySelector("#attendanceRateChart"), attendanceRateOptions);
        attendanceRateChart.render();

        // Monthly Trend Chart (Area Chart)
        var monthlyTrendOptions = {
            series: [{
                name: 'Avg Present Days',
                data: <?php echo json_encode($attendanceAnalytics['monthly_trend'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#5D87FF'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: {
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Average Present Days'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        monthlyTrendChart = new ApexCharts(document.querySelector("#monthlyTrendChart"), monthlyTrendOptions);
        monthlyTrendChart.render();

        // Present vs Absent Chart (Donut)
        var presentAbsentOptions = {
            series: [<?php echo json_encode($attendanceAnalytics['summary']['total_present_days'], 15, 512) ?>, <?php echo json_encode($attendanceAnalytics['summary']['total_absent_days'], 15, 512) ?>],
            chart: {
                type: 'donut',
                height: 350
            },
            labels: ['Present Days', 'Absent Days'],
            colors: ['#00E396', '#FF4560'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Days',
                                formatter: function(w) {
                                    return <?php echo json_encode($attendanceAnalytics['summary']['total_present_days'] + $attendanceAnalytics['summary']['total_absent_days'], 15, 512) ?>;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex] + ' days';
                }
            }
        };

        presentAbsentChart = new ApexCharts(document.querySelector("#presentAbsentChart"), presentAbsentOptions);
        presentAbsentChart.render();
    });
    

    // Login/Logout Charts ......................
    document.addEventListener('DOMContentLoaded', function() {
        
        var loginActivityOptions = {
            series: [
                {
                    name: 'Logins',
                    data: <?php echo json_encode($loginLogoutAnalytics['daily_logins'], 15, 512) ?>
                },
                {
                    name: 'Logouts',
                    data: <?php echo json_encode($loginLogoutAnalytics['daily_logouts'], 15, 512) ?>
                }
            ],
            chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#00E396', '#FF4560'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($loginLogoutAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Events'
                }
            },
            legend: {
                position: 'top'
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        loginActivityTimeline = new ApexCharts(document.querySelector("#loginActivityTimeline"), loginActivityOptions);
        loginActivityTimeline.render();

        // Session Duration Heatmap (Radial Bar)
        var sessionHeatmapOptions = {
            series: <?php echo json_encode($loginLogoutAnalytics['avg_session_duration'], 15, 512) ?>,
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '30%',
                    },
                    dataLabels: {
                        name: {
                            fontSize: '14px',
                        },
                        value: {
                            fontSize: '16px',
                            formatter: function(val) {
                                return val + 'm';
                            }
                        },
                        total: {
                            show: true,
                            label: 'Avg Session',
                            formatter: function(w) {
                                const durations = <?php echo json_encode($loginLogoutAnalytics['avg_session_duration'], 15, 512) ?>;
                                const avg = durations.length > 0 ? 
                                    Math.round(durations.reduce((a, b) => a + b) / durations.length) : 0;
                                return avg + 'm';
                            }
                        }
                    }
                }
            },
            labels: <?php echo json_encode($loginLogoutAnalytics['dates'], 15, 512) ?>,
            colors: ['#5D87FF', '#49BEFF', '#FFAE1F', '#FF5A5A', '#13D8AA'],
            legend: {
                position: 'bottom',
                fontSize: '10px'
            }
        };

        sessionHeatmap = new ApexCharts(document.querySelector("#sessionHeatmap"), sessionHeatmapOptions);
        sessionHeatmap.render();

        // Driver Activity Radar (Radar Chart)
        var driverActivityOptions = {
            series: [{
                name: 'Login Count',
                data: <?php echo json_encode($loginLogoutAnalytics['driver_activity']['logins'], 15, 512) ?>
            }],
            chart: {
                height: 300,
                type: 'radar',
                toolbar: {
                    show: false
                }
            },
            colors: ['#FF4560'],
            xaxis: {
                categories: <?php echo json_encode($loginLogoutAnalytics['driver_activity']['labels'], 15, 512) ?>
            },
            yaxis: {
                show: false
            },
            markers: {
                size: 4,
                hover: {
                    size: 6
                }
            },
            plotOptions: {
                radar: {
                    size: 140,
                    polygons: {
                        strokeColors: '#e9e9e9',
                        fill: {
                            colors: ['#f8f8f8', '#fff']
                        }
                    }
                }
            }
        };

        driverActivityRadar = new ApexCharts(document.querySelector("#driverActivityRadar"));
        driverActivityRadar.render();

        // Login Time Distribution (Bar Chart)
        var loginTimeData = <?php echo json_encode($loginLogoutAnalytics['login_times'], 15, 512) ?>;
        var loginTimeOptions = {
            series: [{
                name: 'Logins',
                data: loginTimeData.map(item => item.count)
            }],
            chart: {
                height: 300,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            colors: ['#5D87FF'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%',
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: loginTimeData.map(item => item.hour),
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Login Count'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        loginTimeDistribution = new ApexCharts(document.querySelector("#loginTimeDistribution"), loginTimeOptions);
        loginTimeDistribution.render();

        // Device Usage Pie (Pie Chart)
        var deviceUsageOptions = {
            series: <?php echo json_encode($loginLogoutAnalytics['device_usage']['data'], 15, 512) ?>,
            chart: {
                height: 300,
                type: 'pie',
            },
            labels: <?php echo json_encode($loginLogoutAnalytics['device_usage']['labels'], 15, 512) ?>,
            colors: ['#5D87FF', '#49BEFF', '#FFAE1F', '#FF5A5A', '#13D8AA', '#8E54E9'],
            legend: {
                position: 'bottom'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        deviceUsagePie = new ApexCharts(document.querySelector("#deviceUsagePie"), deviceUsageOptions);
        deviceUsagePie.render();

        // Active Sessions Trend (Line Chart)
        var activeSessionsOptions = {
            series: [{
                name: 'Active Sessions',
                data: <?php echo json_encode($loginLogoutAnalytics['active_sessions'], 15, 512) ?>
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: <?php echo json_encode($loginLogoutAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Active Sessions'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        activeSessionsTrend = new ApexCharts(document.querySelector("#activeSessionsTrend"), activeSessionsOptions);
        activeSessionsTrend.render();
    });


    // Login Time Charts
    document.addEventListener('DOMContentLoaded', function() {
        var candlestickOptions = {
            series: [{
                name: 'Login Time Distribution',
                data: <?php echo json_encode($loginTimeAnalytics['candlestick_data'], 15, 512) ?>
            }],
            chart: {
                height: 400,
                type: 'candlestick',
                toolbar: {
                    show: true
                }
            },
            title: {
                text: 'Driver Login Time Patterns',
                align: 'left'
            },
            xaxis: {
                type: 'category',
                labels: {
                    formatter: function(val) {
                        return val.length > 10 ? val.substring(0, 10) + '...' : val;
                    },
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                tooltip: {
                    enabled: true
                },
                labels: {
                    formatter: function(val) {
                        const hours = Math.floor(val);
                        const minutes = Math.round((val - hours) * 60);
                        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                    }
                },
                title: {
                    text: 'Login Time'
                }
            },
            plotOptions: {
                candlestick: {
                    colors: {
                        upward: '#00E396',
                        downward: '#FF4560'
                    },
                    wick: {
                        useFillColor: true
                    }
                }
            },
            tooltip: {
                custom: function({ seriesIndex, dataPointIndex, w }) {
                    const data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                    const times = data.y;
                    return `
                    <div class="apexcharts-tooltip-box">
                        <div><strong>${data.x}</strong></div>
                        <div>Earliest: ${this.formatTime(times[0])}</div>
                        <div>Q1: ${this.formatTime(times[1])}</div>
                        <div>Median: ${this.formatTime(times[2])}</div>
                        <div>Q3: ${this.formatTime(times[3])}</div>
                        <div>Latest: ${this.formatTime(times[4])}</div>
                    </div>
                    `;
                }.bind({
                    formatTime: function(decimalTime) {
                        const hours = Math.floor(decimalTime);
                        const minutes = Math.round((decimalTime - hours) * 60);
                        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                    }
                })
            }
        };

        loginTimeCandlestick = new ApexCharts(document.querySelector("#loginTimeCandlestick"), candlestickOptions);
        loginTimeCandlestick.render();

        // Hourly Login Distribution (Polar Area Chart)
        var hourlyData = <?php echo json_encode($loginTimeAnalytics['hourly_distribution'], 15, 512) ?>;
        var hourlyOptions = {
            series: hourlyData.map(item => item.count),
            chart: {
                height: 400,
                type: 'polarArea',
                toolbar: {
                    show: false
                }
            },
            labels: hourlyData.map(item => item.hour),
            colors: [
                '#5D87FF', '#49BEFF', '#FFAE1F', '#FF5A5A', '#13D8AA',
                '#8E54E9', '#00E396', '#FF4560', '#F86624', '#2E93fA',
                '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
                '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
                '#F8C471', '#82E0AA', '#F1948A', '#85C1E9', '#D7BDE2'
            ],
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.8
            },
            legend: {
                position: 'bottom'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            yaxis: {
                show: false
            }
        };

        hourlyLoginDistribution = new ApexCharts(document.querySelector("#hourlyLoginDistribution"), hourlyOptions);
        hourlyLoginDistribution.render();

        // Daily Login Trend (Line Chart)
        var dailyTrendOptions = {
            series: [{
                name: 'Daily Logins',
                data: <?php echo json_encode($loginTimeAnalytics['daily_logins'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: true
                },
                toolbar: {
                    show: true
                }
            },
            colors: ['#5D87FF'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: <?php echo json_encode($loginTimeAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Logins'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        dailyLoginTrend = new ApexCharts(document.querySelector("#dailyLoginTrend"), dailyTrendOptions);
        dailyLoginTrend.render();

        // Early Birds vs Night Owls (Bar Chart)
        var earlyNightOptions = {
            series: [
                {
                    name: 'Early Birds (Before 8 AM)',
                    data: <?php echo json_encode($loginTimeAnalytics['driver_early_birds']['consistency'], 15, 512) ?>
                },
                {
                    name: 'Night Owls (After 10 AM)',
                    data: <?php echo json_encode($loginTimeAnalytics['driver_night_owls']['consistency'], 15, 512) ?>
                }
            ],
            chart: {
                type: 'bar',
                height: 350,
                stacked: false,
                toolbar: {
                    show: false
                }
            },
            colors: ['#00E396', '#FF4560'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: <?php echo json_encode($loginTimeAnalytics['driver_early_birds']['labels'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Consistency Score (%)'
                },
                max: 100
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "% consistency"
                    }
                }
            }
        };

        earlyNightComparison = new ApexCharts(document.querySelector("#earlyNightComparison"), earlyNightOptions);
        earlyNightComparison.render();

        // Login Consistency (Radial Bar Chart)
        var consistencyOptions = {
            series: <?php echo json_encode($loginTimeAnalytics['login_consistency']['scores'], 15, 512) ?>,
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '14px',
                        },
                        value: {
                            fontSize: '16px',
                            formatter: function(val) {
                                return val + '%';
                            }
                        },
                        total: {
                            show: true,
                            label: 'Avg Consistency',
                            formatter: function(w) {
                                const scores = <?php echo json_encode($loginTimeAnalytics['login_consistency']['scores'], 15, 512) ?>;
                                const avg = scores.length > 0 ? 
                                    Math.round(scores.reduce((a, b) => a + b) / scores.length) : 0;
                                return avg + '%';
                            }
                        }
                    }
                }
            },
            labels: <?php echo json_encode($loginTimeAnalytics['login_consistency']['labels'], 15, 512) ?>,
            colors: ['#5D87FF', '#49BEFF', '#FFAE1F', '#FF5A5A', '#13D8AA', '#8E54E9', '#00E396', '#FF4560', '#F86624', '#2E93fA'],
            legend: {
                position: 'bottom',
                fontSize: '10px'
            }
        };

        loginConsistency = new ApexCharts(document.querySelector("#loginConsistency"), consistencyOptions);
        loginConsistency.render();

        // Average Login Time Trend (Area Chart)
        var avgTimeOptions = {
            series: [{
                name: 'Avg Login Time (Hours)',
                data: <?php echo json_encode($loginTimeAnalytics['login_trend'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#8E54E9'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($loginTimeAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Time (Hours)'
                },
                labels: {
                    formatter: function(val) {
                        const hours = Math.floor(val);
                        const minutes = Math.round((val - hours) * 60);
                        return `${hours}:${minutes.toString().padStart(2, '0')}`;
                    }
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        avgLoginTimeTrend = new ApexCharts(document.querySelector("#avgLoginTimeTrend"), avgTimeOptions);
        avgLoginTimeTrend.render();
    });


    // SOS Analytics Charts ........................
    function getHeatmapColor(intensity) {
        if (intensity >= 80) return '#FF0000';
        if (intensity >= 60) return '#FF4560';
        if (intensity >= 40) return '#FF6B6B';
        if (intensity >= 20) return '#FF8E8E';
        return '#FFB3B3';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var bubbleData = <?php echo json_encode($sosAnalytics['dates'], 15, 512) ?>.map((date, index) => {
            return {
                x: date,
                y: <?php echo json_encode($sosAnalytics['daily_sos'], 15, 512) ?>[index],
                z: <?php echo json_encode($sosAnalytics['daily_sos'], 15, 512) ?>[index] * 10 // Bubble size
            };
        });

        var bubbleOptions = {
            series: [{
                name: 'SOS Alerts',
                data: bubbleData
            }],
            chart: {
                height: 400,
                type: 'bubble',
                toolbar: {
                    show: true
                }
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                opacity: 0.8,
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: "vertical",
                    shadeIntensity: 0.5,
                    gradientToColors: ['#FF4560'],
                    inverseColors: false,
                    opacityFrom: 0.8,
                    opacityTo: 0.4,
                    stops: [0, 90, 100]
                }
            },
            colors: ['#FF4560'],
            title: {
                text: 'SOS Alert Intensity Over Time'
            },
            xaxis: {
                type: 'category',
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Alerts'
                },
                max: Math.max(...<?php echo json_encode($sosAnalytics['daily_sos'], 15, 512) ?>) * 1.1
            },
            tooltip: {
                custom: function({ seriesIndex, dataPointIndex, w }) {
                    const data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                    return `
                    <div class="apexcharts-tooltip-box bg-danger text-white">
                        <div><strong>${data.x}</strong></div>
                        <div>Alerts: ${data.y}</div>
                        <div>Intensity: ${data.z/10}/10</div>
                    </div>
                    `;
                }
            }
        };

        sosBubbleTimeline = new ApexCharts(document.querySelector("#sosBubbleTimeline"), bubbleOptions);
        sosBubbleTimeline.render();

        // Severity Donut Chart
        var severityOptions = {
            series: <?php echo json_encode($sosAnalytics['severity_analysis']['counts'], 15, 512) ?>,
            chart: {
                type: 'donut',
                height: 400
            },
            labels: <?php echo json_encode($sosAnalytics['severity_analysis']['labels'], 15, 512) ?>,
            colors: <?php echo json_encode($sosAnalytics['severity_analysis']['colors'], 15, 512) ?>,
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Alerts',
                                formatter: function(w) {
                                    return <?php echo json_encode($sosAnalytics['summary']['total_sos_alerts'], 15, 512) ?>;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex];
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        severityDonut = new ApexCharts(document.querySelector("#severityDonut"), severityOptions);
        severityDonut.render();

        // 24-Hour Heatmap
        var heatmapData = <?php echo json_encode($sosAnalytics['hourly_distribution'], 15, 512) ?>;
        var heatmapOptions = {
            series: [{
                name: 'SOS Alerts',
                data: heatmapData.map(item => ({
                    x: item.hour,
                    y: item.count,
                    fillColor: getHeatmapColor(item.intensity)
                }))
            }],
            chart: {
                height: 350,
                type: 'heatmap',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: true
            },
            colors: ['#FF4560'],
            xaxis: {
                type: 'category',
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Alert Count'
                }
            },
            plotOptions: {
                heatmap: {
                    colorScale: {
                        ranges: [
                            { from: 0, to: 5, color: '#FFB3B3', name: 'Low' },
                            { from: 6, to: 15, color: '#FF6B6B', name: 'Medium' },
                            { from: 16, to: 25, color: '#FF4560', name: 'High' },
                            { from: 26, to: 100, color: '#FF0000', name: 'Critical' }
                        ]
                    }
                }
            }
        };

        hourlyHeatmap = new ApexCharts(document.querySelector("#hourlyHeatmap"), heatmapOptions);
        hourlyHeatmap.render();

        // Driver Treemap Chart
        var treemapData = <?php echo json_encode($sosAnalytics['driver_sos_frequency']['labels'], 15, 512) ?>.map((label, index) => {
            return {
                x: label,
                y: <?php echo json_encode($sosAnalytics['driver_sos_frequency']['counts'], 15, 512) ?>[index]
            };
        });

        var treemapOptions = {
            series: [{
                data: treemapData
            }],
            legend: {
                show: false
            },
            chart: {
                height: 350,
                type: 'treemap',
                toolbar: {
                    show: false
                }
            },
            colors: [
                '#FF4560', '#FF6B6B', '#FF8E8E', '#FFB3B3', '#FFD8D8',
                '#FF0000', '#FF3333', '#FF6666', '#FF9999', '#FFCCCC'
            ],
            plotOptions: {
                treemap: {
                    enableShades: true,
                    shadeIntensity: 0.5,
                    reverseNegativeShade: true,
                    colorScale: {
                        ranges: [
                            { from: 1, to: 5, color: '#FFB3B3' },
                            { from: 6, to: 10, color: '#FF8E8E' },
                            { from: 11, to: 15, color: '#FF6B6B' },
                            { from: 16, to: 100, color: '#FF4560' }
                        ]
                    }
                }
            }
        };

        driverTreemap = new ApexCharts(document.querySelector("#driverTreemap"), treemapOptions);
        driverTreemap.render();

        // Location Radar Chart
        var radarOptions = {
            series: [{
                name: 'SOS Frequency',
                data: <?php echo json_encode($sosAnalytics['location_clusters']['counts'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'radar',
                toolbar: {
                    show: false
                }
            },
            xaxis: {
                categories: <?php echo json_encode($sosAnalytics['location_clusters']['labels'], 15, 512) ?>
            },
            yaxis: {
                show: false
            },
            colors: ['#FF4560'],
            plotOptions: {
                radar: {
                    size: 140,
                    polygons: {
                        strokeColors: '#e9e9e9',
                        fill: {
                            colors: ['#f8f8f8', '#fff']
                        }
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#fff'],
                strokeColor: '#FF4560',
                strokeWidth: 2
            }
        };

        locationRadar = new ApexCharts(document.querySelector("#locationRadar"), radarOptions);
        locationRadar.render();

        // Response Time Trend (Area Chart)
        var responseTimeOptions = {
            series: [{
                name: 'Avg Response Time (min)',
                data: <?php echo json_encode($sosAnalytics['response_time_trend'], 15, 512) ?>
            }],
            chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($sosAnalytics['dates'], 15, 512) ?>,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Response Time (minutes)'
                }
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };

        responseTimeTrend = new ApexCharts(document.querySelector("#responseTimeTrend"));
        responseTimeTrend.render();
    });

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\projects\vlocus\resources\views/dashboard.blade.php ENDPATH**/ ?>