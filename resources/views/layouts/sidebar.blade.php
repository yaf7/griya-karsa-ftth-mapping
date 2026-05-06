<nav id="sidebar" class="d-flex flex-column px-2 fixed-top"
    style="top:56px; left:0; height:calc(100vh - 56px); width:160px; min-width:120px;
           background:#fff; border-right:1px solid #e5e7eb; font-size:0.93rem;">

    <ul class="nav nav-pills flex-column mb-auto">

        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ url('/traffic') }}" class="nav-link active" style="font-size:0.95rem; padding:6px 8px;">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        {{-- Mapping --}}
        <li>
            <a class="nav-link d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#mappingMenu" role="button"
               aria-expanded="false" aria-controls="mappingMenu"
               style="font-size:0.95rem; padding:6px 8px;">
                <span><i class="bi bi-list-ul me-2"></i> Mapping</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-4" id="mappingMenu">
                <ul class="nav flex-column">
                    <li>
                        <a href="{{ url('map/view') }}" class="nav-link"
                           style="font-size:0.75rem; padding:5px 8px;">
                           <i class="bi bi-geo-alt me-2"></i> Map
                        </a>
                    </li>
                     
                    <li>
                        <a href="{{ url('line/create') }}" class="nav-link"
                           style="font-size:0.75rem; padding:5px 8px;">
                           <i class="bi bi-plus-square me-2"></i> Tambah Jalur ODC/ODP
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/optical_distribution/create') }}" class="nav-link"
                           style="font-size:0.75rem; padding:5px 8px;">
                           <i class="bi bi-plus-square me-2"></i> Tambah Titik ODP/ODC/SERVER
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- PPPoE --}}
        <li>
            <a class="nav-link d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#pppoeMenu" role="button"
               aria-expanded="false" aria-controls="pppoeMenu"
               style="font-size:0.95rem; padding:6px 8px;">
                <span><i class="bi bi-person-lines-fill me-2"></i> PPPoE Client</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-4" id="pppoeMenu">
                <ul class="nav flex-column">
                    <li>
                        <a href="{{ url('client/create') }}" class="nav-link" style="font-size:0.75rem; padding:5px 8px;">
                            <i class="bi bi-bar-chart-line me-2"></i> Data PPPoE
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('client/create') }}" class="nav-link" style="font-size:0.75rem; padding:5px 8px;">
                            <i class="bi bi-plus-square me-2"></i> Tambah Data PPPoE
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Pengaturan --}}
        <li>
            <a href="#" class="nav-link" style="font-size:0.95rem; padding:6px 8px;">
                <i class="bi bi-gear me-2"></i> Pengaturan
            </a>
        </li>
    </ul>

    {{-- User Info --}}
    <div class="mt-auto pt-4">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none"
           style="font-size:0.92rem;">
            <img src="https://github.com/mdo.png" alt="User" width="28" height="28"
                 class="rounded-circle me-2" />
            <strong>{{ Auth::user()->name ?? 'User Name' }}</strong>
        </a>
    </div>
</nav>
