<div class="sidebar">
    <!-- Start Logobar -->
    <div class="logobar">
        <a href="{{ route('admin.admins.index') }}" class="logo logo-large"><img src="{{ asset('assets/admin/images/logo.svg') }}" class="img-fluid" alt="logo"></a>
        <a href="{{ route('admin.admins.index') }}" class="logo logo-small"><img src="{{ asset('assets/admin/images/small_logo.svg') }}" class="img-fluid" alt="logo"></a>
    </div>
    <!-- End Logobar -->
    <!-- Start Navigationbar -->
    <div class="navigationbar">
        <ul class="vertical-menu">
            <li id="admins">
                <a href="{{ route('admin.admins.index') }}">
                    <i class="fa fa-users"></i><span>Admin Management</span>
                </a>
            </li>
            <li id="categories">
                <a href="{{ route('admin.categories.index') }}">
                    <i class="fa fa-list"></i><span>Category Management</span>
                </a>
            </li>
            <li id="products">
                <a href="{{ route('admin.products.index') }}">
                    <i class="fa fa-list"></i><span>Product Management</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- End Navigationbar -->
</div>
