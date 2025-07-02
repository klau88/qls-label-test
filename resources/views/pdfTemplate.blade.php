<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pakbon {{ $order['id'] }}</title>

    <script>
        (function() {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    {{-- Inline style to set the HTML background color based on our theme in app.css --}}
    <style style type="text/css" media="all">
        html {
            background-color: oklch(1 0 0);
        }

        html.dark {
            background-color: oklch(0.145 0 0);
        }
    </style>

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/js/app.ts'])
</head>
<body class="font-sans antialiased">
<div id="app">
    <div class="flex flex-col justify-between">
        <div class="p-8">
            <div class="flex flex-row items-center justify-between">
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold">Pakbon</h1>
                    <h3 class="text-sm">Bestelnummer: {{ $order['number'] }}</h3>
                </div>
                <div class="text-xs">
                    <x-address-view
                        title="Afzender"
                        :name="$order['billing_name']"
                        :companyname="$order['billing_companyname']"
                        :street="$order['billing_street']"
                        :housenumber="$order['billing_housenumber']"
                        :postalcode="$order['billing_zipcode']"
                        :city="$order['billing_city']"
                        :country="$order['billing_country']"
                        :email="$order['billing_email']"
                        :phone="$order['billing_phone']"
                    ></x-address-view>
                </div>
            </div>
            <div class="text-sm">
                <x-address-view
                    title="Klantgegevens"
                    :name="$order['delivery_name']"
                    :companyname="$order['delivery_companyname']"
                    :street="$order['delivery_street']"
                    :housenumber="$order['delivery_housenumber']"
                    :postalcode="$order['delivery_zipcode']"
                    :city="$order['delivery_city']"
                    :country="$order['delivery_country']"
                    :email="$order['delivery_email']"
                    :phone="$order['delivery_phone']"
                ></x-address-view>
            </div>
            <div>
                <x-header-row>
                    <div class="flex flex-row">
                        <div class="w-1/2">
                            <h2 class="text-md font-bold text-white">Artikelen</h2>
                        </div>
                        <div class="w-1/2">
                            <h2 class="text-md font-bold text-white">Hierbij uw verzendlabel</h2>
                        </div>
                    </div>
                </x-header-row>
                <div class="flex flex-row text-sm">
                    <div class="w-1/2">
                        @if($order['orderLines'])
                            @foreach($order['orderLines'] as $orderLine)
                                <div class="flex w-full flex-row items-center py-4">
                                    <div class="px-2">
                                        {{ $orderLine['amount_ordered'] }} x
                                    </div>
                                    <div class="grow">
                                        <x-product-row
                                            title="Product"
                                            :value="$orderLine['name']"
                                            :price="($orderLine['amount_ordered'] * $orderLine['price_per_unit'])"
                                        >
                                        </x-product-row>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="w-1/2">
                        <div class="w-fit border-4 border-dotted">
                            <img style="" class="m-2" src="{{ $labelImage }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
