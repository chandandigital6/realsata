<section class="topboxnew">

    <p style="text-align:center;">
        <img src="/Logo3.png"
             style="width:130px;"
             alt="Real Satta King">
    </p>

    <div class="container-fluid">
        <div class="col-md-12 nopadding">

            <div class="newnav">
                <ul>

                    <li>
                        <a href="/" class="active">
                            HOME
                        </a>
                    </li>

                    <li>
                        <a href="/chart">
                            CHART
                        </a>
                    </li>

                    <li>
                        <a href="/contact-us">
                            CONTACT
                        </a>
                    </li>

                    @if (Route::has('login'))

                        @auth

                            <li>
                                <a href="{{ route('dashboard') }}">
                                    MY ACCOUNT
                                </a>
                            </li>

                        @else

                            <li>
                                <a href="{{ route('login') }}">
                                    LOGIN
                                </a>
                            </li>

                            @if (Route::has('register'))
                                <li>
                                    <a href="{{ route('register') }}">
                                        REGISTER
                                    </a>
                                </li>
                            @endif

                        @endauth

                    @endif

                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="text_slide">
                <marquee style="color:#fff;"
                         onmouseover="this.stop();"
                         onmouseout="this.start();">

                    <b>
                        satta king 786, gali satta, disawar satta,
                        satta king gali disawar, online satta king,
                        Real Satta, Satta King live result,
                        Real Satta chart, Real Satta online result,
                        Gali result, Desawar result,
                        Faridabad result, Gaziyabad result,
                        Satta matka king.
                    </b>

                </marquee>
            </div>

        </div>
    </div>
</section>

<section class="sattalogo">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                <span style="font-size:36px;">
                    <a href="/" class="blink">
                        REAL SATTA
                    </a>
                </span>

            </div>
        </div>
    </div>
</section>