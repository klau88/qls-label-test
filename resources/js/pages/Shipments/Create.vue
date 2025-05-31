<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { Input } from '@/components/ui/input';
import { onMounted, ref } from 'vue';
import axios from 'axios';

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

const removeProduct = key => {
    products.value.splice(key, 1);
}

const submit = async () => {
    await axios.post('/shipments/store', {
        order: props.order
    }).then(response => {
        console.log('response');
        console.log(response);
        window.location.href = '/shipments';
    });
}

onMounted(() => {
    amount.value = props.order.order_lines.length;
    products.value = props.order.order_lines;
});
</script>

<template>
    <div class="px-2">
        <div class="flex flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold">Zending aanmaken</h1>
            </div>
            <div>
                <button
                    class="m-2 rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 flex justify-center items-center"
                    type="submit"
                    form="shipmentForm"
                    @click.prevent="submit"
                >
                    Opslaan
                </button>
            </div>
        </div>
        <div>
            <form id="shipmentForm" class="w-full max-w-lg">
                <div class="flex flex-row items-center">
                    <Label>Referentie</Label>
                    <Input v-model="order.number"></Input>
                </div>
                <div class="mx-3 mb-6 flex flex-col">
                    <div>
                        <h2>Ontvanger</h2>
                    </div>
                    <div class="flex flex-row items-center">
                        <Label class="px-2">Naam</Label>
                        <Input v-model="order.delivery_address.name"></Input>
                    </div>
                    <div class="flex flex-row items-center">
                        <Label class="px-2">Bedrijfsnaam</Label>
                        <Input v-model="order.delivery_address.companyname"></Input>
                    </div>
                    <div class="flex flex-row items-center">
                        <Label class="px-2">Straat</Label>
                        <Input v-model="order.delivery_address.street"></Input>
                    </div>
                    <div class="flex flex-row items-center">
                        <Label class="px-2">Huisnummer</Label>
                        <Input v-model="order.delivery_address.housenumber"></Input>
                    </div>
                    <div class="flex flex-row items-center">
                        <Label class="px-2">Plaats</Label>
                        <Input v-model="order.delivery_address.city"></Input>
                    </div>
                    <div class="flex flex-row items-center">
                        <Label class="px-2">Land</Label>
                        <Input v-model="order.delivery_address.country"></Input>
                    </div>
                </div>
                <div class="flex flex-row justify-between items-center">
                    <div class="flex flex-col">
                        <div class="flex flex-row justify-between items-center">
                            <div>
                                <h2>Products</h2>
                            </div>
                            <div class="m-2 rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 flex justify-center items-center">
                                <Icon
                                    name="plus"
                                    @click.prevent="addProduct"
                                />
                            </div>
                        </div>
                        <div class="flex flex-col" :key="key" v-for="(product, key) in order.order_lines">
                            <div class="flex flex-row items-center">
                                <div class="flex flex-col">
                                    <div class="flex flex-row items-center">
                                        <Label class="px-2">Aantal</Label>
                                        <Input v-model="product.amount_ordered"></Input>
                                    </div>
                                    <div class="flex flex-row items-center">
                                        <Label class="px-2">Product</Label>
                                        <Input v-model="product.name"></Input>
                                    </div>
                                    <div class="flex flex-row items-center">
                                        <Label class="px-2">EAN</Label>
                                        <Input v-model="product.ean"></Input>
                                    </div>
                                    <div class="flex flex-row items-center">
                                        <Label class="px-2">SKU</Label>
                                        <Input v-model="product.sku"></Input>
                                    </div>
                                </div>
                                <div class="h-full p-2 bg-red-200">
                                    <Icon
                                        name="minus"
                                        @click.prevent="removeProduct(key)"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped></style>
