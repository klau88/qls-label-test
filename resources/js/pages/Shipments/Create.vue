<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { Input } from '@/components/ui/input';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const amount = ref(1);
const products = ref([]);

const props = defineProps({
    order: String,
});

const addProduct = () => {
    products.value.push({
        amount_ordered: 1,
        name: '',
    });
};

const removeProduct = (key) => {
    products.value.splice(key, 1);
};

const submit = async () => {
    await axios
        .post('/shipments/store', {
            order: props.order,
        })
        .then((response) => {
            console.log('response');
            console.log(response);
            window.location.href = '/shipments';
        });
};

onMounted(() => {
    amount.value = props.order.order_lines.length;
    products.value = props.order.order_lines;
});
</script>

<template>
    <div class="w-full grid place-items-center">
        <div class="px-2 w-7xl">
            <div class="flex flex-row items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold">Zending aanmaken</h1>
                </div>
                <div>
                    <button
                        class="m-2 flex items-center justify-center rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700"
                        type="submit"
                        form="shipmentForm"
                        @click.prevent="submit"
                    >
                        Opslaan
                    </button>
                </div>
            </div>
            <div>
                <form id="shipmentForm">
                    <div class="flex flex-row items-center">
                        <Label class="w-1/4 px-2">Referentie</Label>
                        <Input class="w-3/4" v-model="order.number"></Input>
                    </div>
                    <div class="my-4 mb-6 flex flex-col border-y-4">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Ontvanger</h2>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Naam</Label>
                            <Input class="w-3/4" v-model="order.delivery_address.name"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Bedrijfsnaam</Label>
                            <Input class="w-3/4" v-model="order.delivery_address.companyname"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Straat</Label>
                            <Input class="w-3/4" v-model="order.delivery_address.street"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Huisnummer</Label>
                            <Input class="w-3/4" v-model="order.delivery_address.housenumber"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Plaats</Label>
                            <Input class="w-3/4" v-model="order.delivery_address.city"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Land</Label>
                            <Input class="w-3/4" v-model="order.delivery_address.country"></Input>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-row items-center justify-between">
                            <div class="px-2 py-4">
                                <h2 class="text-lg font-bold">Producten</h2>
                            </div>
                            <div class="m-2 flex items-center justify-center rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700">
                                <Icon name="plus" @click.prevent="addProduct" />
                            </div>
                        </div>
                        <div :key="key" v-for="(product, key) in order.order_lines">
                            <div class="my-4 mb-6 flex flex-row items-center border-t-4">
                                <div class="grow">
                                    <div class="flex flex-row items-center py-2">
                                        <Label class="w-1/4 px-2">Aantal</Label>
                                        <Input class="w-3/4" v-model="product.amount_ordered"></Input>
                                    </div>
                                    <div class="flex flex-row items-center py-2">
                                        <Label class="w-1/4 px-2">Product</Label>
                                        <Input class="w-3/4" v-model="product.name"></Input>
                                    </div>
                                    <div class="flex flex-row items-center py-2">
                                        <Label class="w-1/4 px-2">EAN</Label>
                                        <Input class="w-3/4" v-model="product.ean"></Input>
                                    </div>
                                    <div class="flex flex-row items-center py-2">
                                        <Label class="w-1/4 px-2">SKU</Label>
                                        <Input class="w-3/4" v-model="product.sku"></Input>
                                    </div>
                                </div>
                                <div class="m-4 h-full bg-red-200 p-2">
                                    <Icon name="minus" @click.prevent="removeProduct(key)" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
