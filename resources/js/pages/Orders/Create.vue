<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ref } from 'vue';
import AddRemoveButton from '@/components/AddRemoveButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    shippingMethods: Object
});

const reference = ref();
const productCombinationId = ref();
const senderName = ref();
const senderCompanyName = ref();
const senderStreet = ref();
const senderNumber = ref();
const senderPostal = ref();
const senderCity = ref();
const senderCountry = ref();
const senderEmail = ref();
const senderPhone = ref();
const receiverName = ref();
const receiverCompanyName = ref();
const receiverStreet = ref();
const receiverNumber = ref();
const receiverPostal = ref();
const receiverCity = ref();
const receiverCountry = ref();
const receiverEmail = ref();
const receiverPhone = ref();
const orderLines = ref([]);
const method = ref({});

const addOrderLine = () => {
    orderLines.value.push({
        amount: 1,
        name: '',
        ean: null,
        sku: null
    });

    form.orderLines = orderLines.value;
};

const removeOrderLine = key => {
    orderLines.value.splice(key, 1);
    form.orderLines = orderLines.value;
};

const selectProductCombinationId = event => {
    form.productCombinationId = event.target.value;
}

const form = useForm({
    reference,
    productCombinationId,
    senderName,
    senderCompanyName,
    senderStreet,
    senderNumber,
    senderPostal,
    senderCity,
    senderCountry,
    senderEmail,
    senderPhone,
    receiverName,
    receiverCompanyName,
    receiverStreet,
    receiverNumber,
    receiverPostal,
    receiverCity,
    receiverCountry,
    receiverEmail,
    receiverPhone,
    orderLines
});

const submit = () => {
    form.post('/orders');
};
</script>

<template>
    <div class="grid w-full place-items-center">
        <div class="w-7xl px-2">
            <div class="flex flex-row items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold">Order aanmaken</h1>
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
                        <Input class="w-3/4" v-model="form.reference"></Input>
                    </div>
                    <div class="flex flex-col">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Verzendmethode</h2>
                        </div>
                        <select
                            v-model="method"
                            class="m-2 p-2 border-2 border-gray-400">
                            <option disabled selected>Selecteer een verzendmethode...</option>
                            <option :key="key" v-for="(method,key) in props.shippingMethods" :value="method">
                                {{ method.name }}
                            </option>
                        </select>
                        <select
                            v-if="method.combinations"
                            @change="selectProductCombinationId"
                            class="m-2 p-2 border-2 border-gray-400">
                            <option disabled selected>Selecteer een optie...</option>
                            <option :key="key" v-for="(combination,key) in method.combinations" :value="combination.id">
                                {{ combination.name }}
                            </option>
                        </select>
                    </div>
                    <div class="my-4 mb-6 flex flex-col border-t-4">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Afzender</h2>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Naam</Label>
                            <Input class="w-3/4" v-model="form.senderName"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Bedrijfsnaam</Label>
                            <Input class="w-3/4" v-model="form.senderCompanyName"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Straat</Label>
                            <Input class="w-3/4" v-model="form.senderStreet"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Huisnummer</Label>
                            <Input class="w-3/4" v-model="form.senderNumber"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Postcode</Label>
                            <Input class="w-3/4" v-model="form.senderPostal"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Plaats</Label>
                            <Input class="w-3/4" v-model="form.senderCity"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Land</Label>
                            <Input class="w-3/4" v-model="form.senderCountry"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">E-mailadres</Label>
                            <Input class="w-3/4" v-model="form.senderEmail"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Telefoonnummer</Label>
                            <Input class="w-3/4" v-model="form.senderPhone"></Input>
                        </div>
                    </div>
                    <div class="my-4 mb-6 flex flex-col border-t-4">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Ontvanger</h2>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Naam</Label>
                            <Input class="w-3/4" v-model="form.receiverName"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Bedrijfsnaam</Label>
                            <Input class="w-3/4" v-model="form.receiverCompanyName"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Straat</Label>
                            <Input class="w-3/4" v-model="form.receiverStreet"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Huisnummer</Label>
                            <Input class="w-3/4" v-model="form.receiverNumber"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Postcode</Label>
                            <Input class="w-3/4" v-model="form.receiverPostal"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Plaats</Label>
                            <Input class="w-3/4" v-model="form.receiverCity"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Land</Label>
                            <Input class="w-3/4" v-model="form.receiverCountry"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">E-mailadres</Label>
                            <Input class="w-3/4" v-model="form.receiverEmail"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Telefoonnummer</Label>
                            <Input class="w-3/4" v-model="form.receiverPhone"></Input>
                        </div>
                    </div>
                    <div class="my-4 mb-6 flex flex-col border-t-4">
                        <div class="flex flex-row items-center justify-between">
                            <div class="px-2 py-4">
                                <h2 class="text-lg font-bold">Producten</h2>
                            </div>
                            <AddRemoveButton classes="bg-green-500 hover:bg-green-700">
                                <Icon name="plus" @click.prevent="addOrderLine" />
                            </AddRemoveButton>
                        </div>
                        <div :key="key" v-for="(product, key) in orderLines">
                            <div class="my-4 mb-6 flex flex-row items-center border-t-4">
                                <div class="grow">
                                    <div class="flex flex-row items-center py-2">
                                        <Label class="w-1/4 px-2">Aantal</Label>
                                        <Input class="w-3/4" v-model="product.amount"></Input>
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
                                <AddRemoveButton classes="bg-red-500 hover:bg-red-700">
                                    <Icon name="minus" @click.prevent="removeOrderLine(key)" />
                                </AddRemoveButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
