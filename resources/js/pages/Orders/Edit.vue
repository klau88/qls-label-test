<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ref } from 'vue';
import AddRemoveButton from '@/components/AddRemoveButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
    shippingMethods: Object,
});

const order = ref(props.order);

const selectedShippingMethod = () => props.shippingMethods.filter(method => method.combinations.filter(combination => combination.id === order.value.product_combination_id).length)[0];
const method = ref(selectedShippingMethod());

const addProduct = () => {
    form.order_lines.push({
        id: null,
        order_id: form.id,
        amount_ordered: 1,
        name: '',
    });
};

const form = useForm(order.value);

const removeProduct = (key) => {
    form.order_lines.splice(key, 1);
};

const selectProductCombinationId = (event) => {
    form.order.product_combination_id = event.target.value;
};

const submit = () => {
    form.put(`/orders/${order.value.id}`);
};
</script>

<template>
    <div class="grid w-full place-items-center">
        <div class="w-7xl px-2">
            <div class="flex flex-row items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold">Bestelling wijzigen</h1>
                </div>
                <div>
                    <button
                        class="m-2 flex items-center justify-center rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700"
                        type="submit"
                        form="updateOrder"
                        @click.prevent="submit"
                    >
                        Wijziging opslaan
                    </button>
                </div>
            </div>
            <div>
                <form id="updateOrder">
                    <div class="flex flex-row items-center">
                        <Label class="w-1/4 px-2">Referentie</Label>
                        <Input class="w-3/4" v-model="form.number" required></Input>
                    </div>
                    <div class="flex flex-col">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Verzendmethode</h2>
                        </div>

                        <select v-model="method" class="m-2 border-2 border-gray-400 p-2">
                            <option disabled>Selecteer een verzendmethode...</option>
                            <option
                                :key="key"
                                v-for="(method, key) in props.shippingMethods"
                                :value="method"
                            >
                                {{ method.name }}
                            </option>
                        </select>

                        <select @change="selectProductCombinationId" class="m-2 border-2 border-gray-400 p-2">
                            <option disabled selected>Selecteer een optie...</option>
                            <option
                                :key="key"
                                v-for="(combination, key) in method.combinations"
                                :value="combination.id"
                                :selected="combination.id === form.product_combination_id"
                            >
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
                            <Input class="w-3/4" v-model="form.billing_name"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Bedrijfsnaam</Label>
                            <Input class="w-3/4" v-model="form.billing_companyname"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Straat</Label>
                            <Input class="w-3/4" v-model="form.billing_street"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Huisnummer</Label>
                            <Input class="w-3/4" v-model="form.billing_housenumber"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Postcode</Label>
                            <Input class="w-3/4" v-model="form.billing_zipcode"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Plaats</Label>
                            <Input class="w-3/4" v-model="form.billing_city"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Land</Label>
                            <Input class="w-3/4" v-model="form.billing_country"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">E-mailadres</Label>
                            <Input class="w-3/4" v-model="form.billing_email"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Telefoonnummer</Label>
                            <Input class="w-3/4" v-model="form.billing_phone"></Input>
                        </div>
                    </div>
                    <div class="my-4 mb-6 flex flex-col border-t-4">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Ontvanger</h2>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Naam</Label>
                            <Input class="w-3/4" v-model="form.delivery_name"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Bedrijfsnaam</Label>
                            <Input class="w-3/4" v-model="form.delivery_companyname"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Straat</Label>
                            <Input class="w-3/4" v-model="form.delivery_street"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Huisnummer</Label>
                            <Input class="w-3/4" v-model="form.delivery_housenumber"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Postcode</Label>
                            <Input class="w-3/4" v-model="form.delivery_zipcode"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Plaats</Label>
                            <Input class="w-3/4" v-model="form.delivery_city"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Land</Label>
                            <Input class="w-3/4" v-model="form.delivery_country"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">E-mailadres</Label>
                            <Input class="w-3/4" v-model="form.delivery_email"></Input>
                        </div>
                        <div class="flex flex-row items-center py-2">
                            <Label class="w-1/4 px-2">Telefoonnummer</Label>
                            <Input class="w-3/4" v-model="form.delivery_phone"></Input>
                        </div>
                    </div>
                    <div class="my-4 mb-6 flex flex-col border-t-4">
                        <div class="flex flex-row items-center justify-between">
                            <div class="px-2 py-4">
                                <h2 class="text-lg font-bold">Producten</h2>
                            </div>
                            <AddRemoveButton class="bg-green-500 hover:bg-green-700">
                                <Icon name="plus" @click.prevent="addProduct" />
                            </AddRemoveButton>
                        </div>
                        <div :key="key" v-for="(product, key) in form.order_lines">
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
                                    <div v-if="product.ean" class="flex flex-row items-center py-2">
                                        <Label class="w-1/4 px-2">EAN</Label>
                                        <Input class="w-3/4" v-model="product.ean"></Input>
                                    </div>
                                    <div v-if="product.sku" class="flex flex-row items-center py-2">
                                        <Label class="w-1/4 px-2">SKU</Label>
                                        <Input class="w-3/4" v-model="product.sku"></Input>
                                    </div>
                                </div>
                                <AddRemoveButton class="bg-red-500 hover:bg-red-700">
                                    <Icon name="minus" @click.prevent="removeProduct(key)" />
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
