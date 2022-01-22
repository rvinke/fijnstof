<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fijnstof Veenendaal</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="shortcut icon" href="/img/favicon.ico" />

    </head>
    <body class="antialiased bg-gray-900 dark:bg-gray-900">

        <div class="py-12 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="py-10 text-base text-2xl text-white font-semibold tracking-wide">Fijnstof in Veenendaal</h2>


                        @if($pm2 > config('fijnstof.pm2_bovengrens') || $pm10 > config('fijnstof.pm10_bovengrens'))
                        <p class="mt-2 leading-8 font-extrabold tracking-tight text-red-700 text-3xl md:text-4xl lg:text-5xl">
                            De fijnstof-concentratie is te hoog
                        </p>
                        @else
                        <p class="mt-2 leading-8 font-extrabold tracking-tight text-green-400 text-3xl md:text-4xl lg:text-5xl">
                            De fijnstof-concentratie is in orde
                        </p>
                        @endif

                    <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                        <br /><br />
                    </p>
                </div>

                <div class="mt-10">
                    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gray-600 text-white">
                                    <!-- Heroicon name: outline/globe-alt -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-100">Fijnstofmetingen</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-100">
                                De fijnstofmetingen worden door een aantal vrijwilligers in Veenendaal uitgevoerd met behulp van
                                het <a href="https://www.samenmetenaanluchtkwaliteit.nl/sensorcommunity" class="text-gray-400">Sensor.community fijnstofmeetapparaat</a>.
                                Deze website neemt de gemiddelde waarde van de laatste metingen en geeft op basis van de RIVM-grenzen aan of deze
                                waarden acceptabel zijn. De (gemiddelde) laatst gemeten waarden zijn:<br /><br />
                                Laatst gemeten waarde PM2.5: <b>{{ round($pm2, 2) }} &mu;g/m<sup>3</sup></b><br />
                                Laatst gemeten waarde PM10: <b>{{ round($pm10, 2) }} &mu;g/m<sup>3</sup></b>
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gray-600 text-white">
                                    <!-- Heroicon name: outline/trend-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-100">Trend</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-100">
                                Er wordt op dit moment een
                                @if($trend_up)
                                    <span class="text-red-600 font-semibold">toenemende</span> concentratie fijnstof waargenomen. Dat betekent dat de hoeveelheid fijnstof in de lucht aan het toenemen is op dit moment.
                                @else
                                    <span class="text-green-400">afnemende</span> concentratie fijnstof waargenomen. Dat betekent dat de hoeveelheid fijnstof in de lucht aan het afnemen is op dit moment.
                                @endif
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gray-600 text-white">
                                    <!-- Heroicon name: outline/user-group -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-100">Meedoen?</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-100">
                                Wil je ook meemeten? Dat kan! Bestel <a href="https://sensor.community/nl/sensors/" class="text-gray-400">een kit op de website van Sensor.community</a>, en hang deze op binnen Veenendaal. Je sensor
                                wordt dan automatisch opgenomen in de berekeningen op deze site. Als je een mail stuurt aan info@de-url-van-deze-site.nl dan krijg
                                je ook toegang tot de gebruikersgroep in Veenendaal.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gray-600 text-white">
                                    <!-- Heroicon name: outline/search -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-100">Aanvullende uitleg</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-100">
                                Fijnstof betreft kleine (stof)deeltjes in de lucht, in het geval van PM10 gaat het om deeltjes die kleiner zijn dan 10 micrometer,
                                en PM2.5 betreft deeltjes die kleiner zijn dan 2.5 micrometer. Bronnen van deze deeltjes zijn soms natuurlijk (zoals stof vanaf de Veluwe),
                                maar vaak ook van wegverkeer (remstof) of verbrandingsprocessen (zoals openhaarden en kachels). <br />
                                Langdurige blootstelling aan verhoogde concentraties fijnstof heeft een negatief effect op de gezondheid. Vooral mensen met COPD heeft veel
                                last van fijnstof. Het RIVM heeft normen vastgesteld op basis waarvan vastgesteld wordt of de concentratie te hoog is. Voor PM10 is deze grens 40 &mu;g en
                                voor PM2.5 25 &mu;g.
                            </dd>
                        </div>

                    </dl>
                </div>
            </div>
        </div>


        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div>
                    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-5 md:gap-x-8 md:gap-y-10">
                        <div class="relative">

                        </div>
                        <div class="relative absolute flex items-center justify-center">
                            <a href="https://www.influxdata.com/" target="_blank">
                            <img src="/img/influxdb.png" style="width: 140px" />
                            </a>
                        </div>
                        <div class="relative absolute flex items-center justify-center">
                            <a href="https://www.laravel.com" target="_blank">
                            <img src="/img/laravel.png" style="width: 100px" />
                            </a>
                        </div>
                        <div class="relative absolute flex items-center justify-center">
                            <a href="http://www.ronaldvinke.nl" target="_blank">
                            <img src="/img/ronaldvinke.png" style="width: 100px" />
                            </a>
                        </div>
                        <div class="relative">

                        </div>
                    </dl>
                </div>
            </dl>
        </div>
    </body>
</html>
