<script setup lang="ts">
import axios from 'axios';
import { nextTick, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const selected = ref(null);

defineProps({
    order: Object,
    shipments: Object,
    csrf: String,
});

const makeLabel = async (shipment) => {
    await nextTick();
    selected.value = shipment;

    await axios
        .post(
            '/generatePdf',
            {
                shipment: JSON.stringify(shipment),
            },
            {
                responseType: 'blob',
            },
        )
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `packing_slip_${shipment}.pdf`);
            document.body.appendChild(link);
            link.click();
            link.remove();
        });
};

const form = useForm({});

const deleteShipment = (id) => {
    form.delete(`/shipments/${id}/destroy`);
}
</script>

<template>
    <div class="flex flex-row items-center justify-between">
        <div class="p-2">
            <h1 class="text-4xl font-bold">Zendingen</h1>
        </div>
        <div class="p-2">
            <a href="/shipments/create" class="m-2 rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700">Nieuwe zending</a>
        </div>
    </div>
    <table>
        <thead>
            <tr class="bg-black text-white">
                <th>Id</th>
                <th>Vervoerder</th>
                <th>Naam</th>
                <th>Bedrijfsnaam</th>
                <th>Adres</th>
                <th>E-mailadres</th>
                <th>Telefoonnummer</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <tr :key="key" v-for="(shipment, key) in shipments">
                <td class="px-2">{{ shipment.reference }}</td>
                <td class="px-2">{{ shipment.shipment_name }}</td>
                <td class="px-2">{{ shipment.receiver_name }}</td>
                <td class="px-2">{{ shipment.receiver_company_name }}</td>
                <td class="px-2">
                    <div class="flex flex-col">
                        <div>{{ shipment.receiver_street }} {{ shipment.receiver_housenumber }}</div>
                        <div>{{ shipment.receiver_postalcode }} {{ shipment.receiver_city }}</div>
                        <div>{{ shipment.receiver_country }}</div>
                    </div>
                </td>
                <td class="px-2">{{ shipment.receiver_email }}</td>
                <td class="px-2">{{ shipment.receiver_phone }}</td>
                <td class="px-2">
                    <div class="flex flex-row">
                        <button
                            class="rounded mx-2 px-4 py-2 font-bold text-white bg-yellow-500 hover:bg-yellow-700"
                            @click.prevent="makeLabel(shipment.id)"
                        >
                            Download pakbon
                        </button>
                        <a
                            class="rounded mx-2 px-4 py-2 text-white bg-blue-500 hover:bg-blue-700"
                           :href="`/shipments/${shipment.id}`"
                        >
                            Openen
                        </a>
                        <a
                            class="rounded mx-2 px-4 py-2 text-white bg-green-500 hover:bg-green-700"
                            :href="`/shipments/${shipment.id}/edit`"
                        >
                            Wijzigen
                        </a>
                        <button
                            class="rounded mx-2 px-4 py-2 text-white bg-red-500 hover:bg-red-700"
                            @click.prevent="deleteShipment(shipment.id)"
                        >
                            Verwijderen
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
