<div class="w-full">
    @if(isset($title))
        <x-header-row>
            <h2 class="text-md font-bold text-white">{{ $title }}</h2>
        </x-header-row>
    @endif
    <table>
        <tbody class="m-2">
        @if(isset($name))
            <tr>
                <td>Naam</td>
                <td class="px-2">{{ $name }}</td>
            </tr>
        @endif
        @if(isset($companyname))
            <tr>
                <td>Bedrijfsnaam</td>
                <td class="px-2">
                    {{ $companyname }}
                </td>
            </tr>
        @endif
        <tr v-if="addressTitle">
            <td>
                <h3>Adres</h3>
            </td>
            <td class="px-2">
                <div class="flex flex-col">
                    <div>{{ $street }} {{ $housenumber }}</div>
                    <div>{{ $postalcode }} {{ $city }}</div>
                    <div>{{ $country }}</div>
                </div>
            </td>
        </tr>

        @if(isset($email))
            <tr>
                <td>E-mailadres</td>
                <td class="px-2">{{ $email }}</td>
            </tr>
        @endif
        @if(isset($phone))
            <tr>
                <td>Telefoonnummer</td>
                <td class="px-2">{{ $phone }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
