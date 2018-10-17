<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/dashboard*') ? 'active' : ''}}" href="{{route('admin.dashboard')}}">
                    <span data-feather="home"></span>
                    Dashboard <span class="sr-only">(current)</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/product*') ? 'active' : ''}}" href="{{route('admin.product.index')}}">
                    <span data-feather="shopping-cart"></span>
                    Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/supplier*') ? 'active' : ''}}" href="{{route('admin.supplier.index')}}">
                    <span data-feather="users"></span>
                    Suppliers
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/lead*') ? 'active' : ''}}" href="{{route('admin.lead.index')}}">
                    <span data-feather="file"></span>
                    Leads
                </a>
            </li>

            <!--<li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="bar-chart-2"></span>
                    Reports
                </a>
            </li>-->
            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/user*') ? 'active' : ''}}" href="{{route('admin.user.index')}}">
                    <span data-feather="layers"></span>
                    Users
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{Request::is('admin/activity*') ? 'active' : ''}}" href="{{route('admin.user.index')}}">
                    <span data-feather="activity"></span>
                    Activity
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Saved reports</span>
            <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
            </a>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Current month
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Last quarter
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Social engagement
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <span data-feather="file-text"></span>
                    Year-end sale
                </a>
            </li>
        </ul>
    </div>
</nav>
