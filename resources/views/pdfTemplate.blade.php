<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pakbon {{ $shipment->id }}</title>

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
                    <h3 class="text-sm">Bestelnummer: {{ $shipment->reference }}</h3>
                </div>
                <div class="text-xs">
                    <x-address-view
                        title="Afzender"
                        :name="$shipment->sender_name"
                        :companyname="$shipment->sender_companyname"
                        :street="$shipment->sender_street"
                        :housenumber="$shipment->sender_housenumber"
                        :postalcode="$shipment->sender_postalcode"
                        :city="$shipment->sender_city"
                        :country="$shipment->sender_country"
                        :email="$shipment->sender_email"
                        :phone="$shipment->sender_phone"
                    ></x-address-view>
                </div>
            </div>
            <div>
                <x-address-view
                    title="Klantgegevens"
                    :name="$shipment->receiver_name"
                    :companyname="$shipment->receiver_companyname"
                    :street="$shipment->receiver_street"
                    :housenumber="$shipment->receiver_housenumber"
                    :postalcode="$shipment->receiver_postalcode"
                    :city="$shipment->receiver_city"
                    :country="$shipment->receiver_country"
                    :email="$shipment->receiver_email"
                    :phone="$shipment->receiver_phone"
                ></x-address-view>
            </div>
            @if($shipment['products'])
                <div>
                    <x-header-row>
                        <h2 class="text-md font-bold text-white">Producten</h2>
                    </x-header-row>
                    @foreach($shipment['products'] as $product)
                        <div class="w-fit">
                            <div class="flex w-full flex-row items-center py-4">
                                <div class="px-2">
                                    {{ $product->amount }} x
                                </div>
                                <div class="grow">
                                    <x-product-row
                                        title="Product"
                                        :value="$product->name"
                                    ></x-product-row>
                                    @if($product->ean)
                                        <x-product-row
                                            title="EAN"
                                            :value="$product->ean"
                                        ></x-product-row>
                                    @endif
                                    @if($product->sku)
                                        <x-product-row
                                            title="SKU"
                                            :value="$product->sku"
                                        ></x-product-row>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div>
                <x-header-row>
                    <h2 class="text-md font-bold text-white">Hierbij uw verzendlabel</h2>
                </x-header-row>
                <div class="w-fit border-4 border-dotted">
                    <img style="" width="300px" class="m-2" src="{{ $labelImage }}" />
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
