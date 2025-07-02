<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { nextTick, ref } from 'vue';

const selected = ref(null);

defineProps({
    orders: Object,
});

const makeLabel = async (order) => {
    await nextTick();
    selected.value = order;

    await axios
        .post(
            '/generatePdf',
            {
                order: JSON.stringify(order),
            },
            {
                responseType: 'blob',
            },
        )
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `packing_slip_${order}.pdf`);
            document.body.appendChild(link);
            link.click();
            link.remove();
        });
};

const form = useForm({});

const deleteOrder = (id) => {
    form.delete(`/orders/${id}`);
};
</script>

<template>
    <div class="flex flex-row items-center justify-between">
        <div class="p-2">
            <h1 class="text-4xl font-bold">Bestellingen</h1>
        </div>
        <div class="p-2">
            <a href="/orders/create" class="m-2 rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700">Nieuwe bestelling</a>
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
            <tr :key="key" v-for="(order, key) in orders">
                <td class="px-2">{{ order.number }}</td>
                <td class="px-2">{{ order.product_combination_id }}</td>
                <td class="px-2">{{ order.billing_name }}</td>
                <td class="px-2">{{ order.billing_companyname }}</td>
                <td class="px-2">
                    <div class="flex flex-col">
                        <div>{{ order.billing_street }} {{ order.billing_housenumber }}</div>
                        <div>{{ order.billing_zipcode }} {{ order.billing_city }}</div>
                        <div>{{ order.billing_country }}</div>
                    </div>
                </td>
                <td class="px-2">{{ order.billing_email }}</td>
                <td class="px-2">{{ order.billing_phone }}</td>
                <td class="px-2">
                    <div class="flex flex-row">
                        <button
                            class="mx-2 rounded bg-yellow-500 px-4 py-2 font-bold text-white hover:bg-yellow-700"
                            @click.prevent="makeLabel(order.id)"
                        >
                            Download pakbon
                        </button>
                        <a class="mx-2 rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-700" :href="`/orders/${order.id}`"> Openen </a>
                        <a class="mx-2 rounded bg-green-500 px-4 py-2 text-white hover:bg-green-700" :href="`/orders/${order.id}/edit`"> Wijzigen </a>
                        <button class="mx-2 rounded bg-red-500 px-4 py-2 text-white hover:bg-red-700" @click.prevent="deleteOrder(order.id)">
                            Verwijderen
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
