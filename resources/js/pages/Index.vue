<script setup>
import axios from 'axios';

const props = defineProps({
    order: Object
});

const synchronize = async () => {
    console.log('clicked');
    console.log(props.order);

    await axios.post('/shipments/store', {
        order: props.order
    }).then(response => {
        console.log('response');
        console.log(response);
        window.location.href = '/shipments';
    });
}
</script>

<template>
    <div class="grid h-screen place-items-center">
        <button class="p-2 rounded text-white bg-blue-500" @click.prevent="synchronize">Order(s) ophalen</button>
    </div>
</template>