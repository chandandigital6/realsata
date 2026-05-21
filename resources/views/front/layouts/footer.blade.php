<!-- Footer here -->
<section class="octoberresultchart">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>SATTA CHART</h2>
            </div>
        </div>
    </div>
</section>

<section class="tabel3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 nopadding">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>

                            @forelse($footerGames as $game)
                                <tr>
                                    <td class="forfirtcolor">
                                        <strong>{{ strtoupper($game->name) }}</strong>
                                    </td>

                                    @forelse($game->chartYears as $chartYear)
                                        <td>
                                            <strong>
                                                <a href="{{ route('game.year-record', [$game->slug, $chartYear->year]) }}"
                                                   style="color:black;">
                                                    {{ $chartYear->year }}
                                                </a>
                                            </strong>
                                        </td>
                                    @empty
                                        <td>
                                            <strong>
                                                <a href="{{ route('game.year-record', [$game->slug, now()->year]) }}"
                                                   style="color:black;">
                                                    {{ now()->year }}
                                                </a>
                                            </strong>
                                        </td>
                                    @endforelse
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">
                                        No chart found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="somelinks">
    <div class="container">
        <div class="footer_white">
            <div class="row">
                <div class="col-md-12 text-center">
                    <ul>
                        <li><a href="/privacy-policy">Privacy Policy</a></li>
                        <li><a href="/terms-conditions">Terms & Conditions</a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="somelinks2">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <strong>@ 2022-2026 Real Satta All Rights Reserved</strong>
            </div>
        </div>
    </div>
</section>

<div class="floating-wa-refresh" style="position:fixed; bottom:18px; right:6px; z-index:9; display:flex; flex-direction:column; align-items:flex-end; gap:10px;">
    <a href="https://api.whatsapp.com/send/?phone=919896916793&text&type=phone_number&app_absent=0" target="_blank" rel="noopener noreferrer" style="line-height:0;">
        <img src="/m/wapp.png" alt="WhatsApp" style="height:80px;width:80px;margin-right:10px;display:block;">
    </a>

    <button type="button"
            class="page-refresh-widget"
            onclick="location.reload()"
            title="Refresh page"
            aria-label="Refresh this page"
            style="margin-right:10px;width:56px;height:56px;border-radius:50%;border:2px solid #1565C0;background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.2);cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="#1565C0" d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.56 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
        </svg>
    </button>
</div>

<a style="text-decoration:none;
    padding:20px 2px 30px 2px;
    background-color:#D10B37;
    color:#ffff;
    border:2px solid #FFF;
    font-weight:700;
    font-size:14px;
    border-radius:10px;
    position:fixed;
    bottom:18px;
    left:3px;
    width:69px;
    text-align:center;
    z-index:999999999999;"
   href="https://wa.me/919896916793">
    <i class="fa fa-arrow-down blink"></i><br> PLAY Now
</a>

<section class="somelinks">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <ul>
                    <li>
                        <a>
                            <p>
                                <strong>
                                    !! DISCLAIMER:- realsatta.in is a non-commercial website. Viewing This Website Is Your Own Risk.
                                </strong>
                            </p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<style>
    .fa {
        display: inline-block;
        font: normal normal normal 14px / 1 FontAwesome;
        font-size: inherit;
    }

    .page-refresh-widget:hover {
        background: #E3F2FD !important;
        border-color: #0D47A1 !important;
    }

    .page-refresh-widget:active {
        transform: scale(0.96);
    }
</style>