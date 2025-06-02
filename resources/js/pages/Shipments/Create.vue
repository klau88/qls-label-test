<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { Input } from '@/components/ui/input';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    order: Object,
    shippingMethods: Object
});

const newOrder = ref(props.order);
const method = ref({});

const addProduct = () => {
    console.log(newOrder.value.order_lines);
    newOrder.value.order_lines.push({
        amount_ordered: 1,
        name: '',
    });
};

const removeProduct = key => {
    newOrder.value.order_lines.splice(key, 1);
};

const selectProductCombinationId = event => {
    newOrder.value.product_combination_id = event.target.value;
}

const submit = async () => {
    await axios
        .post('/shipments/store', {
            order: newOrder.value,
        })
        .then(() => {
            window.location.href = '/shipments';
        });
};
</script>

<template>
    <div class="grid w-full place-items-center">
        <div class="w-7xl px-2">
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
                        <Input class="w-3/4" v-model="newOrder.number"></Input>
                    </div>
                    <div class="my-4 mb-6 flex flex-col border-y-4">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Ontvanger</h2>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Naam</Label>
                            <Input class="w-3/4" v-model="newOrder.delivery_address.name"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Bedrijfsnaam</Label>
                            <Input class="w-3/4" v-model="newOrder.delivery_address.companyname"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Straat</Label>
                            <Input class="w-3/4" v-model="newOrder.delivery_address.street"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Huisnummer</Label>
                            <Input class="w-3/4" v-model="newOrder.delivery_address.housenumber"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Postcode</Label>
                            <Input class="w-3/4" v-model="newOrder.delivery_address.zipcode"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Plaats</Label>
                            <Input class="w-3/4" v-model="newOrder.delivery_address.city"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Land</Label>
                            <Input class="w-3/4" v-model="newOrder.delivery_address.country"></Input>
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
                        <div :key="key" v-for="(product, key) in newOrder.order_lines">
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
                    <div>
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Verzendmethode</h2>
                        </div>
                        <div>
                            <select
                                v-model="method"
                                class="p-2 border-2 border-gray-400">
                                <option disabled selected>Selecteer een verzendmethode...</option>
                                <option v-for="method in props.shippingMethods" :value="method">
                                    {{ method.name }}
                                </option>
                            </select>
                        </div>
                        <div v-if="method.combinations">
                            <select
                                @change="selectProductCombinationId"
                                class="p-2 border-2 border-gray-400">
                                <option disabled selected>Selecteer een optie...</option>
                                <option v-for="combination in method.combinations" :value="combination.id">
                                    {{ combination.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
