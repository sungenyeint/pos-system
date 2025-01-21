<div class="sidebar">
    <!-- Start Logobar -->
    <div class="logobar">
        <a href="{{ route('admin.admins.index') }}" class="logo logo-large"><img src="{{ asset('assets/admin/images/logo.png') }}" class="img-fluid" alt="logo"></a>
        <a href="{{ route('admin.admins.index') }}" class="logo logo-small"><img src="{{ asset('assets/admin/images/logo.png') }}" class="img-fluid" alt="logo"></a>
    </div>
    <!-- End Logobar -->
    <!-- Start Navigationbar -->
    <div class="navigationbar">
        <ul class="vertical-menu">
            <li id="home">
                <a href="{{ route('admin.home') }}">
                    <i class="fa fa-home"></i><span>Dashboard</span>
                </a>
            </li>
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
                    <i class="fa fa-product-hunt"></i><span>Product Management</span>
                </a>
            </li>
            <li id="purchases">
                <a href="{{ route('admin.purchases.index') }}">
                    <i class="fa fa-cart-arrow-down"></i><span>Purchase Management</span>
                </a>
            </li>
            <li id="sales">
                <a href="{{ route('admin.sales.index') }}">
                    <i class="fa fa-shopping-bag"></i><span>Sale Management</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- End Navigationbar -->
</div>
