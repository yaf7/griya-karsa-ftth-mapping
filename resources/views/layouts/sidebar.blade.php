<nav id="sidebar" class="d-flex flex-column px-2 fixed-top glass"
    style="top:56px; left:0; height:calc(100vh - 56px); width:160px; min-width:120px;
           border-right:1px solid rgba(255,255,255,0.1)!important; font-size:0.93rem;">

    <ul class="nav nav-pills flex-column mb-auto mt-3">

        {{-- Dashboard --}}
        <li class="nav-item">
            <a href="{{ url('/traffic') }}" class="nav-link active" style="font-size:0.95rem; padding:8px 10px;">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        {{-- Mapping --}}
        <li class="mt-2">
            <a class="nav-link d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#mappingMenu" role="button"
               aria-expanded="false" aria-controls="mappingMenu"
               style="font-size:0.95rem; padding:8px 10px;">
                <span><i class="bi bi-geo-fill me-2"></i> Mapping</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="mappingMenu">
                <ul class="nav flex-column mt-1 border-start border-secondary ms-2 ps-2">
                    <li>
                        <a href="{{ url('map/view') }}" class="nav-link"
                           style="font-size:0.8rem; padding:6px 8px;">
                           <i class="bi bi-map me-2"></i> Peta Visual
                        </a>
                    </li>
                     
                    <li>
                        <a href="{{ route('line.index') }}" class="nav-link"
                           style="font-size:0.8rem; padding:6px 8px;">
                           <i class="bi bi-share me-2"></i> Data Jalur
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('optical_distribution.index') }}" class="nav-link"
                           style="font-size:0.8rem; padding:6px 8px;">
                           <i class="bi bi-router me-2"></i> Data Perangkat
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- PPPoE --}}
        <li class="mt-2">
            <a class="nav-link d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#pppoeMenu" role="button"
               aria-expanded="false" aria-controls="pppoeMenu"
               style="font-size:0.95rem; padding:8px 10px;">
                <span><i class="bi bi-people-fill me-2"></i> Client</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="pppoeMenu">
                <ul class="nav flex-column mt-1 border-start border-secondary ms-2 ps-2">
                    <li>
                        <a href="{{ route('client.index') }}" class="nav-link" style="font-size:0.8rem; padding:6px 8px;">
                            <i class="bi bi-person-lines-fill me-2"></i> Data PPPoE
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('client.create') }}" class="nav-link" style="font-size:0.8rem; padding:6px 8px;">
                            <i class="bi bi-person-plus me-2"></i> Tambah Client
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Pengaturan --}}
        <li class="mt-2">
            <a class="nav-link d-flex justify-content-between align-items-center"
               data-bs-toggle="collapse" href="#pengaturanMenu" role="button"
               aria-expanded="false" aria-controls="pengaturanMenu"
               style="font-size:0.95rem; padding:8px 10px;">
                <span><i class="bi bi-gear-fill me-2"></i> Pengaturan</span>
                <i class="bi bi-chevron-down small"></i>
            </a>
            <div class="collapse ps-3" id="pengaturanMenu">
                <ul class="nav flex-column mt-1 border-start border-secondary ms-2 ps-2">
                    <li>
                        <a href="{{ route('kategori.index') }}" class="nav-link" style="font-size:0.8rem; padding:6px 8px;">
                            <i class="bi bi-tags me-2"></i> Kategori
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('paket.index') }}" class="nav-link" style="font-size:0.8rem; padding:6px 8px;">
                            <i class="bi bi-box me-2"></i> Paket Internet
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>

    {{-- User Info --}}
    <div class="mt-auto pt-4 pb-3 border-top border-secondary mx-2">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none"
           style="font-size:0.92rem;">
            <i class="bi bi-person-circle fs-4 text-secondary me-2"></i>
            <strong>{{ Auth::user()->name ?? 'Deyafa Arsetya' }}</strong>
        </a>
    </div>
</nav>
