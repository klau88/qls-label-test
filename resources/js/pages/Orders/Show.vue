<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    order: Object,
    shippingMethod: Object
});

const form = useForm(props.order);

const deleteOrder = () => {
    form.delete(`/orders/${props.order.id}`);
};
</script>

<template>
    <div class="grid w-full place-items-center">
        <div class="w-7xl px-2">
            <div class="flex flex-row items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold">Order {{ order.number }}</h1>
                </div>
                <div class="flex flex-row">
                    <a
                        class="m-2 flex items-center justify-center rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700"
                        type="submit"
                        href="/orders"
                    >
                        Zendingen
                    </a>
                    <a
                        class="m-2 flex items-center justify-center rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700"
                        type="submit"
                        :href="`/orders/${order.id}/edit`"
                    >
                        Wijzigen
                    </a>
                    <button
                        class="m-2 flex items-center justify-center rounded bg-red-500 px-4 py-2 font-bold text-white hover:bg-red-700"
                        type="submit"
                        @click.prevent="deleteOrder"
                    >
                        Verwijderen
                    </button>
                </div>
            </div>
            <div>
                <div class="flex flex-col">
                    <div class="px-2 py-4">
                        <h2 class="text-lg font-bold">Verzendmethode</h2>
                    </div>
                    <div class="flex flex-row py-2">
                        <div class="text-md w-1/4 px-2">Vervoerder</div>
                        <div class="w-3/4">
                            {{ shippingMethod.name }}
                        </div>
                    </div>
                    <div class="flex flex-row py-2">
                        <div class="text-md w-1/4 px-2">Verzendoptie</div>
                        <div class="w-3/4">
                            {{ shippingMethod.option.name }}
                        </div>
                    </div>
                </div>
                <div class="my-4 mb-6 flex flex-col border-t-4">
                    <div class="px-2 py-4">
                        <h2 class="text-lg font-bold">Afzender</h2>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Naam</div>
                        <div class="w-3/4">{{ order.billing_name }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Bedrijfsnaam</div>
                        <div class="w-3/4">{{ order.billing_companyname }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Straat</div>
                        <div class="w-3/4">{{ order.billing_street }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Huisnummer</div>
                        <div class="w-3/4">{{ order.billing_housenumber }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Postcode</div>
                        <div class="w-3/4">{{ order.billing_zipcode }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Plaats</div>
                        <div class="w-3/4">{{ order.billing_city }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Land</div>
                        <div class="w-3/4">{{ order.billing_country }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">E-mailadres</div>
                        <div class="w-3/4">{{ order.billing_email }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Telefoonnummer</div>
                        <div class="w-3/4">{{ order.billing_phone }}</div>
                    </div>
                </div>
                <div class="my-4 mb-6 flex flex-col border-t-4">
                    <div class="px-2 py-4">
                        <h2 class="text-lg font-bold">Ontvanger</h2>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Naam</div>
                        <div class="w-3/4">{{ order.delivery_name }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Bedrijfsnaam</div>
                        <div class="w-3/4">{{ order.delivery_companyname }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Straat</div>
                        <div class="w-3/4">{{ order.delivery_street }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Huisnummer</div>
                        <div class="w-3/4">{{ order.delivery_housenumber }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Postcode</div>
                        <div class="w-3/4">{{ order.delivery_zipcode }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Plaats</div>
                        <div class="w-3/4">{{ order.delivery_city }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Land</div>
                        <div class="w-3/4">{{ order.delivery_country }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">E-mailadres</div>
                        <div class="w-3/4">{{ order.delivery_email }}</div>
                    </div>
                    <div class="flex flex-row items-center py-2">
                        <div class="w-1/4 px-2">Telefoonnummer</div>
                        <div class="w-3/4">{{ order.delivery_phone }}</div>
                    </div>
                </div>
                <div>
                    <div class="flex flex-row items-center justify-between">
                        <div class="px-2 py-4">
                            <h2 class="text-lg font-bold">Producten</h2>
                        </div>
                    </div>
                    <div :key="key" v-for="(product, key) in order.order_lines">
                        <div class="my-4 mb-6 flex flex-row items-center border-t-4">
                            <div class="grow">
                                <div class="flex flex-row items-center py-2">
                                    <div class="w-1/4 px-2">Aantal</div>
                                    <div class="w-3/4">{{ product.amount_ordered }}</div>
                                </div>
                                <div class="flex flex-row items-center py-2">
                                    <div class="w-1/4 px-2">Product</div>
                                    <div class="w-3/4">{{ product.name }}</div>
                                </div>
                                <div v-if="product.hs_code" class="flex flex-row items-center py-2">
                                    <div class="w-1/4 px-2">HS-code</div>
                                    <div class="w-3/4">{{ product.hs_code }}</div>
                                </div>
                                <div v-if="product.ean" class="flex flex-row items-center py-2">
                                    <div class="w-1/4 px-2">EAN</div>
                                    <div class="w-3/4">{{ product.ean }}</div>
                                </div>
                                <div v-if="product.sku" class="flex flex-row items-center py-2">
                                    <div class="w-1/4 px-2">SKU</div>
                                    <div class="w-3/4">{{ product.sku }}</div>
                                </div>
                                <div v-if="product.price_per_unit" class="flex flex-row items-center py-2">
                                    <div class="w-1/4 px-2">Prijs per stuk</div>
                                    <div class="w-3/4">{{ product.price_per_unit }}</div>
                                </div>
                                <div v-if="product.weight_per_unit" class="flex flex-row items-center py-2">
                                    <div class="w-1/4 px-2">Gewicht per stuk</div>
                                    <div class="w-3/4">{{ product.weight_per_unit }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
