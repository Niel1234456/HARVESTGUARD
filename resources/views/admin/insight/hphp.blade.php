<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
<div class="sidebar">
        <ul>
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.insight.index') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Insight</span>
                </a>
            </li>    
            <li>
                <a href="{{ route('admin.admin.farmers') }}">
                    <i class="fas fa-users"></i>
                    <span>Farmers</span>
                </a>
            </li>    
            <li>
                <a href="{{ route('admin.supplies.index') }}">
                    <i class="fas fa-boxes"></i>
                    <span>Supplies</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.equipment.index') }}">
                    <i class="fas fa-tools"></i>
                    <span>Equipments</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-file-alt"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-bullhorn"></i>
                    <span>Promote</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fas fa-question-circle"></i>
                    <span>Help</span>
                </a>
            </li>
            <li>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="logout-icon">
            <i class="fas fa-sign-out-alt"></i> <!-- Replace with the desired icon -->
        </a>
    </form>
        </ul>
    </div>